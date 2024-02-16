<style>
    .button {
        display: inline-block;
        text-decoration: none;
        height: 35px;
        line-height: 35px;
        text-align: center;
        cursor: pointer;
        border-radius: 2px;
        box-sizing: border-box;
        padding: 0 17.5px;
        letter-spacing: 0.03rem;
        transition: background-color cubic-bezier(0.24, 0.19, 0.28, 1.29) 150ms, border-color cubic-bezier(0.24, 0.19, 0.28, 1.29) 150ms, box-shadow cubic-bezier(0.24, 0.19, 0.28, 1.29) 150ms;
        font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Helvetica Neue, Arial, Noto Sans, sans-serif, Apple Color Emoji, Segoe UI Emoji, Segoe UI Symbol, Noto Color Emoji;
        font-size: 14px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
        white-space: nowrap;
        user-select: none;
        -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
        background-color: #0B68C2;
        border-color: #0B68C2;
        color: #ffffff;
    }
    .button:hover{
        background-color: #479eeb;
        border-color: #479eeb;
    }
</style>
<div style="display: flex; flex-direction: column; justify-content: center; align-items: center; height: 100vh;">
    @php /** @var \App\Models\Invoices\Invoice $invoice */ @endphp

    <p>Оплата услуги: {{$invoice->contract->subscription->service->title}}</p>
    <p>Дата начала: {{$invoice->contract->contractData->service_start_date->translatedFormat('D, d M Y')}}</p>
    <p>Дата окончания: {{$invoice->contract->contractData->service_end_date->translatedFormat('D, d M Y')}}</p>
    <p>Ученик: {{$invoice->contract->subscription->clientWard->user->profile->getFullName()}}</p>
    <p>Тренировочная база: {{$invoice->contract->subscription->service->trainingBase->title}}</p>
    <p>Сумма: {{$invoice->contract->contractData->price ?? $invoice->contract->contractData->monthly_price}} руб.</p>

    <form method="POST" action="{{ route('invoice.payment.make', ['hash' => $invoice->hash])}}">
        @csrf
        <button type="submit" class="button">Оплатить</button>
    </form>
</div>

