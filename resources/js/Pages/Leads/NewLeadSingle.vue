<template>
    <template v-if="!message">
        <LeadGuiContainer>
            <HeaderSingle/>

            <div style="margin-top: 30px"/>

            <LeadBlockForm label="ФИО и данные законного представителя">
                <LeadFormString :form="form" :name="'lastname'"/>
                <LeadFormString :form="form" :name="'firstname'"/>
                <LeadFormString :form="form" :name="'patronymic'" :autocomplete="'additional-name'"/>
                <div class="container-lead-form-center">
                    <LeadFormPhone :form="form" :name="'phone'" class="input-field-50"/>
                    <LeadFormString :form="form" :name="'email'" class="input-field-50__second"/>
                </div>
            </LeadBlockForm>

            <div style="margin-top: 30px"/>

            <LeadBlockForm label="ФИО и данные будущего чемпиона" :right-ball="true">
                <LeadFormString :form="form" :name="'ward_lastname'"/>
                <LeadFormString :form="form" :name="'ward_firstname'"/>
                <LeadFormString :form="form" :name="'ward_patronymic'" :autocomplete="'additional-name'"/>
                <LeadFormDate :form="form" :name="'ward_birth_date'"/>
            </LeadBlockForm>

            <div style="margin-top: 30px"/>

            <LeadBlockForm label="Пожалуйста, укажите, если у ребенка есть особенности">
                <div class="container-lead-form-center">
                    <LeadFormCheckBoxSingle :form="form" :name="'ward_spe'" :hide-title="true" class="input-field-50"/>
                    <LeadFormCheckBoxSingle :form="form" :name="'ward_uch'" :hide-title="true" class="input-field-50__second-checkbox"/>
                </div>
                <LeadFormCheckBoxSingle :form="form" :name="'ward_inv'" :hide-title="true"/>

                <LeadFormDropdownSingle :top="true"
                                  :form="form"
                                  class="input-field-50"
                                  :name="'region_id'"
                                  :options="regions"
                                  :identifier="'id'"
                                  :show="'name'"
                                  :placeholder="'Любой район'"
                                  :has-null="true" @change="regionChanged"/>
                <div class="input-field-50__second"></div>

                <div class="container-lead-form-center">
                    <LeadFormDropdownSingle :top="true"
                                      class="vsc-services-drop input-field-50" :form="form" :name="'service_id'" :options="regionServices" :identifier="'id'" :show="'title'"
                                      :placeholder="'Выберите услугу'" :disabled="form.values['need_help'] === true"
                                      @change="serviceChanged"
                    />

                    <LeadFormCheckBoxSingle :form="form"
                                      class="input-field-50__second-checkbox"
                                      :name="'need_help'"
                                      :hide-title="true"
                                      @change="needHelpChanged"/>
                </div>

                <GuiContainer v-if="service_info" service_info>
                    <div class="service_info">
                        <GuiValue :title="'Тренировочная база'">{{ service_info['training_base_title'] }}</GuiValue>
                        <GuiValue :title="'Адрес'">{{ service_info['training_base_address'] }}</GuiValue>
                        <GuiValue :title="'Стоимость услуги руб.'">{{ service_info['price'] }} руб.</GuiValue>
                        <GuiValue :title="'Дата внесения средств'">{{ service_info['date_deposit_funds'] }}</GuiValue>
                        <GuiValue :title="'Авансовый платеж'">{{ service_info['advance_payment'] }}</GuiValue>
                        <GuiValue :title="'Дата внесения аванса'">{{ service_info['date_advance_payment'] }}</GuiValue>
                        <GuiValue :title="'Период пребывания'">{{ service_info['service_start_at'] }} - {{ service_info['service_end_at'] }}</GuiValue>
                    </div>
                </GuiContainer>

                <FormText :form="form" :name="'client_comments'"/>
            </LeadBlockForm>

            <div style="margin-top: 30px"/>

            <LeadBlockForm class="container-form__no-image">
                <InputCheckbox v-model="agreement" :label="'Подтверждаю свое согласие на обработку моих персональных данных'"/>
            </LeadBlockForm>

            <div style="margin-top: 30px"/>

            <div class="container-center">
                <div class="btn_fon">
                    <LeadGuiButton :class="'center'" @clicked="sendLead" :disabled="!agreement">Отправить заявку</LeadGuiButton>
                </div>

                <div style="margin-top: 25px"/>

                <div class="container-wrapper">
                    <LeadGuiHint>Если Вы хотите записать более одного ребенка, Вам необходимо оставить несколько отдельных заявок</LeadGuiHint>
                </div>
            </div>
        </LeadGuiContainer>
    </template>

    <GuiMessage v-else>{{ message }}</GuiMessage>
</template>

