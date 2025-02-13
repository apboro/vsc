<?php
/** @var int|string $contract_number */
/** @var string $contract_date */
/** @var string $client_name */
/** @var string $client_phone */
/** @var string $ward_name */
/** @var string $ward_birth_date */
/** @var string $ward_document */
/** @var string $ward_document_date */
/** @var string $service_name */
/** @var int|string $trainings_per_week */
/** @var int|string $trainings_per_month */
/** @var int|string $training_duration */
/** @var string $sport_kind */
/** @var string $schedule */
/** @var string $training_base_address */
/** @var string $service_start_date */
/** @var string $service_end_date */
/** @var string $monthly_price */
/** @var string $monthly_price_string */
/** @var string $training_return_price */
/** @var string $training_return_price_string */
/** @var string $discount_1_monthly_price */
/** @var string $discount_1_monthly_price_string */
/** @var string $discount_2_monthly_price */
/** @var string $discount_2_monthly_price_string */
/** @var string $discount_3_monthly_price */
/** @var string $discount_3_monthly_price_string */
/** @var string $client_email */
/** @var string $client_address */
/** @var string $client_compact_name */
/** @var string $client_birth_date */
/** @var string $client_passport_serial */
/** @var string $client_passport_number */
/** @var string $client_passport_date */
/** @var string $client_passport_place */
/** @var string $client_passport_code */
/** @var string $organization_title */
/** @var string $organization_inn */
/** @var string $organization_kpp */
/** @var string $bank_account */
/** @var string $bank_title */
/** @var string $bank_bik */
/** @var string $bank_ks */
/** @var string $training_base_email */
/** @var string $training_base_phone */
/** @var string $training_base_homepage */
/** @var string $organization_phone */
/** @var string $organization_email */
/** @var string $organization_homepage */

/** @var bool $signed */

?>
    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>Договор</title>
    <style>
        html, body {
            font-family: DejaVu Sans;
        }

        h1 {
            font-size: 16px;
        }

        h2 {
            font-size: 14px;
        }

        h3 {
            font-size: 12px;
        }

        p {
            font-size: 12px;
            margin: 1pt 0;
        }
    </style>
</head>
<body>
<h3 style="text-align: center">Договор</h3>
<h3 style="text-align: center">оказания возмездных услуг №{{ $contract_number }}</h3>

<div style="page-break-inside: avoid">
    <div style="display: inline-block; vertical-align: top; width: 49%;">
        <p style="text-align: left">г. Кудрово</p>
    </div>
    <div style="display: inline-block; vertical-align: top; width: 49%">
        <p style="text-align: right">{{ $contract_date }}</p>
    </div>
</div>

<p style="margin-top: 10pt">Общественная организация «Центр школьного спорта Всеволожского района»,
    (далее Исполнитель), в лице Председателя Совета Алешина Максима Александровича, действующего на основании Устава
    и гр. <b>{{ $client_name }}</b>, (далее Заказчик) являющийся(аяся) законным представителем: <b>{{ $ward_name }}</b> <b>{{ $ward_birth_date }}</b>  года рождения (далее Ребенок),
    с другой стороны, заключили настоящий Договор о нижеследующем:</p>

<br/>
<p><b>1. Предмет договора</b></p>
<p>1.1. Предметом настоящего Договора является оказание Исполнителем Заказчику физкультурно – оздоровительных
    услуг по физической подготовке и физическому развитию (далее – услуги), а именно:
</p>
<p>- <b>{{ $service_name }}</b> с {{ $service_start_date }} по {{ $service_end_date }}</p>
<p>Проживание в гостинице «<b>{{ $training_base_name }}</b>», расположенной по адресу: <b>{{ $training_base_address }}</b></p>
<p>Тренировки по игровым видам спорта <b>{{ $sport_kind }}</b> 1 час в день, а также тренировки в бассейне 2 часа в день в доме спорта
    «Юность» расположенного по адресу: Ленинградская область, г. Волхов, Волховский пр., д. 26;</p>
