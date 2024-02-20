<div style="page-break-after: always"></div>
<p style="padding-left: 540px; padding-right: 0">Приложение №2</p>
<p style="padding-left: 540px; padding-right: 0">к Договору об оказании услуг №{{ $contract_number }}</p>
<p style="padding-left: 540px; padding-right: 0">от {{ $contract_date }}</p>
<p style="padding-left: 540px; padding-right: 0">Образец Ценового листа-спецификации</p>


<h1 style="text-align: center"><b>ЦЕНОВОЙ ЛИСТ – СПЕЦИФИКАЦИЯ</b></h1>
<p style="text-align: center"><i>на услугу, оформленную Путевкой</i></p>
<p style="text-align: center"><i>с {{ $service_start_date }}. по {{ $service_end_date }}</i></p>

<br>

<table style="width:100%">
<tbody>
<tr>
  <td>№ п/п</td>
  <td>Наименование услуги</td>
  <td>Кол-во<br> человек</td>
  <td>Кол-во суток</td>
  <td>Стоимость 1-го койко/дня<br> (в рублях), без НДС</td>
  <td>Общая сумма (в рублях),<br> без НДС, согласно п.п. 18 п. 3 ст. 149<br> Налогового кодекса РФ</td>
</tr>
<tr>
  <td>1</td>
  <td>{{ $service_name }}</td>
  <td>{{ $ward_count }}</td>
  <td>14</td>
  <td>{{ $service_daily_price }}</td>
  <td>{{ $group_price }}</td>
</tr>
<tr>
  <td colspan="5">Дополнительные требования</td>
  <td>{{ $additional_price }}</td>
</tr>
<tr>
  <td colspan="5">Всего</td>
  <td>{{ $total_price }}</td>
</tr>
</tbody>
</table>

<br>

<p>{{ $service_description }}</p>


<hr>
<p>Настоящий Ценовой лист является подтверждением бронирования комплекса услуг, оформленных Путевкой.<br></p>
<p>Стоимость Путевки рассчитана только для условий, указанных в Заявке (количество дней, условия проживания и т.д.), при изменении условий стоимость Путевки может быть изменена Исполнителем в одностороннем порядке.</p>
