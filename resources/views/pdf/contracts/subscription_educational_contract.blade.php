<?php
/** @var int|string $contract_number */
/** @var string $contract_date */
/** @var string $client_name */
/** @var string $client_phone */
/** @var string $ward_name */
/** @var string $ward_birth_date */
/** @var string $ward_document */
/** @var string $ward_document_date */
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

        p {
            font-size: 12px;
            margin: 1pt 0;
        }
    </style>
</head>
<body>
<h1 style="text-align: center">Договор №{{ $contract_number }}</h1>

<p style="text-align: right">г. Санкт-Петербург {{ $contract_date }}</p>

<p style="margin-top: 10pt">{{$header_of_contract}},  с одной стороны, и <b>{{ $client_name }}</b>, <b>{{ $client_phone }}</b>, дата рождения <b>{{ $client_birth_date }}</b>,
    паспорт <b>{{ $client_passport_serial }}</b> <b>{{ $client_passport_number }}</b>, выдан <b>{{ $client_passport_date }}</b> <b>{{ $client_passport_place }}</b>, код
    подразделения <b>{{ $client_passport_code }}</b>, именуемый в дальнейшем «Заказчик», с другой стороны, являющийся законным представителем несовершеннолетнего
    <b>{{ $ward_name }}</b>, дата рождения <b>{{ $ward_birth_date }}</b>, свидетельство о рождении <b>{{ $ward_document }}</b>, выдано <b>{{ $ward_document_date }}</b>, именуемого
    далее «Обучающийся», заключили настоящий договор (далее - Договор) о нижеследующем:</p>
<p>1. Предмет договора</p>
<p>1.1 Исполнитель организует образовательную услугу Обучающемуся в следующем объеме:</p>
<p>По программе: <b>{{ $sport_kind }}</b>; <b>{{ $trainings_per_week }}</b> раза в неделю, <b>{{ $trainings_per_month }}</b> занятий в месяц по
    <b>{{ $training_duration }}</b> минут по дням недели: <b>{{ $schedule }}</b> (дни недели и время занятий могут меняться в зависимости от конкретных обстоятельств или по
    договоренности между Сторонами), в соответствии с условиями настоящего Договора, а Заказчик обязуется принять и оплатить их.</p>
<p>1.2 Исполнитель организует образовательную услугу на базе помещений по адресу: <b>{{ $training_base_address }}</b> принадлежащих ему на праве пользования, и с привлечением
    специалистов, обладающих соответствующими знаниями, навыками, опытом и квалификацией.</p>
<p>2. Права и обязанности Исполнителя</p>
<p>2.1 Исполнитель обязуется:</p>
<p>2.1.1 Оказать Заказчику услуги, указанные в п.1.1 настоящего договора</p>
<p>2.1.2 Организовать и обеспечить работу образовательной программы, указанной в п.1.1 Договора, в течение учебного года с {{ $service_start_date }} по {{ $service_end_date }}.</p>
<p>2.1.3 Предупреждать Заказчика об изменении платы за занятия и других платежей не менее чем за две недели до даты вступления в силу новых тарифов путем размещения информации на
    официальной странице в сети интернет: {{ $training_base_homepage }}</p>
<p>2.2 Исполнитель вправе:</p>
<p>2.2.1 Устанавливать порядок оказания Услуг путем утверждения расписания занятий в соответствии с режимом работы учреждения, предоставившего помещение.</p>
<p>2.2.2 Привлекать для оказания Услуг третьих лиц — специалистов, обладающих соответствующими знаниями, навыками, опытом и квалификацией, отвечающих за жизнь и здоровье
    Обучающегося.</p>
<p>2.2.3 Передавать (уступать) свои права и обязанности (часть прав и/или обязанностей) по Договору третьему лицу при условии гарантированного соблюдения прав и интересов
    Заказчика, предоставленных последнему в соответствии с условиями настоящего Договора. Заказчик путем подписания настоящего Договора выражает безусловное согласие на передачу
    (уступку) Исполнителем своих прав и обязанностей (части прав и/или обязанностей) по Договору любому третьему лицу по своему единоличному усмотрению. Передача (уступка)
    Исполнителем третьему лицу прав и обязанностей (части прав и/или обязанностей) по Договору не является основанием для расторжения Договора в одностороннем порядке.</p>
<p>2.2.4 Отказать Заказчику в заключении Договора на новый срок по истечении действия настоящего Договора, либо отказаться от исполнения настоящего Договора, если Заказчик допускал
    нарушения, предусмотренные гражданским законодательством и настоящим Договором.</p>
<p>3. Права и обязанности Заказчика</p>
<p>3.1 Заказчик обязуется</p>
<p>3.1.1 Обеспечить неукоснительное соблюдение Обучающимся требований по технике безопасности и порядка проведения занятий и тренировок, всех санитарно-эпидемиологических правил
    Главного санитарного врача Российской Федерации в условиях сохранения рисков распространения COVID -19</p>