<p>Тренировки на ОФП 1 час в день на территории стадиона «Металлург» расположенного по адресу: Ленинградская область, г. Волхов, Волховский пр., д. 16</p>
<p>– участие Ребенка на период сборов в спортивных мероприятиях среди коллективов физической культуры и спортивных клубов.
</p>
<br/>
<p><b>2. Обязательства сторон</b></p>
<p><b>2.1. Исполнитель обязуется: </b></p>
<p>2.1.1. Обеспечить проживание Ребенка на период проведения. тренировочных сборов с <b>{{ $service_start_date }}</b> по <b>{{ $service_end_date }}</b> в гостинице «<b>{{ $training_base_name }}</b>», расположенной по адресу: <b>{{ $training_base_address }}</b> </p>
<p>2.1.2. Организовать доставку Ребенка до территории гостиницы «<b>{{ $training_base_name }}</b>»</p>
<p>2.1.3. Обеспечить проведение тренировок по игровым видам спорта <b>{{ $sport_kind }}</b> 1 час в день, а также тренировок
    в бассейне 2 часа в день  в доме спорта «Юность» расположенного по адресу: Ленинградская область, г. Волхов, Волховский пр., д. 26;</p>
<p>Обеспечить проведение тренировок на ОФП 1 час в день на территории стадиона «Металлург» расположенного по адресу: Ленинградская область, г. Волхов, Волховский пр., д. 16.</p>
<p>2.1.4. Обеспечить охрану здоровья и безопасность пребывания Ребенка на тренировочных сборах в период с <b>{{ $service_start_date }}</b> по <b>{{ $service_end_date }}</b></p>
<p>2.1.5. Организовать трехразовое питание Ребенка в период его нахождения в гостинице «<b>{{ $training_base_name }}</b>».</p>
<p>2.1.6. Организовать участие Ребенка в спортивных мероприятиях среди коллективов физической культуры и спортивных клубов;</p>
<p>2.1.7. В случае необходимости оказать Ребенку первую медицинскую помощь и обеспечить его доставку в лечебное учреждение (по согласованию с Заказчиком);</p>
<p>2.1.8. Уведомлять Заказчика о ненадлежащем поведении Ребенка, нарушении им этических норм и норм общественного порядка;</p>

