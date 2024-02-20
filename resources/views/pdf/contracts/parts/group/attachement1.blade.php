<div style="page-break-after: always"></div>
<p style="padding-left: 540px; padding-right: 0">Приложение №1</p>
<p style="padding-left: 540px; padding-right: 0">к Договору об оказании услуг №{{ $contract_number }}</p>
<p style="padding-left: 540px; padding-right: 0">от {{ $contract_date }}</p>
<p style="padding-left: 540px; padding-right: 0">образец оформления заявки</p>

<br>

<table style="width:100%">
  <thead>
  <tr><th colspan="5">Заявка на бронирование путевок / размещение / питание / пользование сооружениями</th></tr>
  </thead>
</table>
<div style="display:flex; width: 100%; flex-direction: row">
  <table style="width:50%;float: left; border-collapse: collapse;">
    <tbody>
    <tr>
      <td>Ответственный<br> менеджер:</td>
      <td colspan="2">{{ $responsible_manager }}</td>
    </tr>
    <tr>
      <td colspan="3"><b>Данные по группе</b></td>
    </tr>
    <tr>
      <td rowspan="2"><b>Количество гостей<br><br>До 10 лет</b></td>
      <td><b>муж.</b></td>
      <td><b>жен.</b></td>
    </tr>
    <tr>
      <td>{{ $girls_1_count }}</td>
      <td>{{ $boys_1_count }}</td>
    </tr>
    <tr>
      <td><b>от 10 до 17 лет</b></td>
      <td>{{ $girls_2_count }}</td>
      <td>{{ $boys_2_count }}</td>
    </tr>
    <tr>
      <td><b>18 лет и старше</b></td>
      <td>{{ $girls_3_count }}</td>
      <td>{{ $boys_3_count }}</td>
    </tr>
    <tr>
      <td><b>Всего человек</b></td>
      <td colspan="2">{{ $ward_count }}</td>
    </tr>
    <tr>
      <td></td>
      <td colspan="2"></td>
    </tr>
    <tr>
      <td><b>Вид спорта /<br>деятельности</b></td>
      <td colspan="2">{{ $sport_kind }}</td>
    </tr>
    <tr>
      <td></td>
      <td colspan="2"></td>
    </tr>
    <tr>
      <td><b>Наименование услуг</b></td>
      <td colspan="2">{{ $service_name }}</td>
    </tr>
    <tr>
      <td></td>
      <td colspan="2"></td>
    </tr>
    <tr>
      <td><b>Заезд (дата/время)</b></td>
      <td>{{ $service_start_date }}</td>
      <td>14:00</td>
    </tr>
    <tr>
      <td><b>Выезд (дата/время)</b></td>
      <td>{{ $service_end_date }}</td>
      <td>12:00</td>
    </tr>
    <tr>
      <td><b>Питание</b></td>
      <td colspan="2">4-х разовое</td>
    </tr>
    <tr>
      <td><b>Дополнительные требования</b></td>
      <td colspan="2">{{ $additional_conditions }}</td>
    </tr>
    </tbody>
  </table>
  <table style="width:50%;float: left; border-collapse: collapse;">
    <tbody>
    <tr>
      <td><b>ID</b></td>
      <td>
        {{ $is_legal ? $org_name : $client_name }} <br>/ {{ $service_start_date }} - {{ $service_end_date }}</td>
    </tr>
    <tr>
      <td colspan="2"><b>Контактное лицо от Заказчика</b></td>
    </tr>
    <tr>
      <td rowspan="2"><b>Ф.И.О.<br><br>тел.</b></td>
      <td>{{ $client_name }}</td>
    </tr>
    <tr>
      <td>{{ $client_phone }}</td>
    </tr>
    <tr>
      <td><b>e-mail</b></td>
      <td>{{ $client_email }}</td>
    </tr>
    </tbody>
  </table>
</div>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<p>Обязательное* приложение:</p><br>
<p>Расписание тренировок (прилагается в свободной форме).</p><br>
<p>*В случае участия в тренировках, организуемых Исполнителем, приложение Расписания не требуется.</p><br>
