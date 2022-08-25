<?php
/** @var int|string $contract_number */
/** @var string $contract_date */
/** @var string $client_name */
/** @var string $client_phone */
/** @var string $ward_name */
/** @var string $ward_birth_date */
/** @var int|string $trainings_per_week */
/** @var int|string $trainings_per_month */
/** @var int|string $training_duration */
/** @var string $sport_kind */
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

<p>Общественная организация «Центр школьного спорта Всеволожского района», именуемая в дальнейшем «Исполнитель», в лице Председателя Совета Алёшин Максим Александрович,
    действующего на основании Устава, с одной стороны, и {{ $client_name }},</p>
<p>(ТЕЛЕФОН ПРЕДСТАВИТЕЛЯ НЕСОВЕРШЕННОЛЕТНЕГО) {{ $client_phone }}</p>
<p>именуемый в дальнейшем «Заказчик», с другой стороны, являющийся законным представителем
    несовершеннолетнего {{ $ward_name }}, {{ $ward_birth_date }} (Ф.И.О. , дата рождения), именуемого далее «Воспитанник», заключили настоящий договор (далее - Договор) о
    нижеследующем:</p>
<p>1. Предмет договора</p>
<p>1.1 Исполнитель организует тренировочный процесс для Воспитанника {{ $trainings_per_week }} раза в неделю {{ $trainings_per_month }} занятий в месяц по {{ $training_duration }}
    минут по спортивному направлению: {{ $sport_kind }} в соответствии с условиями настоящего Договора, а Заказчик обязуется принять и оплатить их.</p>
<p>1.2 Исполнитель организует тренировочный процесс на базе помещений по адресу: {{ $training_base_address }} принадлежащих ему на праве пользования, и с привлечением специалистов,
    обладающих соответствующими знаниями, навыками, опытом и квалификацией.</p>
<p>2. Права и обязанности Исполнителя</p>
<p>2.1 Исполнитель обязуется:</p>
<p>2.1.1 Оказать Заказчику услуги, указанные в п.1.1 настоящего договора</p>
<p>2.1.2 Организовать и обеспечить работу направления, указанного в п.1.1 Договора, в течение учебного года с {{ $service_start_date }} по {{ $service_end_date }}.</p>
<p>2.1.3 Предупреждать Заказчика об изменении платы за занятия и других платежей не менее чем за две недели до даты вступления в силу новых тарифов путем размещения информации на
    официальной странице в сети интернет: https://vk.com/cskudrovo</p>
<p>2.2 Исполнитель вправе:</p>
<p>2.2.1 Устанавливать порядок оказания Услуг путем утверждения расписания занятий и тренировок в соответствии с режимом работы учреждения, предоставившего помещение.</p>
<p>2.2.2 Привлекать для оказания Услуг третьих лиц — специалистов, обладающих соответствующими знаниями, навыками, опытом и квалификацией, отвечающих за жизнь и здоровье
    Воспитанника.</p>
<p>2.2.3 Передавать (уступать) свои права и обязанности (часть прав и/или обязанностей) по Договору третьему лицу при условии гарантированного соблюдения прав и интересов
    Заказчика, предоставленных последнему в соответствии с условиями настоящего Договора. Заказчик путем подписания настоящего Договора выражает безусловное согласие на передачу
    (уступку) Исполнителем своих прав и обязанностей (части прав и/или обязанностей) по Договору любому третьему лицу по своему единоличному усмотрению. Передача (уступка)
    Исполнителем третьему лицу прав и обязанностей (части прав и/или обязанностей) по Договору не является основанием для расторжения Договора в одностороннем порядке.</p>
<p>2.2.4 Отказать Заказчику в заключении Договора на новый срок по истечении действия настоящего Договора, либо отказаться от исполнения настоящего Договора, если Заказчик допускал
    нарушения, предусмотренные гражданским законодательством и настоящим Договором.</p>
<p>3. Права и обязанности Заказчика</p>
<p>3.1 Заказчик обязуется</p>
<p>3.1.1 Обеспечить неукоснительное соблюдение Воспитанником требований по технике безопасности и порядка проведения занятий и тренировок, всех санитарно-эпидемиологических правил
    Главного санитарного врача Российской Федерации в условиях сохранения рисков распространения COVID -19</p>