<br/>
<p><b>2.2. Заказчик обязуется</b></p>
<p>2.2.1. Оплатить представленные Исполнителем услуги на условиях Договора.</p>
<p>2.2.2. Обеспечить Ребенка необходимыми личными вещами и предметами личной гигиены для пребывания в гостинице «<b>{{ $training_base_name }}</b>». </p>
<p>2.2.3. Довести до сведения Исполнителя факт наличия у Ребенка существенных медицинских противопоказаний или хронических заболеваний, которые могут негативно отразиться на здоровье Ребенка во время проведения тренировочных сборов.
</p>
<p>2.2.4. За неделю до выезда (начала тренировочных сборов) предоставить Исполнителю:</p>
<p>- копию Свидетельства о рождении или копию паспорта Ребенка;</p>
<p>- справку об отсутствии хронических и инфекционных заболеваний по месту жительства Ребенка;</p>
<p>- медицинскую справку из школы для Ребенка, отъезжающего на тренировочные сборы;</p>
<p>- результаты анализов я/г и энтеробиоз;</p>
<p>- копии медицинского полиса, сертификата прививок;</p>
<p>2.2.5. Проинформировать Ребенка о требованиях, предъявляемых к нему в период исполнения настоящего договора и последствиях их нарушения.</p>
<p>2.2.6. Произвести оплату услуг Исполнителя в порядке, установленном настоящим договором.</p>
<p>2.2.7. В случае причинения ущерба имуществу Исполнителя, других детей, тренеров   и третьих лиц в результате нарушения Ребенком установленных настоящим договором требований возместить стоимость нанесенного Ребенком ущерба в течении 30 календарных дней со дня получения соответствующей претензии.</p>
<p>2.2.8. На основании уведомления Исполнителя забрать Ребенка с территории места проведения тренировочных сборов в случаях:</p>
<p>- грубого нарушения им мер собственной безопасности и правил внутреннего распорядка, включая самовольный уход с территории места проведения тренировочных сборов, спортивных мероприятий и иных мест, где Ребенок должен находиться в период исполнения Исполнителем настоящего договора;</p>
<p>- грубого нарушения распорядка дня, дисциплины, норм поведения в общественных  местах;</p>
<p>- совершения любого рода противоправных действий;</p>
<p>- нанесения морального или физического ущерба другим детям;</p>
<p>- употребления спиртных напитков, наркотиков, курения;</p>
<p>- нанесения материального ущерба или умышленной порчи имуществу Исполнителя, участников спортивных мероприятий, сотрудников Исполнителя или третьих лиц;</p>
<p>- выявления у Ребенка скрытых Заказчиком при заключении настоящего Договора и предоставлении медицинских справок существенных медицинских противопоказаний или хронических заболеваний, в том числе вновь выявленных в период проведения тренировочных сборов, которые могут негативно отразиться на здоровье Ребенка во время отдыха.</p>
<p>2.2.9 Разъяснить Ребенку об обязанности:</p>
<p>- принимать участие в спортивных мероприятиях среди коллективов физической культуры и спортивных клубов;  </p>
<p>- выполнять распорядок дня, установленный на каждый день;</p>
<p>- не покидать территорию проведения тренировочных сборов;</p>
<p>- соблюдать меры собственной безопасности, пожарной безопасности; правила участия в спортивных мероприятиях среди коллективов физической культуры и спортивных клубов;</p>
<p>- постоянно находиться в составе своей группы;</p>
<p>- принимать участие в самообслуживающем труде;</p>
<p>- содержать в порядке личные вещи;</p>
<p>- выполнять санитарно-гигиенические требования; следить за своим внешним видом, одеждой;</p>
<p>- бережно относиться к имуществу Исполнителя, участников спортивных мероприятий, сотрудников Исполнителя или третьих лиц;</p>
<p>- в случае недомогания или получения травмы немедленно известить сотрудников Исполнителя;</p>
<p>- не нарушать общественный порядок, вести себя социально ответственно, с уважением и тактом относиться к иным спортсменам, участникам тренировочных сборов и спортивных мероприятий;</p>
<p>- с уважением относиться к сотрудникам Исполнителя, выполнять их указания.</p>
<p>- не совершать действий, наносящих вред своему здоровью и здоровью окружающих.</p>
<p>- бережно относиться к природе.</p>

<br/>
<p><b>3. Права сторон</b></p>
<p><b>3.1. Исполнитель имеет право:</b></p>
<p>3.1.1. Досрочно прекратить исполнение услуг по настоящему Договору и известить Заказчика о необходимости забрать Ребенка с территории проведения тренировочных сборов в случае нарушения им условий, предусмотренных п. 2.2.8. и п. 2.2.9. настоящего Договора. Возврат денежных средств, уплаченных Заказчиком по настоящему договору, в случае расторжения договора по основаниям, предусмотренным п. 2.2.8. и п. 2.2.9., Исполнителем не производится.</p>
<p>3.1.2. Привлекать для оказания Услуг третьих лиц — специалистов, обладающих соответствующими знаниями, навыками, опытом и квалификацией, отвечающих за жизнь и здоровье Ребенка в установленном законом порядке.</p>
<p>3.1.3. Осуществлять фото и видеосъемку Ребенка в ходе выполнения услуг, по своему усмотрению распоряжаться полученными фото и видеоматериалами, в том числе публиковать их в СМИ и сети Интернет, а также использовать их в рекламных целях.</p>
<p><b>3.2. Заказчик имеет право:</b></p>
<p>3.2.1. Забрать Ребенка ранее срока, установленного настоящим Договором по письменному заявлению.</p>
<p>3.2.2. Высказывать свои пожелания представителю Исполнителя по поводу организации оказания услуг и предложения о совершенствовании деятельности.</p>
<p>3.2.3. Направлять в адрес Исполнителя индивидуальные рекомендации по работе с Ребенком (детьми).</p>