<p>3.1.2 Своевременно вносить плату за оказание услуг Исполнителем.</p>
<p>3.1.3 Нести имущественную (материальную) ответственность за нанесение Обучающимся материального ущерба Исполнителю.</p>
<p>3.1.4 Прибыть на занятия не ранее, чем за 10 минут до начала занятий, передать ребенка педагогу, и покинуть территорию Исполнителя.</p>
<p>3.1.5 После окончания занятий забрать Обучающегося и в течение 10 минут покинуть территорию Исполнителя. При опоздании Заказчика и невозможности забрать Обучающегося вовремя,
    Исполнитель не несёт ответственность за жизнь и здоровье Обучающегося в этот период.</p>
<p>3.2. Заказчик вправе</p>
<p>3.2.1 Требовать от Исполнителя предоставления качественных услуг в соответствии с условиями настоящего Договора.</p>
<p>3.2.2 Получать необходимую и достоверную информацию об оказываемых исполнителем Услугах.</p>
<p>3.2.3 Направлять Исполнителю свои отзывы, предложения и рекомендации по любому виду услуг.</p>
<p>4. Стоимость услуг и порядок взаиморасчетов.</p>
<p>4.1 Стоимость услуг Исполнителя по настоящему договору, составляет <b>{{ $monthly_price }}</b> <b>({{ $monthly_price_string }})</b> НДС не облагается за 1 месяц занятий. При
    этом стоимость разового занятия, используемая при перерасчетах по болезни, принимается равной <b>{{ $training_return_price }}</b> <b>({{ $training_return_price_string }})</b>
    без НДС. Правила перерасчета по болезни указаны в п 4.8 настоящего договора.</p>
<p>4.2 Оплата за образовательную услугу производится 100% предоплатой до 7-го числа текущего месяца.</p>
<p>4.3 Дополнительные мероприятия и соревнования, проводимые в день занятий и тренировок, оплачиваются отдельно согласно прейскуранту. Занятия и тренировки в этот день перерасчету
    не подлежат.</p>
<p>4.4 В случае невнесения оплаты до 7-го числа текущего месяца, Обучающийся на занятия не допускается до полной оплаты занятий за месяц, с учетом дней, когда Обучающийся не был
    допущен на занятия в связи с неоплатой.</p>
<p>4.5 В случае отмены занятия по вине Исполнителя, оно переносится на другое время.</p>
<p>4.6 Занятия, пропущенные по причинам, не зависящим от Исполнителя, не возмещаются и подлежат своевременной оплате.</p>
<p>4.7 Занятия, выпавшие на официальные выходные праздничные дни, в случае их отмены не возмещаются и подлежат своевременной оплате.</p>
<p>4.8 В случае пропуска занятий по причине болезни Обучающегося, при предоставлении медицинской справки, производится перерасчёт оплаты за текущий или следующий месяц. Справка
    предоставляется не позднее 7 календарных дней после даты выписки. При этом стоимость фактически посещенных занятий рассчитывается исходя из стоимости разового занятия,
    указанного в п. 4.1. Фотография справки направляется на нашу почту <b>{{ $training_base_email }}</b>. Телефон Вашего менеджера <b>{{ $training_base_phone }}</b></p>
<p>4.9 В случае невнесения предоплаты за текущий месяц и отсутствия Обучающегося на занятиях три недели, место за ним не сохраняется, настоящий Договор расторгается в одностороннем
    порядке со стороны Исполнителя. Новый договор заключается при наличии свободных мест.</p>
<p>4.10 В соответствии с п. 2 ст. 310, п. 2 ст. 424 ГК РФ, в течение срока действия настоящего Договора размер оплаты занятий может меняться в связи с изменением условий занятий,
    инфляционными процессами и индикативными показателями. Об изменении размеров платежей Заказчик предупреждается не менее, чем за две недели до даты их введения.</p>
<p>4.11 В случае, когда Обучающийся является членом многодетной семьи либо иной льготной категории граждан, Заказчик имеет право обратиться с заявлением на уменьшение стоимости услуг на 10% от стоимости,
    указанной в пункте 4.1. В этом случае стоимость составит <b>{{ $discount_1_monthly_price }}</b> <b>({{ $discount_1_monthly_price_string }})</b>. Скидка подтверждается справкой
    о многодетности. Фотография справки
    направляется на нашу почту {{ $training_base_email }}.</p>
<p>Скидка предоставляется только при 100% оплате абонемента, при перерасчете и оплате разовых тренировок скидка не предоставляется.</p>
<p>4.12 В случае, когда два и более Обучающихся одного Заказчика получают образовательную услугу, осуществляемую Исполнителем, Заказчик имеет право обратиться с заявлением на уменьшение стоимости услуг
    на 10% от стоимости указанной в пункте 4.1. Скидка
    действует только в тот момент, когда два и более Обучающихся занимаются одновременно. Скидка предоставляется только при 100% оплате абонемента, при перерасчете и оплате разовых тренировок скидка не предоставляется.</p>