<p>3.1.2 До начала тренировочного процесса предоставить следующие документы:</p>
<p style="margin-left: 2pt">- подписанный настоящий договор, а в случае заключения договора через сайт предоставление договора в бумажном виде необязательно</p>
<p style="margin-left: 2pt">- медицинскую справку о допуске Воспитанника к занятиям выбранным видом спорта</p>
<p style="margin-left: 2pt">- для воспитанников в возрасте младше 12 лет предоставить справку на энтеробиоз и обновлять ее каждые 3 месяца при посещении бассейна.</p>
<p>3.1.3 В случае непредставления достоверной медицинской информации о Воспитаннике, Заказчик несет полную ответственность за его жизнь и здоровье.</p>
<p>3.1.4 Своевременно вносить плату за оказание услуг Исполнителем.</p>
<p>3.1.5 Нести имущественную (материальную) ответственность за нанесение Воспитанником материального ущерба Исполнителю.</p>
<p>3.1.6 Прибыть на занятия не ранее, чем за 10 минут до начала занятий, передать ребенка тренеру, и покинуть территорию Исполнителя.</p>
<p>3.1.7 После окончания занятий забрать Воспитанника и в течение 10 минут покинуть территорию Исполнителя. При опоздании Заказчика и невозможности забрать Воспитанника вовремя,
    Исполнитель не несёт ответственность за жизнь и здоровье Воспитанника в этот период.</p>
<p>3.2. Заказчик вправе</p>
<p>3.2.1 Требовать от Исполнителя предоставления качественных услуг в соответствии с условиями настоящего Договора.</p>
<p>3.2.2 Получать необходимую и достоверную информацию об оказываемых исполнителем Услугах.</p>
<p>3.2.3 Направлять Исполнителю свои отзывы, предложения и рекомендации по любому виду услуг.</p>
<p>4. Стоимость услуг и порядок взаиморасчетов.</p>
<p>4.1 Стоимость услуг Исполнителя по настоящему договору, составляет {{ $monthly_price }}
    ({{ $monthly_price_string }}) НДС не облагается за 1 месяц занятий. При этом стоимость разового занятия,
    используемая при перерасчетах по болезни, принимается равной {{ $training_return_price }} ({{ $training_return_price_string }}) без НДС. Правила перерасчета по болезни указаны
    в п 4.8 настоящего договора.</p>
<p>4.2 Оплата за спортивные занятия и тренировки производится 100% предоплатой до 7-го числа текущего месяца.</p>
<p>4.3 Дополнительные мероприятия и соревнования, проводимые в день занятий и тренировок, оплачиваются отдельно согласно прейскуранту. Занятия и тренировки в этот день перерасчету
    не подлежат.</p>
<p>4.4 В случае невнесения оплаты до 7-го числа текущего месяца, Воспитанник на занятия не допускается до полной оплаты занятий за месяц, с учетом дней, когда Воспитанник не был
    допущен на занятия в связи с неоплатой.</p>
<p>4.5 В случае отмены занятия по вине Исполнителя, оно переносится на другое время.</p>
<p>4.6 Занятия, пропущенные по причинам, не зависящим от Исполнителя, не возмещаются и подлежат своевременной оплате.</p>
<p>4.7 Занятия, выпавшие на официальные выходные праздничные дни, в случае их отмены не возмещаются и подлежат своевременной оплате.</p>
<p>4.8 В случае пропуска занятий по причине болезни Воспитанника, при предоставлении медицинской справки, производится перерасчёт оплаты за текущий или следующий месяц. Справка
    предоставляется не позднее 7 календарных дней после даты выписки. При этом стоимость фактически посещенных занятий рассчитывается исходя из стоимости разового занятия,
    указанного в п. 4.1</p>
<p>4.9 В случае невнесения предоплаты за текущий месяц и отсутствия Воспитанника на занятиях три недели, место за ним не сохраняется, настоящий Договор расторгается в одностороннем
    порядке со стороны Исполнителя. Новый договор заключается при наличии свободных мест.</p>
<p>4.10 В соответствии с п. 2 ст. 310, п. 2 ст. 424 ГК РФ, в течение срока действия настоящего Договора размер оплаты занятий может меняться в связи с изменением условий занятий,
    инфляционными процессами и индикативными показателями. Об изменении размеров платежей Заказчик предупреждается не менее, чем за две недели до даты их введения.</p>
<p>4.11 В случае, когда Воспитанник является членом многодетной семьи, Заказчик имеет право на уменьшение стоимости услуг на 10% от стоимости указанной в пункте 4.1. В этом случае
    стоимость составит {{ $discount_1_monthly_price }} ({{ $discount_1_monthly_price_string }}) Скидка подтверждается справкой о многодетности. Фотография справки направляется на
    нашу
    почту kudrovo.sport@yandex.ru</p>