<br/>
<p><b>4. Стоимость и порядок расчетов</b></p>
<p>4.1. Стоимость настоящего договора составляет <b>{{$price}}</b> рублей 00 копеек, НДС не облагается. Моментом оплаты считается момент поступления денежных средств на расчетный счет Исполнителя.</p>
<p>4.2. Оплата производится в рублях путем перечисления денежных средств на расчётный счет Исполнителя, указанный в настоящем договоре.</p>
<p>4.3. Оплата по настоящему договору осуществляется в следующие сроки:</p>
<p>- авансовый платеж в размере <b>{{$advance_payment}}</b> рублей перечисляется Заказчиком на расчетный счет Исполнителя не позднее <b>{{$date_advance_payment}}</b></p>
<p>- оставшаяся сумма в размере <b>{{$price - $advance_payment}}</b> рублей перечисляется Заказчиком на расчетный счет Исполнителя не позднее <b>{{$date_deposit_funds}}</b>.</p>
<p>4.4. В случае расторжения договора по инициативе Заказчика до отбытия Ребенка до места проведения тренировочных сборов Исполнитель осуществляет возврат всей суммы уплаченных денежных средств Заказчику, за вычетом суммы внесенного аванса, установленного п. 4.3. настоящего договора. Стороны пришли к соглашению, что сумма аванса, уплаченного Заказчиком, является компенсацией расходов Исполнителя, понесенных Исполнителем, до момента расторжения договора в одностороннем порядке по инициативе Заказчика.</p>
<p>4.5. В случае расторжения договора по инициативе Заказчика после отбытия Ребенка к месту проведения тренировочных сборов ранее срока, установленного настоящим договором, Исполнитель компенсирует Заказчику стоимость договора из расчета <b>{{$refund_amount}}</b> рублей 00 копеек за каждый неотбытый Ребенком день.</p>
<p>4.6. Возврат денежных средств осуществляется в срок, не превышающий девяносто календарных дней.</p>

<br/>
<p><b>5. Персональные данные</b></p>
<p>5.1. Заказчик выражает своё согласие на осуществление Исполнителем обработки, сбора, систематизации, хранения, использования персональных данных Ребенка и Заказчика в соответствии с требованиями Федерального Закона от 27.07.2006г.№153-ФЗ «О персональных данных», а также дает согласие на фото и видеосъёмку Ребенка и возможное дальнейшее опубликование фотографий на официальной странице в сети интернет и на рекламных материалах.</p>

<br/>
<p><b>6. Прочие условия</b></p>
<p>6.1. При наступлении форс-мажорных обстоятельств, стороны не несут ответственность за неисполнение или ненадлежащее исполнение взятых на себя в рамках настоящего договора обязательств. В случае наступления для одной из сторон настоящего договора обстоятельств, препятствующих исполнению ей взятых на себя обязательств, такая сторона обязана в письменном виде, либо путем направления письма на электронную почту противоположной стороне известить ее о наступлении таких обстоятельств не позже одного дня с момента их наступления.</p>
<p>6.2. Возможные споры между сторонами по поводу исполнения взаимных обязательств по условиям настоящего договора, разрешаются путем переговоров. В случае недостижения согласия спор подлежит рассмотрению в судебном порядке в соответствии с действующим законодательством Российской Федерации.</p>
<p>6.3. Изменение условий настоящего договора осуществляется по письменному соглашению сторон. Дополнительное соглашение оформляется в простой письменной форме и является неотъемлемой частью настоящего договора.</p>
<p>6.4. Стороны признают надлежащим подписание договора, отчетов, актов, дополнительных соглашений путем обмена отсканированными копиями по электронной почте. Такие документы считаются подписанными простой электронной подписью и приравниваются к документам на бумажном носителе.</p>
<p>6.5. Обмен отсканированными копиями договора, отчетов, актов, дополнительных соглашений, извещений, уведомлений производятся по следующим адресам электронной почты: электронная почта Заказчика: <b>{{$client_email}}</b>, электронная почта Исполнителя: {{ $service_email }}</p>
<p>6.6. Стороны обязуются сохранять конфиденциальность сведений и условий, имеющихся в настоящем договоре и не передавать их третьим лицам.</p>
<p>6.7. Настоящий Договор вступает в силу с момента его подписания сторонами и действует до окончания срока, указанного в п.1.1.; составлен в двух экземплярах, имеющих одинаковую юридическую силу, по одному для каждой из сторон.</p>

