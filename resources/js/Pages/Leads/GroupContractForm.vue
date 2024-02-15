<template>
    <div>
        <template v-if="!message">

            <GuiHeading>Заполнение формы договора</GuiHeading>
            <div style="margin-top: 20px"></div>
            <GuiText>
                Настоящая анкета является основой для формирования договора об оказании услуг, который в последующем направляется заказчику с резолюцией исполнителя по электронной
                почте для ответного подписания. При необходимости заказчик может получить получения копию договора на физическом носителе в офисе школы.
            </GuiText>
            <div style="margin-top: 20px"></div>
            <GuiText>
                В случае заполнения настоящей формы на сайте предоставление договора на бумажном носителе перед началом тренировочного процесса не требуется.
            </GuiText>
            <div style="margin-top: 20px"></div>
            <GuiText>
                Настоящая анкета включает в себя комплекс обязательных для ознакомления сведений и данных, а также предусматривает необходимость выражения согласия заказчика с
                условиями оказания исполнителем услуг по договору. В целях сообщения верной корректной информации, а также во избежание неполной осведомленности о сути, процессе и
                правилах оказания услуг, настоятельно просим внимательно ознакомиться с содержанием заполняемой формы.
            </GuiText>

            <div style="margin-top: 20px"></div>
            <GuiHeading>Услуга</GuiHeading>

            <div style="margin-top: 20px"></div>
            <GuiValue :title="'Услуга'" :dots="false">{{ serviceData['title'] }}</GuiValue>
            <GuiValue :title="'Дата оказания услуги'" :dots="false">{{ serviceData['service_start_date'] }}</GuiValue>
            <GuiValue :title="'Место оказание услуг'" :dots="false">{{ serviceData['training_base_title'] }}</GuiValue>
            <GuiValue :title="'Адрес'" :dots="false">{{ serviceData['training_base_address'] }}</GuiValue>

            <GuiHeading>Данные для договора</GuiHeading>

            <template v-if="subscriptionData['is_contract_legal']">
                <GuiContainer>
                    <FormString :form="form" :name="'organization_name'"/>
                    <FormString :form="form" :name="'in_face'"/>
                    <FormString :form="form" :name="'inn'"/>
                    <FormString :form="form" :name="'kpp'"/>
                    <FormString :form="form" :name="'checking_account'"/>
                    <FormString :form="form" :name="'bic'"/>
                    <FormString :form="form" :name="'corr_account'"/>
                    <FormString :form="form" :name="'org_email'"/>
                    <FormPhone :form="form" :name="'org_phone'"/>
                </GuiContainer>
                <GuiHeading v-if="subscriptionData['is_contract_legal']">Контактное лицо</GuiHeading>
            </template>

            <div style="margin-top: 20px"></div>
            <GuiContainer>
                <FormString :form="form" :name="'lastname'"/>
                <FormString :form="form" :name="'firstname'"/>
                <FormString :form="form" :name="'patronymic'"/>
                <FormPhone :form="form" :name="'phone'"/>
                <FormString :form="form" :name="'email'"/>
            </GuiContainer>

            <template v-if="!subscriptionData['is_contract_legal']">
                <div style="margin-top: 20px"></div>
                <GuiContainer>
                  <FormDate :form="form" :name="'birth_date'"/>
                  <FormString :form="form" :name="'passport_serial'"/>
                  <FormString :form="form" :name="'passport_number'"/>
                  <FormString :form="form" :name="'passport_place'"/>
                  <FormDate :form="form" :name="'passport_date'"/>
                  <FormString :form="form" :name="'passport_code'"/>
                  <FormString :form="form" :name="'registration_address'"/>
                </GuiContainer>
            </template>

            <div style="margin-top: 20px"></div>
            <GuiHeading>Состав группы</GuiHeading>

            <div style="margin-top: 20px"></div>
            <GuiContainer w-50 mt-30 pr-10 inline>

                <div class="group-block-row">
                    <div class="group-block-row__title"></div>
                    <GuiText group-block-row__input>Кол-во девочек</GuiText>
                    <GuiText group-block-row__input>Кол-во мальчиков</GuiText>
                    <div></div>
                </div>

                <div class="group-block-row">
                    <GuiText group-block-row__title>До 10 лет</GuiText>
                    <FormString class="group-block-row__input" :form="form" :hide-title="true" :name="'girls_1_count'"/>
                    <FormString class="group-block-row__input" :form="form" :hide-title="true" :name="'boys_1_count'"/>
                    <div></div>
                </div>

                <div class="group-block-row">
                    <GuiText group-block-row__title>10-17 лет</GuiText>
                    <FormString class="group-block-row__input" :form="form" :hide-title="true" :name="'girls_2_count'"/>
                    <FormString class="group-block-row__input" :form="form" :hide-title="true" :name="'boys_2_count'"/>
                    <div></div>
                </div>

                <div class="group-block-row">
                    <GuiText group-block-row__title>старше 18 лет</GuiText>
                    <FormString class="group-block-row__input" :form="form" :hide-title="true" :name="'girls_3_count'"/>
                    <FormString class="group-block-row__input" :form="form" :hide-title="true" :name="'boys_3_count'"/>
                    <div></div>
                </div>

                <div class="group-block-row">
                    <GuiText group-block-row__title>Количество тренеров</GuiText>
                    <FormString class="group-block-row__input" :form="form" :hide-title="true" :name="'trainer_count'"/>
                    <div class="group-block-row__input"></div>
                    <div></div>
                </div>

                <div class="group-block-row">
                    <GuiText group-block-row__title>Количество сопровожждающих</GuiText>
                    <FormString class="group-block-row__input" :form="form" :hide-title="true" :name="'attendant_count'"/>
                    <div class="group-block-row__input"></div>
                    <div></div>
                </div>
            </GuiContainer>

            <div style="margin-top: 20px"></div>
            <GuiHeading>Дополнительные условия</GuiHeading>

            <div style="margin-top: 20px"></div>
            <GuiContainer>
                <FormText :form="form" :name="'additional_conditions'"/>
            </GuiContainer>

            <div style="margin-top: 20px"></div>
            <InputCheckbox v-model="agreement_1">Подтверждаю свое согласие на обработку моих персональных данных и персональных данных воспитанника</InputCheckbox>
            <div style="margin-top: 15px"></div>
            <InputCheckbox v-model="agreement_2">Подтверждаю свое согласие на фото- и видеосъемку спортивных занятий с возможностью публикации таких материалов в сети Интернет, в
                том числе, в рекламных и информационных целях
            </InputCheckbox>
            <div style="margin-top: 15px"></div>
            <InputCheckbox v-model="agreement_3">Уведомлен о необходимости предоставления справки об отсутствии медицинских противопоказаний для занятия выбранным видом спорта,
                результаты анализа на энтеробиозу детей до 12 лет в случае посещения бассейна
            </InputCheckbox>
            <div style="margin-top: 15px"></div>
            <InputCheckbox v-model="agreement_4">Уведомлен о том, что воспитанник не допускается до занятий в случае наличия признаков острого респираторного заболевания, вирусной
                инфекции, covid-19, иных симптомов, свидетельствующих о плохом самочувствии воспитанника, в случае нахождения в больничном воспитанника либо лиц, совместно
                проживающих с воспитанником (состояние временной нетрудоспособности, карантин, самоизоляция)
            </InputCheckbox>
            <div style="margin-top: 15px"></div>
            <InputCheckbox v-model="agreement_5">Я ознакомлен с <a :href="crm_url + '/leads/contract/' + subscriptionKey" target="_blank">Договором и Приложениями</a> и подтверждаю
                свое согласие с всеми правилами и условиями
            </InputCheckbox>

            <div style="margin-top: 20px"></div>
            <GuiContainer>
                <GuiButton :color="'blue'" @clicked="sendContract" :disabled="!agree">Отправить</GuiButton>
            </GuiContainer>
        </template>
        <GuiMessage v-else>{{ message }}</GuiMessage>
    </div>