<p>4.12 В случае, когда Воспитанник посещает занятия по двум и более направлений, Заказчик имеет право на уменьшение стоимости услуг на 10% от стоимости указанной в пункте 4.1. В
    этом случае стоимость составит {{ $discount_2_monthly_price }} ({{ $discount_2_monthly_price_string }}). Скидка действует только в тот период времени когда Воспитанник
    занимается в двух и более направлениях одновременно.</p>
<p>4.13 В случае, когда два и более Воспитанника одного Заказчика получают услугу тренировочного процесса осуществляемую Исполнителем, Заказчик имеет на уменьшение стоимости услуг
    на 10% от стоимости указанной в пункте 4.1. В этом случае стоимость составит {{ $discount_3_monthly_price }} ({{ $discount_3_monthly_price_string }}). Скидка действует только в
    тот момент, когда два и более Воспитанника занимаются одновременно. Скидка не распространяется на секцию «Плавание дошколята».</p>
<p>4.14 Скидки указанные в пункте 4.11, 4.12 и 4.13 не суммируются. Исполнитель оставляет за собой право увеличивать размер скидки на свое усмотрение.</p>
<p>5. Порядок заключения договора</p>
<p>5.1 Стороны признают надлежащим подписание договора, отчетов, актов, дополнительных соглашений путем обмена отсканированными копиями по электронной почте. Такие документы
    считаются подписанными простой электронной подписью и приравниваются к документам на бумажном носителе</p>
<p>5.2 Обмен отсканированными копиями договора, отчетов, актов, дополнительных соглашений производятся по следующим адресам электронной почты: электронная почта
    Заказчика: {{ $client_email }}, электронная почта Исполнителя: kudrovo.sport@yandex.ru</p>
<p>5.3 Стороны обязуются сохранять конфиденциальность доступов к электронной почте и не передавать их третьим лицам.</p>
<p>6. Порядок расторжения договора</p>
<p>6.1 Настоящий договор может быть расторгнут до окончания срока действия:</p>
<p>6.1.1 По взаимному соглашению сторон.</p>
<p>6.1.2 Заказчиком по желанию, выраженному в письменном заявлении. При этом производится возврат стоимости неиспользованных занятий, начиная с момента подачи заявления и до даты
    окончания оплаченного срока занятий.</p>
<p>6.1.3 По инициативе Исполнителя, если Заказчик или Воспитанник допускали нарушения, предусмотренные гражданским законодательством и настоящим Договором, договор расторгается в
    одностороннем порядке. При этом осуществляется возврат суммы оплаты по Договору за один месяц, за вычетом фактически отработанного Исполнителем времени.</p>
<p>7. Персональные данные</p>
<p>7.1 Заказчик выражает своё согласие на осуществление Исполнителем обработки, сбора, систематизации, хранения, использования персональных данных Воспитанника и Заказчика в
    соответствии с требованиями Федерального Закона от 27.07.2006г.№153-ФЗ «О персональных данных», а также согласие на фото и видеосъёмку Воспитанника и возможное дальнейшее
    опубликование фотографий на официальной странице в сети интернет и на рекламных материалах.</p>
<p>8. Форс-Мажор</p>
<p>8.1 В случае непредвиденных обстоятельств у собственника помещения или у администрации школы, не позволяющих проведения занятий и тренировок, Исполнитель обязуется выполнить
    условия договора, путем перенесения тренировок на другое время при наличии свободного времени на используемых площадях.</p>
<p>9. Заключительные положения</p>
<p>9.1 Споры по настоящему Договору разрешаются путем переговоров сторон или же в соответствии с действующим законодательством РФ.</p>
<p>9.2 Исполнитель не несет ответственности за вещи и имущество Заказчика.</p>
<p>9.3 Договор вступает в законную силу с момента его подписания сторонами и действует по «31» мая 2023 года, либо после получения электронной формы договора на электронную почту
    Заказчика.</p>