<p>4.13 Скидки, указанные в пункте 4.11 и 4.12 не суммируются. Исполнитель оставляет за собой право на предоставление скидки либо на отказ в ее предоставлении, право
    изменять размер скидки на свое усмотрение.</p>
<p>5. Порядок заключения договора</p>
<p>5.1 Стороны признают надлежащим подписание договора, отчетов, актов, дополнительных соглашений путем обмена отсканированными копиями по электронной почте. Такие документы
    считаются подписанными простой электронной подписью и приравниваются к документам на бумажном носителе</p>
<p>5.2 Обмен отсканированными копиями договора, отчетов, актов, дополнительных соглашений производятся по следующим адресам электронной почты: электронная почта
    Заказчика: <b>{{ $client_email }}</b>, электронная почта Исполнителя: <b>{{ $organization_email }}</b></p>
<p>5.3 Стороны обязуются сохранять конфиденциальность доступов к электронной почте и не передавать их третьим лицам.</p>
<p>6. Порядок расторжения договора</p>
<p>6.1 Настоящий договор может быть расторгнут до окончания срока действия:</p>
<p>6.1.1 По взаимному соглашению сторон.</p>
<p>6.1.2 Заказчиком по желанию, выраженному в письменном заявлении. При этом производится возврат стоимости неиспользованных занятий, начиная с момента подачи заявления и до даты
    окончания оплаченного срока занятий.</p>
<p>6.1.3 По инициативе Исполнителя, если Заказчик или Обучающийся допускали нарушения, предусмотренные гражданским законодательством и настоящим Договором, договор расторгается в
    одностороннем порядке. При этом осуществляется возврат суммы оплаты по Договору за один месяц, за вычетом фактически отработанного Исполнителем времени.</p>
<p>7. Персональные данные</p>
<p>7.1 Заказчик выражает своё согласие на осуществление Исполнителем обработки, сбора, систематизации, хранения, использования персональных данных Обучающегося и Заказчика в
    соответствии с требованиями Федерального Закона от 27.07.2006г.№153-ФЗ «О персональных данных», а также согласие на фото и видеосъёмку Обучающегося и возможное дальнейшее
    опубликование фотографий на официальной странице в сети интернет и на рекламных материалах.</p>
<p>8. Форс-Мажор</p>
<p>8.1 В случае непредвиденных обстоятельств у собственника помещения или у администрации школы, не позволяющих проведения занятий и тренировок, Исполнитель обязуется выполнить
    условия договора, путем перенесения тренировок на другое время при наличии свободного времени на используемых площадях.</p>
<p>9. Заключительные положения</p>
<p>9.1 Споры по настоящему Договору разрешаются путем переговоров сторон или же в соответствии с действующим законодательством РФ.</p>
<p>9.2 Исполнитель не несет ответственности за вещи и имущество Заказчика.</p>
<p>9.3 Договор вступает в законную силу с момента его подписания сторонами и действует по «31» мая 2024 года, либо после получения электронной формы договора на электронную почту
    Заказчика.</p>