<script>
import form from "@/Core/Form";
import GuiContainer from "@/Components/GUI/GuiContainer.vue";
import GuiHeading from "@/Components/GUI/GuiHeading.vue";
import FormString from "@/Components/Form/FormString.vue";
import FormPhone from "@/Components/Form/FormPhone.vue";
import GuiButton from "@/Components/GUI/GuiButton.vue";
import FormDropdown from "@/Components/Form/FormDropdown.vue";
import GuiMessage from "@/Components/GUI/GuiMessage.vue";
import FormDate from "../../Components/Form/FormDate.vue";
import FormCheckBox from "../../Components/Form/FormCheckBox.vue";
import InputCheckbox from "../../Components/Inputs/InputCheckbox.vue";
import GuiHint from "../../Components/GUI/GuiHint.vue";
import GuiValue from "../../Components/GUI/GuiValue.vue";
import GuiValueArea from "../../Components/GUI/GuiValueArea.vue";
import FormText from "../../Components/Form/FormText.vue";
import HeaderSingle from "@/Pages/Leads/Parts/HeaderSingle.vue";
import LeadGuiContainer from "@/Pages/Leads/Components/GUI/LeadGuiContainer.vue";
import LeadBlockForm from "@/Pages/Leads/Components/LeadBlockForm.vue";
import LeadFormString from "@/Pages/Leads/Components/LeadFormString.vue";
import LeadFormPhone from "@/Pages/Leads/Components/LeadFormPhone.vue";
import LeadFormDate from "@/Pages/Leads/Components/LeadFormDate.vue";
import LeadFormCheckBoxSingle from "@/Pages/Leads/Components/LeadFormCheckBoxSingle.vue";
import LeadFormDropdownSingle from "@/Pages/Leads/Components/LeadFormDropdownSingle.vue";
import LeadGuiButton from "@/Pages/Leads/Components/GUI/LeadGuiButton.vue";
import LeadGuiHint from "@/Pages/Leads/Components/GUI/LeadGuiHint.vue";

export default {
    components: {
        LeadGuiHint,
        LeadGuiButton,
        LeadFormDropdownSingle,
        LeadFormCheckBoxSingle,
        LeadFormDate,
        LeadFormPhone,
        LeadFormString,
        LeadBlockForm,
        LeadGuiContainer,
        HeaderSingle,
        FormText,
        GuiValueArea, GuiValue, GuiHint, InputCheckbox, FormCheckBox, FormDate, GuiMessage, FormDropdown, GuiButton, GuiContainer, GuiHeading, FormString, FormPhone},

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
        this.form.save_url = this.url('/leads-single/send');
        this.form.set('lastname', null, 'required', 'Фамилия', true);
        this.form.set('firstname', null, 'required', 'Имя', true);
        this.form.set('patronymic', null, 'required', 'Отчество', true);

        this.form.set('ward_lastname', null, 'required', 'Фамилия', true);
        this.form.set('ward_firstname', null, 'required', 'Имя', true);
        this.form.set('ward_patronymic', null, 'required', 'Отчество', true);
        this.form.set('ward_birth_date', null, 'required', 'Дата рождения (будущего чемпиона)', true);

        this.form.set('ward_inv', null, null, 'Наличие инвалидности', true);
        this.form.set('ward_hro', null, null, 'Наличие хронических заболеваний', true);
        this.form.set('ward_uch', null, null, 'Состоит на учете у медицинских специалистов', true);
        this.form.set('ward_spe', null, null, 'Индивидуальные особенности воспитанника (физические, психологические)', true);

        this.form.set('phone', null, 'required', 'Телефон', true);
        this.form.set('email', null, 'required|email|bail', 'Email', true);
        this.form.set('region_id', null, null, 'Район', true);
        this.form.set('service_id', null, 'required_if:need_help,false', 'Услуга', true);
        this.form.set('need_help', false, null, 'Не могу определиться, прошу со мной связаться', true);

        this.form.set('client_comments', null, null, 'Комментарии', true);

        this.form.load();
    },

    methods: {
        url(path) {
            return this.crm_url + path + (this.debug ? '?XDEBUG_SESSION_START=PHPSTORM' : '');
        },

        sendLead() {
            // remove whitespaces in email
            this.form.values['email'] = this.form.values['email'] ? String(this.form.values['email']).trim() : null;
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
            axios.post('/leads-single/info', {service_id: service_id}, {headers: {'X-Vsc-Session': this.session}})
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

<style lang="scss">
$base_button_color: #fdc93c !default;
$base_disabled_color: #b4902e !default;

.btn_fon {
    background-color: $base_button_color;
    width: min-content;
    border-radius: 50px;
    margin: 0 auto;
    transform: rotate(356deg);
}

.button__lead {
    transform: rotate(3deg);
}

.service_info {
    margin-top: 10px;
    padding-left: 150px;
    box-sizing: border-box;
}

.ball {
    display: block;
    position: absolute;
    width: 150px;
    right: 12%;
    bottom: 0;
}

.container {
    &-wrapper {
        max-width: 90%;
        margin: 0 auto;
        text-align: left;
    }

    &-center {
        text-align: center;
    }

    &-lead {
        &-form {
            &-center{
                display: flex;
                align-items: center;
            }
        }
    }
}


@media screen and (max-width: 769px) {
    .service_info {
        padding-left: 0;
    }

    .ball {
        display: none;
    }

    .container {
        &-wrapper {
            max-width: 100%;
            margin: 0 auto;
        }
    }
}
</style>

