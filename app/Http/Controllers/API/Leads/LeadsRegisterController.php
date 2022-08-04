<?php

namespace App\Http\Controllers\API\Leads;

use App\Current;
use App\Http\APIResponse;
use App\Http\Controllers\ApiEditController;
use App\Mail\SubscriptionContractFillLinkMail;
use App\Models\Clients\Client;
use App\Models\Dictionaries\ClientStatus;
use App\Models\Dictionaries\LeadStatus;
use App\Models\Dictionaries\SubscriptionStatus;
use App\Models\Leads\Lead;
use App\Models\Subscriptions\Subscription;
use App\Models\User\User;
use App\Scopes\ForOrganization;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class LeadsRegisterController extends ApiEditController
{
    protected array $rules = [
        'lastname' => 'required',
        'firstname' => 'required',
        'patronymic' => 'required',
        'phone' => 'required',
        'email' => 'required|email|bail',
        'service_id' => 'required',
    ];
    protected array $titles = [
        'lastname' => 'Фамилия',
        'firstname' => 'Имя',
        'patronymic' => 'Отчество',
        'phone' => 'Телефон',
        'email' => 'Email',
        'service_id' => 'Услуга',
    ];

    public function register(Request $request): JsonResponse
    {
        $id = $request->input('lead_id');
        $current = Current::get($request);

        /** @var Lead $lead */
        if ($id === null ||
            null === ($lead = Lead::query()
                ->with(['status', 'service', 'service.trainingBase', 'service.sportKind', 'client.user.profile'])
                ->where('id', $id)
                ->tap(new ForOrganization($current->organizationId()))
                ->first())
        ) {
            return APIResponse::notFound('Лид не найден');
        }

        // validate data
        $data = $this->getData($request);
        if ($errors = $this->validate($data, $this->rules, $this->titles)) {
            return APIResponse::validationError($errors);
        }

        try {
            DB::transaction(static function () use ($lead, $data, $current) {
                // create client
                $user = new User();
                $user->save();

                $user->profile->firstname = $data['firstname'];
                $user->profile->lastname = $data['lastname'];
                $user->profile->patronymic = $data['patronymic'];
                $user->profile->email = $data['email'];
                $user->profile->phone = $data['phone'];
                $user->profile->save();

                $client = new Client();
                $client->setStatus(ClientStatus::active, false);
                $client->organization_id = $current->organizationId();
                $client->user_id = $user->id;
                $client->save();

                // attach the client to the lead
                $lead->client_id = $client->id;
                $lead->setStatus(LeadStatus::client_created, false);
                $lead->save();

                // create subscription
                $subscription = new Subscription();
                $subscription->setStatus(SubscriptionStatus::new, false);
                $subscription->organization_id = $current->organizationId();
                $subscription->client_id = $client->id;
                $subscription->service_id = $data['service_id'];
                $subscription->save();

                // send a link to client
                try {
                    Mail::send(new SubscriptionContractFillLinkMail($subscription));
                } catch (Exception $exception) {
                    Log::channel('outgoing_mail_errors')->error($exception->getMessage());
                    throw $exception;
                }
            });
        } catch (Exception $exception) {
            return APIResponse::error($exception->getMessage());
        }

        return APIResponse::success('Клиент зарегистрирован. Ссылка на заполнение договора отправлена клиенту.');
    }
}
