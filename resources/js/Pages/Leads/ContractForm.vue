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
            <GuiValue :title="'Вид спорта'" :dots="false">{{ serviceData['sport_kind'] }}</GuiValue>
            <GuiValue :title="'Тренировочная база'" :dots="false">{{ serviceData['training_base_title'] }}</GuiValue>
            <GuiValue :title="'Адрес'" :dots="false">{{ serviceData['training_base_address'] }}</GuiValue>
            <GuiValue :title="'График занятий'" :dots="false">{{ serviceData['schedule'] }}</GuiValue>

            <div style="margin-top: 20px"></div>
            <GuiHeading>Данные для договора</GuiHeading>

            <div style="margin-top: 20px"></div>
            <GuiContainer>
                <FormString :form="form" :name="'lastname'"/>
                <FormString :form="form" :name="'firstname'"/>
                <FormString :form="form" :name="'patronymic'"/>
                <FormPhone :form="form" :name="'phone'"/>
                <FormString :form="form" :name="'email'"/>
            </GuiContainer>

            <div style="margin-top: 20px"></div>
            <GuiContainer>
                <FormNumber :form="form" :name="'passport_serial'"/>
                <FormNumber :form="form" :name="'passport_number'"/>
                <FormString :form="form" :name="'passport_place'"/>
                <FormDate :form="form" :name="'passport_date'"/>
                <FormString :form="form" :name="'passport_code'"/>
                <FormString :form="form" :name="'registration_address'"/>
            </GuiContainer>

            <div style="margin-top: 20px"></div>
            <GuiHeading>Данные ребёнка</GuiHeading>

            <div style="margin-top: 20px"></div>
            <GuiContainer>
                <FormString :form="form" :name="'ward_lastname'"/>
                <FormString :form="form" :name="'ward_firstname'"/>
                <FormString :form="form" :name="'ward_patronymic'"/>
                <FormDate :form="form" :name="'ward_birth_date'"/>
                <FormString :form="form" :name="'ward_document'"/>
                <FormDate :form="form" :name="'ward_document_date'"/>
            </GuiContainer>

            <div style="margin-top: 20px"></div>
            <div style="margin-top: 10px; padding-left: 200px; box-sizing: border-box;">
                <InputCheckbox v-model="discount" :label="'Я могу рассчитывать на льготу'" @change="discountChanged"/>
            </div>
            <FormDropdown :form="form" :name="'discount'" :options="discounts" :show="'name'" :identifier="'id'" :top="true" :disabled="!discount"/>

            <div style="margin-top: 20px"></div>
            <InputCheckbox v-model="agreement_1">Подтверждаю свое согласие на обработку моих персональных данных и персональных данных воспитанника</InputCheckbox>
            <div style="margin-top: 15px"></div>
            <InputCheckbox v-model="agreement_2">Подтверждаю свое согласие на фото- и видеосъемку спортивных занятий с возможностью публикации таких материалов в сети Интернет, в
                том числе, в рекламных и информационных целях
            </InputCheckbox>
            <div style="margin-top: 15px"></div>
            <InputCheckbox v-model="agreement_3">Уведомлен о необходимости предоставления справки об отсутствии медицинских противопоказаний для занятия выбранным видом спорта,
                справку об отсутствии у воспитанника * в случае посещения бассейна
            </InputCheckbox>
            <div style="margin-top: 15px"></div>
            <InputCheckbox v-model="agreement_4">Уведомлен о том, что воспитанник не допускается до занятий в случае наличия признаков острого респираторного заболевания, вирусной
                инфекции, covid-19, иных симптомов, свидетельствующих о плохом самочувствии воспитанника, в случае нахождения в больничном воспитанника либо лиц, совместно
                проживающих с воспитанником (состояние временной нетрудоспособности, карантин, самоизоляция)
            </InputCheckbox>
            <div style="margin-top: 15px"></div>
            <InputCheckbox v-model="agreement_5">Я ознакомлен с <a :href="crm_url + '/leads/contract/' + subscriptionKey" target="_blank">Договором и Приложениями</a> и подтверждаю свое согласие с всеми правилами и условиями</InputCheckbox>

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
import FormPhone from "@/Components/Form/FormPhone";
import GuiButton from "@/Components/GUI/GuiButton";
import FormDropdown from "@/Components/Form/FormDropdown";
import GuiMessage from "@/Components/GUI/GuiMessage";
import GuiValue from "@/Components/GUI/GuiValue";
import FormDate from "@/Components/Form/FormDate";
import FormNumber from "@/Components/Form/FormNumber";
import GuiText from "../../Components/GUI/GuiText";
import InputCheckbox from "../../Components/Inputs/InputCheckbox";

export default {
    components: {InputCheckbox, GuiText, FormNumber, FormDate, GuiValue, GuiMessage, FormDropdown, GuiButton, GuiContainer, GuiHeading, FormString, FormPhone},

    props: {
        crm_url: {type: String, default: null},
        debug: {type: Boolean, default: false},
        session: {type: String, default: null},
        subscriptionKey: {type: String, default: null},
        subscriptionId: {type: Number, default: null},
        subscriptionData: {type: Object, default: null},
        serviceData: {type: Object, default: null},
        discounts: {type: Array, default: null},
    },

    computed: {
        agree() {
            return this.agreement_1 & this.agreement_2 & this.agreement_3 & this.agreement_4 & this.agreement_5;
        }
    },

    data: () => ({
        form: form(null, null),
        message: null,
        discount: false,
        agreement_1: false,
        agreement_2: false,
        agreement_3: false,
        agreement_4: false,
        agreement_5: false,
    }),

    created() {
        this.form.save_url = this.url('/leads/contract');
        this.form.set('lastname', this.getData('lastname'), 'required', 'Фамилия', true);
        this.form.set('firstname', this.getData('firstname'), 'required', 'Имя', true);
        this.form.set('patronymic', this.getData('patronymic'), 'required', 'Отчество', true);
        this.form.set('phone', this.getData('phone'), 'required', 'Телефон', true);
        this.form.set('email', this.getData('email'), 'required|email|bail', 'Email', true);

        this.form.set('passport_serial', this.getData('passport_serial'), 'required', 'Серия паспорта', true);
        this.form.set('passport_number', this.getData('passport_number'), 'required', 'Номер паспорта', true);
        this.form.set('passport_place', this.getData('passport_place'), 'required', 'Кем выдан', true);
        this.form.set('passport_date', this.getData('passport_date'), 'required', 'Дата выдачи', true);
        this.form.set('passport_code', this.getData('passport_code'), 'required', 'Код подразделения', true);
        this.form.set('registration_address', this.getData('address'), 'required', 'Адрес регистрации', true);

        this.form.set('ward_lastname', this.getData('ward_lastname'), 'required', 'Фамилия', true);
        this.form.set('ward_firstname', this.getData('ward_firstname'), 'required', 'Имя', true);
        this.form.set('ward_patronymic', this.getData('ward_patronymic'), 'required', 'Отчество', true);
        this.form.set('ward_birth_date', this.getData('ward_birth_date'), 'required', 'Дата рождения', true);
        this.form.set('ward_document', this.getData('ward_document'), 'required', 'Свидетельство о рождении', true);
        this.form.set('ward_document_date', this.getData('ward_document_date'), 'required', 'Дата выдачи', true);

        this.form.set('discount', null, null, 'Основание для льготы', true);

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

        discountChanged() {
            this.form.update('discount', null);
        },
    }
}
</script>