</template>

<script>
import form from "@/Core/Form";
import GuiContainer from "@/Components/GUI/GuiContainer";
import GuiHeading from "@/Components/GUI/GuiHeading";
import FormString from "@/Components/Form/FormString";
import FormText from "@/Components/Form/FormText";
import FormPhone from "@/Components/Form/FormPhone";
import GuiButton from "@/Components/GUI/GuiButton";
import FormDropdown from "@/Components/Form/FormDropdown";
import GuiMessage from "@/Components/GUI/GuiMessage";
import GuiValue from "@/Components/GUI/GuiValue";
import FormDate from "@/Components/Form/FormDate";
import FormNumber from "@/Components/Form/FormNumber";
import GuiText from "../../Components/GUI/GuiText";
import InputCheckbox from "../../Components/Inputs/InputCheckbox";
import GuiValueArea from "../../Components/GUI/GuiValueArea";

export default {
    components: {GuiValueArea, InputCheckbox, GuiText, FormNumber, FormDate, GuiValue, GuiMessage, FormDropdown, GuiButton, GuiContainer, GuiHeading, FormString, FormText, FormPhone},

    props: {
        crm_url: {type: String, default: null},
        debug: {type: Boolean, default: false},
        session: {type: String, default: null},
        subscriptionKey: {type: String, default: null},
        subscriptionId: {type: Number, default: null},
        subscriptionData: {type: Object, default: null},
        serviceData: {type: Object, default: null},
    },

    computed: {
        agree() {
            return this.agreement_1 & this.agreement_2 & this.agreement_3 & this.agreement_4 & this.agreement_5;
        }
    },

    data: () => ({
        form: form(null, null),
        message: null,
        agreement_1: false,
        agreement_2: false,
        agreement_3: false,
        agreement_4: false,
        agreement_5: false,
    }),

    created() {
        this.form.save_url = this.url('/leads-group/contract');
        this.form.set('lastname', this.getData('lastname'), 'required', 'Фамилия', true);
        this.form.set('firstname', this.getData('firstname'), 'required', 'Имя', true);
        this.form.set('patronymic', this.getData('patronymic'), 'required', 'Отчество', true);
        this.form.set('phone', this.getData('phone'), 'required', 'Телефон', true);
        this.form.set('email', this.getData('email'), 'required|email|bail', 'Email', true);


        this.form.set('girls_1_count', this.getData('girls_1_count'), null, 'До 10 лет', true);
        this.form.set('boys_1_count', this.getData('boys_1_count'), null, null, true);
        this.form.set('girls_2_count', this.getData('girls_2_count'), null, '10-17 лет', true);
        this.form.set('boys_2_count', this.getData('boys_2_count'), null, null, true);
        this.form.set('girls_3_count', this.getData('girls_3_count'), null, '18 лет и старше', true);
        this.form.set('boys_3_count', this.getData('boys_3_count'), null, null, true);
        this.form.set('ward_count', this.getData('ward_count'), 'required', 'Общее количество детей', true);
        this.form.set('trainer_count', this.getData('trainer_count'), 'required', 'Количество тренеров', true);
        this.form.set('attendant_count', this.getData('attendant_count'), 'required', 'Количество сопровождающих', true);

        this.form.set('additional_conditions', null, null, 'Дополнительные условия', true);

        if (this.subscriptionData['is_contract_legal']) {
            this.form.set('organization_name', this.getData('organization_name'), 'required', 'Название организации', true);
            this.form.set('in_face', null, 'required', 'В лице', true);
            this.form.set('inn', null, 'required', 'ИНН', true);
            this.form.set('kpp', null, 'required', 'КПП/ОГРН', true);
            this.form.set('checking_account', null, 'required', 'Расчетный счет', true);
            this.form.set('bic', null, 'required', 'БИК', true);
            this.form.set('corr_account', null, 'required', 'К/с', true);
            this.form.set('org_email', null, 'required|email|bail', 'Email', true);
            this.form.set('org_phone', null, 'required', 'Телефон', true);
        } else {
            this.form.set('birth_date', this.getData('birth_date'), 'required', 'Дата рождения', true);
            this.form.set('passport_serial', this.getData('passport_serial'), 'required', 'Серия паспорта', true);
            this.form.set('passport_number', this.getData('passport_number'), 'required', 'Номер паспорта', true);
            this.form.set('passport_place', this.getData('passport_place'), 'required', 'Кем выдан', true);
            this.form.set('passport_date', this.getData('passport_date'), 'required', 'Дата выдачи', true);
            this.form.set('passport_code', this.getData('passport_code'), 'required', 'Код подразделения', true);
            this.form.set('registration_address', this.getData('address'), 'required', 'Адрес регистрации', true);
        }

        this.form.load();
    },

    methods: {
        url(path) {
            return this.crm_url + path + (this.debug ? '?XDEBUG_SESSION_START=PHPSTORM' : '');
        },

        getData(key) {
            return this.subscriptionData && this.subscriptionData[key] ? this.subscriptionData[key] : null;
        },

        sendContract() {
            if (!this.form.validate()) {
                return;
            }

            // override form saving to send headers
            this.form.is_saving = true;
            axios.post(this.form.save_url, {
                data: this.form.values,
                subscription_id: this.subscriptionId,
            }, {headers: {'X-Vsc-Session': this.session}})
                .then(response => {
                    // show message
                    this.message = response.data['message'];
                })
                .catch(error => {
                    this.message = error.response.data['message'];
                })
                .finally(() => {
                    this.form.is_saving = false;
                })
        },
    }
}
</script>
<style lang="scss">
.group-block-row {
  display: flex;
  justify-content: space-between;
  &__title {
     width: 25%;
   }
  &__input {
     width: 25%;
   }
}
</style>