<br/>
<p style="text-align: center"><b>7. Адреса и реквизиты сторон</b></p>
<div style="page-break-inside: avoid">
    <div style="display: inline-block; vertical-align: top; width: 49%">
        <h2 style="text-align: center"> «Заказчик»</h2>
        <p>{{ $client_name }}</p>
        <p>Паспорт: {{ $client_passport_serial }} {{ $client_passport_number }}, выдан {{ $client_passport_date }}{{ $client_passport_place }}, код
            подразделения {{ $client_passport_code }}</p>
        <p>Фактический адрес: {{ $client_address }}</p>
        <p>Телефон: {{ $client_phone }}</p>
        <p>Email: {{ $client_email }}</p>
        <p>Подпись</p>
        <p style="margin-top: 15pt">___________________________________/{{ $client_compact_name }}/</p>
    </div>

    <div style="display: inline-block; vertical-align: top; width: 49%;">
        <h2 style="text-align: center">«Исполнитель»</h2>
        <p>Общественная организация «Центр школьного спорта Всеволожского района»</p>

        <p>Наименование: {{ $organization_title }}</p>
        <p>ИНН: {{ $organization_inn }}</p>
        <p>КПП: {{ $organization_kpp }}</p>
        <p>р/с №{{ $bank_account }}</p>
        <p>{{ $bank_title }}</p>
        <p>БИК: {{ $bank_bik }}</p>
        <p>Корр. счет: {{ $bank_ks }}</p>
        <p>Почтовый ящик: {{ $organization_email }}</p>
        <p>Страница в сети интернет: https://vsev-sportcenter.ru</p>
        <p>Председатель Совета</p>
        <p>Подпись</p>
        <div style="position: relative;">
            <p style="margin-top: 15pt; opacity: 0.99; z-index: 10;">___________________________________/М.А.Алёшин/</p>
            @if(!empty($signed))
                <div style="position: absolute; left: 40pt; top: -18pt; width: 100pt; height: 50pt; z-index: 5;">
                    <img style="width: 100%; height: 100%;" src="data:image/png;base64,<?php echo base64_encode(file_get_contents(resource_path('views/pdf/sign.png'))); ?>"
                         alt="stamp"/>
                </div>
                <div style="position: absolute; left: 100pt; top: -20pt; width: 120pt; height: 120pt; z-index: 5;">
                    <img style="width: 100%; height: 100%;" src="data:image/png;base64,<?php echo base64_encode(file_get_contents(resource_path('views/pdf/stamp.png'))); ?>"
                         alt="stamp"/>
                </div>
            @endisset
        </div>
    </div>
</div>


{{--<div style="page-break-inside: avoid; margin-top: 100pt">--}}
{{--    <div style="display: inline-block; vertical-align: top; width: 49%;border-top: 1px solid #000000;">--}}
{{--        <p style="text-align: center">Заказчик:</p>--}}
{{--    </div>--}}

{{--    <div style="display: inline-block; vertical-align: top; width: 49%;text-align: right;border-top: 1px solid #000000;">--}}
{{--        <div style="position: relative;">--}}
{{--            <p style="text-align: center">Исполнитель:</p>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}
{{--<div style="page-break-after: always"></div>--}}
</body>
</html>
