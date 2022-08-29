<template>
    <div>
        <template v-if="!message">
            <GuiHeading>Записаться на регулярные тренировочные занятия</GuiHeading>

            <div style="margin-top: 20px"/>

            <GuiContainer>
                <FormString :form="form" :name="'lastname'"/>
                <FormString :form="form" :name="'firstname'"/>
                <FormString :form="form" :name="'patronymic'" :autocomplete="'additional-name'"/>
                <FormPhone :form="form" :name="'phone'"/>
                <FormString :form="form" :name="'email'"/>

                <div style="margin-top: 20px"/>

                <FormString :form="form" :name="'ward_lastname'"/>
                <FormString :form="form" :name="'ward_firstname'"/>
                <FormString :form="form" :name="'ward_patronymic'" :autocomplete="'additional-name'"/>
                <FormDate :form="form" :name="'ward_birth_date'"/>

                <div style="margin-top: 20px"/>

                <GuiHeading>Пожалуйста, укажите, если у ребенка есть особенности</GuiHeading>

                <div style="margin-top: 10px"/>

                <FormCheckBox :form="form" :name="'ward_spe'" :hide-title="true"/>
                <FormCheckBox :form="form" :name="'ward_uch'" :hide-title="true"/>
                <FormCheckBox :form="form" :name="'ward_inv'" :hide-title="true"/>

                <div style="margin-top: 20px"/>

                <FormDropdown :top="true" :form="form" :name="'region_id'" :options="regions" :identifier="'id'" :show="'name'" :placeholder="'Любой район'" :has-null="true" @change="regionChanged"/>
                <FormDropdown :top="true" class="vsc-services-drop" :form="form" :name="'service_id'" :options="regionServices" :identifier="'id'" :show="'title'"
                              :placeholder="'Выберите услугу'" :disabled="form.values['need_help'] === true"
                              @change="serviceChanged"
                />
                <FormCheckBox :form="form" :name="'need_help'" :without-title="true" @change="needHelpChanged"/>
            </GuiContainer>

            <GuiContainer v-if="service_info">
                <div style="margin-top: 10px; padding-left: 200px; box-sizing: border-box;">
                    <GuiValue :title="'Тренировочная база'">{{ service_info['training_base_title'] }}</GuiValue>
                    <GuiValue :title="'Адрес'">{{ service_info['training_base_address'] }}</GuiValue>
                    <GuiValue :title="'Вид спорта'">{{ service_info['service_sport_kind'] }}</GuiValue>
                    <GuiValue :title="'Стоимость в мес.'">{{ service_info['service_monthly_price'] }} руб.</GuiValue>
                    <GuiValue :title="'Занятий в неделю'">{{ service_info['service_trainings_per_week'] }}</GuiValue>
                    <GuiValue :title="'Занятий в месяц'">{{ service_info['service_trainings_per_month'] }}</GuiValue>
                    <GuiValue :title="'Продолжительность занятия'">{{ service_info['service_training_duration'] }} мин.</GuiValue>
                    <GuiValue :title="'Период занятий'">{{ service_info['service_start_at'] }} - {{ service_info['service_end_at'] }}</GuiValue>
                    <GuiValueArea :title="'Расписание занятий'" :text-content="service_info['schedule']"></GuiValueArea>
                </div>
            </GuiContainer>


            <div style="margin-top: 20px"/>

            <GuiContainer>
                <InputCheckbox v-model="agreement" :label="'Подтверждаю свое согласие на обработку моих персональных данных'"/>
                <div style="margin-top: 10px"/>
                <GuiButton :color="'blue'" @clicked="sendLead" :disabled="!agreement">Отправить заявку</GuiButton>
            </GuiContainer>

            <div style="margin-top: 20px"/>

            <GuiHint>Если Вы хотите записать более одного ребенка, или записаться на несколько секций, Вам необходимо оставить несколько отдельных заявок.</GuiHint>
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
import FormDate from "../../Components/Form/FormDate";
import FormCheckBox from "../../Components/Form/FormCheckBox";
import InputCheckbox from "../../Components/Inputs/InputCheckbox";
import GuiHint from "../../Components/GUI/GuiHint";
import GuiValue from "../../Components/GUI/GuiValue";
import GuiValueArea from "../../Components/GUI/GuiValueArea";

export default {
    components: {GuiValueArea, GuiValue, GuiHint, InputCheckbox, FormCheckBox, FormDate, GuiMessage, FormDropdown, GuiButton, GuiContainer, GuiHeading, FormString, FormPhone},

    props: {
        crm_url: {type: String, default: null},
        debug: {type: Boolean, default: false},
        session: {type: String, default: null},
        regions: {type: Array, default: () => ([])},
        services: {type: Array, default: () => ([])},
    },

    computed: {
        regionServices() {
            return this.services.filter(
                service => this.form.values['region_id'] === null || service.region_id === this.form.values['region_id']
            ).map(
                service => ({id: service['id'], title: service['title'], hint: service['address']})
            );
        }
    },

    data: () => ({
        form: form(null, null),
        message: null,
        agreement: false,
        service_info: null,
    }),

    created() {
        this.form.save_url = this.url('/leads/send');
        this.form.set('lastname', null, 'required', 'Фамилия (законного представителя)', true);
        this.form.set('firstname', null, 'required', 'Имя (законного представителя)', true);
        this.form.set('patronymic', null, 'required', 'Отчество (законного представителя)', true);

        this.form.set('ward_lastname', null, 'required', 'Фамилия (будущего чемпиона)', true);
        this.form.set('ward_firstname', null, 'required', 'Имя (будущего чемпиона)', true);
        this.form.set('ward_patronymic', null, 'required', 'Отчество (будущего чемпиона)', true);
        this.form.set('ward_birth_date', null, 'required', 'Дата рождения (будущего чемпиона)', true);

        this.form.set('ward_inv', null, null, 'Наличие инвалидности', true);
        this.form.set('ward_hro', null, null, 'Наличие хронических заболеваний', true);
        this.form.set('ward_uch', null, null, 'Состоит на учете у медицинских специалистов', true);
        this.form.set('ward_spe', null, null, 'Индивидуальные особенности воспитанника (физические, психологические)', true);

        this.form.set('phone', null, 'required', 'Телефон', true);
        this.form.set('email', null, 'required|email|bail', 'Email', true);
        this.form.set('region_id', null, null, 'Район', true);
        this.form.set('service_id', null, 'required_if:need_help,false', 'Услуга', true);
        this.form.set('need_help', false, null, 'Не могу определиться с секцией, прошу со мной связаться', true);

        this.form.load();
    },

    methods: {
        url(path) {
            return this.crm_url + path + (this.debug ? '?XDEBUG_SESSION_START=PHPSTORM' : '');
        },

        sendLead() {
            if (!this.form.validate()) {
                return;
            }

            // override form saving to send headers
            this.form.is_saving = true;
            axios.post(this.form.save_url, {
                data: this.form.values,
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

        regionChanged() {
            this.form.values['service_id'] = null;
            this.serviceChanged(null);
        },

        needHelpChanged(value) {
            if (value) {
                this.form.update('service_id', null);
                this.serviceChanged(null);
            }
        },

        serviceChanged(service_id) {
            if (service_id === null) {
                this.service_info = null;
                return;
            }
            this.service_info_loading = true;
            axios.post('/leads/info', {service_id: service_id}, {headers: {'X-Vsc-Session': this.session}})
                .then(response => {
                    this.service_info = response.data['data'];
                })
                .catch(error => {
                    this.message = error.response.data['message'];
                })
                .finally(() => {
                    this.service_info_loading = false;
                })
        },
    }
}
</script>
