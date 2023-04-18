<?php


namespace App\Helpers;


use App\Models\Clients\Client;
use App\Models\Clients\ClientWard;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class DuplicatesFinder
{
    public static function clientDuplicates (
        int $organizationId,
        string $lastname,
        string $firstname,
        string $patronymic,
        string $phone,
        string $email
    ): ?Collection {
        $clientDuplicates = Client::query()
            ->with('user.profile')
            ->where('organization_id', $organizationId)
            ->whereHas('user', function (Builder $query) use ($lastname, $firstname, $patronymic, $phone, $email) {
                $query->whereHas('profile', function (Builder $query) use ($lastname, $firstname, $patronymic, $phone, $email) {
                    $query
                        ->where('phone', $phone)
                        ->orWhere('email', $email)
                        ->orWhere(function (Builder $query) use ($lastname, $firstname, $patronymic) {
                            $query
                                ->where('lastname', $lastname)
                                ->where('firstname', $firstname)
                                ->where('patronymic', $patronymic);
                        });
                });
            })
            ->get();

        return $clientDuplicates->count() > 0 ? $clientDuplicates : null;
    }

    public function clientDuplicatedById(int $organizationId, int $userId)
    {
        $clientDuplicates = Client::query()
            ->whereHas('user', function (Builder $query) use ($userId) {
                $query
                    ->where('user_id', $userId)
                    ->with('user_profile');
            })
            ->get();

        if (!$clientDuplicates) {
            return null;
        };

        return $this::clientDuplicates($organizationId,
            $clientDuplicates->user->user_profile->lastname,
            $clientDuplicates->user->user_profile->fistname,
            $clientDuplicates->user->user_profile->patronymic,
            $clientDuplicates->user->user_profile->phone,
            $clientDuplicates->user->user_profile->email,
        );
    }


    public static function wardDuplicates (
        int $organizationId,
        string $lastname,
        string $firstname,
        string $patronymic,
        string $birthdate,
    ): ?Collection {
        $wardDuplicates = ClientWard::query()
            ->with('user.profile')
            ->whereHas('clients', function (Builder $query)use($organizationId) {
                $query->where('organization_id', $organizationId) ;
            })
            ->whereHas('user', function (Builder $query) use ($lastname, $firstname, $patronymic, $birthdate) {
                $query->whereHas('profile', function (Builder $query) use ($lastname, $firstname, $patronymic, $birthdate) {
                    $query
                        ->where('birthdate', $birthdate)
                        ->where(function (Builder $query) use ($lastname, $firstname, $patronymic) {
                            $query
                                ->where('lastname', $lastname)
                                ->where('firstname', $firstname)
                                ->where('patronymic', $patronymic);
                        });
                });
            })
            ->get();

        return $wardDuplicates->count() > 0 ? $wardDuplicates : null;
    }
}