<p>9.4 Договор существует в 2 (двух) экземплярах, имеющих равную юридическую силу (один экземпляр для каждой стороны).</p>
<p>9.5 Любые изменения и дополнения к настоящему договору действительны только в письменной форме и подписанные Сторонами.</p>
<p>9.6 Неотъемлемой частью настоящего договора являются приложения: Приложение №1</p>
<p>9. Реквизиты и подписи сторон.</p>
<div style="page-break-inside: avoid">
    <div style="display: inline-block; vertical-align: top; width: 49%">
        <h2 style="text-align: center">Исполнитель</h2>
        <p>Общественная организация «Центр школьного спорта Всеволожского района»</p>

        <p>Наименование: {{ $organization_title }}</p>
        <p>ИНН: {{ $organization_inn }}</p>
        <p>КПП: {{ $organization_kpp }}</p>
        <p>р/с №{{ $bank_account }}</p>
        <p>{{ $bank_title }}</p>
        <p>БИК: {{ $bank_bik }}</p>
        <p>Корр. счет: {{ $bank_ks }}</p>
        <p>Контактный телефон: 89500332309</p>
        <p>Почтовый ящик: kudrovo.sport@yandex.ru</p>
        <p>Страница в сети интернет: https://vk.com/cskudrovo</p>

        <p>Председатель Совета</p>
        <p>Подпись</p>
        <p style="margin-top: 15pt">___________________________________/М.А.Алёшин/</p>
    </div>
    <div style="display: inline-block; vertical-align: top; width: 49%">
        <h2 style="text-align: center">Заказчик</h2>
        <p>{{ $client_name }}</p>
        <p>Паспорт: {{ $client_passport_serial }} {{ $client_passport_number }}, выдан {{ $client_passport_date }}{{ $client_passport_place }}, код
            подразделения {{ $client_passport_code }}</p>
        <p>Фактический адрес: {{ $client_address }}</p>
        <p>Подпись</p>
        <p style="margin-top: 15pt">___________________________________/{{ $client_compact_name }}/</p>
    </div>
</div>
<div style="page-break-after: always"></div>
<h1>Приложение №1 к Договору №{{ $contract_number }}</h1>
<p style="text-align: right">от {{ $contract_date }}</p>
<h2>Правила посещения.</h2>
<p>1.1 Тренер отвечает за жизнь и здоровье Воспитанника только во время проведения занятий. До начала тренировочного процесса и после окончания занятий за его жизнь, здоровье и
    поведение отвечает Заказчик.</p>
<p>1.2 К занятиям допускаются Воспитанники, прошедшие медицинский осмотр и не имеющие противопоказаний по состоянию здоровья для занятий данным видом спорта.</p>
<p>1.3 Заходить в зал и трогать спортивный инвентарь без разрешения тренера (учителя, преподавателя) строго запрещено.</p>
<p>1.4 В процессе занятий обучающиеся должны соблюдать порядок выполнения упражнений, следовать четким указаниям тренера (учителя, преподавателя).</p>
<p>1.5 Запрещено нахождение в зале Заказчика и других посторонних лиц, не участвующих в занятии, без разрешения тренера (учителя, преподавателя) и администрации Учреждения,
    предоставляющего помещения для занятий.</p>
<p>1.6 Все вопросы с тренером (учителем, преподавателем) решаются во время перерывов, ни в коем случае не во время занятий.</p>
<p>1.7 Запрещается нахождение в зале без формы и спортивной обуви, установленной тренером (учителем, преподавателем), в бассейне без шапочки, купального костюма и купальных
    принадлежностей, с включенным мобильным телефоном, с жевательной резинкой, в случае задолженности по оплате.</p>
<p>1.8 Исполнитель и тренер (учитель, преподаватель) вправе удалить с занятия в случае невыполнения указаний тренера (учителя, преподавателя), или неуважительного отношения к
    учащимся на занятии. Это является основанием для расторжения договора в одностороннем порядке.</p>
<p>1.9 Запрещается использование учащимися в спортивных секциях и их родителями фото- и видеосъёмки занятий без разрешения тренера (учителя, преподавателя).</p>
<p>1.10 В случае опоздания Воспитанника на занятие, тренер имеет право не допустить на занятие в целях безопасности.</p>
<p>1.11 В школе и на прилегающих территориях запрещено нахождение в состоянии алкогольного или наркотического опьянения, данный факт доводится до сведения администрации школы.</p>
<p>1.12 В целях безопасности других воспитанников, тренера, сотрудников, исполнитель вправе отказать в оказании услуг при признаках инфекционных, кожных и иных заболеваниях. Допуск
    на занятия производится после полного выздоровления при наличии справки от врача, разрешающей занятие.</p>
<p>1.13 Скан или фотография медицинской справки после болезни направляются на наш почтовый ящик kudrovo.sport@yandex.ru. В теме письма пишется «ФИО Воспитанника справка», а в теле
    письма комментарий Заказчика. Письма обрабатываются в течение трех рабочих дней.</p>

<p style="margin-top: 15pt">С настоящими правилами ознакомлен {{ $contract_date }}</p>
<p style="margin-top: 15pt">Заказчик ____________________/{{ $client_name }}/</p>
</body>
</html>