<p>9.4 Договор существует в 2 (двух) экземплярах, имеющих равную юридическую силу (один экземпляр для каждой стороны).</p>
<p>9.5 Любые изменения и дополнения к настоящему договору действительны только в письменной форме и подписанные Сторонами.</p>
<p>9.6 Приложение № 1 к настоящему договору являются неотъемлемой его частью.</p>
<p>9. Реквизиты и подписи сторон.</p>
<div style="page-break-inside: avoid">
    <div style="display: inline-block; vertical-align: top; width: 49%;">
        <h2 style="text-align: center">Исполнитель</h2>

        <p>Наименование: {{ $organization_title }}</p>
        <p>ИНН: {{ $organization_inn }}</p>
        @if(!empty($organization_kpp))
        <p>КПП: {{ $organization_kpp }}</p>
        @endif
        <p>р/с №{{ $bank_account }}</p>
        <p>{{ $bank_title }}</p>
        <p>БИК: {{ $bank_bik }}</p>
        <p>ОГРН: {{$organization_ogrn}}</p>
        <p>Корр. счет: {{ $bank_ks }}</p>
        <p>Юр. адрес: {{ $legal_address }}</p>
        @if(!empty($organization_email))
        <p>Почтовый ящик: {{ $organization_email }}</p>
        @endif
        @if(!empty($organization_phone))
            <p>Телефон: {{ $organization_phone }}</p>
        @endif
        <p>Страница в сети интернет: {{$organization_homepage}}</p>
        <p>Подпись</p>
        <div style="position: relative;">
            <p style="margin-top: 15pt; opacity: 0.99; z-index: 10;">___________________________________/{{$sign}}/</p>
            @if(!empty($signed))
                @if($sign=='Ткаченко В.В.')
                    <div style="position: absolute; left: 40pt; top: -18pt; width: 100pt; height: 50pt; z-index: 5;">
                        <img style="width: 100%; height: 100%;" src="data:image/png;base64,<?php echo base64_encode(file_get_contents(resource_path('views/pdf/tkachenko_sign.png'))); ?>"
                             alt="stamp"/>
                    </div>
                    <div style="position: absolute; left: 100pt; top: -20pt; width: 120pt; height: 120pt; z-index: 5;">
                        <img style="width: 100%; height: 100%;" src="data:image/png;base64,<?php echo base64_encode(file_get_contents(resource_path('views/pdf/tkachenko_stamp.png'))); ?>"
                             alt="stamp"/>
                    </div>
                @else
                    @if($sign=='Бабаевский Г.А.')
                        <div style="position: absolute; left: 40pt; top: -18pt; width: 100pt; height: 50pt; z-index: 5;">
                            <img style="width: 100%; height: 100%;" src="data:image/png;base64,<?php echo base64_encode(file_get_contents(resource_path('views/pdf/babaevkiy_sign.png'))); ?>"
                                 alt="stamp"/>
                        </div>
                    @else
                        <div style="position: absolute; left: 40pt; top: -18pt; width: 100pt; height: 50pt; z-index: 5;">
                            <img style="width: 100%; height: 100%;" src="data:image/png;base64,<?php echo base64_encode(file_get_contents(resource_path('views/pdf/sign.png'))); ?>"
                                 alt="stamp"/>
                        </div>
                        <div style="position: absolute; left: 100pt; top: -20pt; width: 120pt; height: 120pt; z-index: 5;">
                            <img style="width: 100%; height: 100%;" src="data:image/png;base64,<?php echo base64_encode(file_get_contents(resource_path('views/pdf/stamp.png'))); ?>"
                                 alt="stamp"/>
                        </div>
                    @endif
                @endif
            @endisset
        </div>
    </div>
    <div style="display: inline-block; vertical-align: top; width: 49%">
        <h2 style="text-align: center">Заказчик</h2>
        <p>{{ $client_name }}</p>
        <p>Паспорт: {{ $client_passport_serial }} {{ $client_passport_number }}, выдан {{ $client_passport_date }}{{ $client_passport_place }}, код
            подразделения {{ $client_passport_code }}</p>
        <p>Фактический адрес: {{ $client_address }}</p>
        <p>Телефон: {{ $client_phone }}</p>
        <p>Email: {{ $client_email }}</p>
        <p>Подпись</p>
        <p style="margin-top: 15pt">___________________________________/{{ $client_compact_name }}/</p>
    </div>
</div>
<div style="page-break-after: always"></div>
<h1>Приложение №1 к Договору №{{ $contract_number }}</h1>
<p style="text-align: right">от {{ $contract_date }}</p>
<h2>Правила посещения.</h2>
<p>1.1 Преподаватель отвечает за жизнь и здоровье Обучающегося только во время проведения занятий. До начала образовательного процесса и после окончания занятий за его жизнь, здоровье и
    поведение отвечает Заказчик.</p>
<p>1.2 Все вопросы с преподавателем решаются во время перерывов, ни в коем случае не во время занятий.</p>
<p>1.3 Исполнитель и преподаватель вправе удалить с занятия в случае невыполнения указаний преподавателя, или неуважительного отношения к
    учащимся на занятии. Это является основанием для расторжения договора в одностороннем порядке.</p>
<p>1.4 Запрещается использование учащимися и их родителями фото- и видеосъёмки занятий без разрешения тренера (учителя, преподавателя).</p>
<p>1.5 В школе и на прилегающих территориях запрещено нахождение в состоянии алкогольного или наркотического опьянения, данный факт доводится до сведения администрации школы.</p>
<p>1.6 В целях безопасности других Обучающихся, преподавателя и сотрудников, исполнитель вправе отказать в оказании услуг при признаках инфекционных, кожных и иных заболеваниях. Допуск
    на занятия производится после полного выздоровления при наличии справки от врача, разрешающей занятие.</p>
<p>1.7 Скан или фотография медицинской справки после болезни направляются на наш почтовый ящик <b>{{ $training_base_email }}</b>. В теме письма пишется «ФИО Обучающегося справка»,
    а в теле письма комментарий Заказчика. Письма обрабатываются в течение трех рабочих дней.</p>

<p style="margin-top: 15pt">С настоящими правилами ознакомлен {{ $contract_date }}</p>
<p style="margin-top: 15pt">Заказчик ____________________/{{ $client_name }}/</p>
</body>
</html>
