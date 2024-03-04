<template>
    <template v-if="!message">
        <LeadGuiContainer>
            <HeaderGroup/>

            <div style="margin-top: 30px"/>
            <LeadBlockForm>
              <GuiText>
                Уважаемые организаторы!<br>
                Не переживайте, все указанные в заявке данные можно будет уточнить и отредактировать с менеджером перед заполнением договора (например, количество, возраст, пол детей, количество сопровождающих и пр.)
              </GuiText>
            </LeadBlockForm>
            <div style="margin-top: 30px"/>
            <LeadBlockForm label="Данные организатора">
                <GuiHeading>Контактное лицо</GuiHeading>
                <LeadFormString :form="form" :name="'lastname'"/>
                <LeadFormString :form="form" :name="'firstname'"/>
                <LeadFormString :form="form" :name="'patronymic'" :autocomplete="'additional-name'"/>
                <div class="container-lead-form-center">
                    <LeadFormPhone :form="form" :name="'phone'" class="input-field-50"/>
                    <LeadFormString :form="form" :name="'email'" class="input-field-50__second"/>
                </div>
                <LeadFormCheckBoxSingle :form="form" :name="'is_contract_individual'" :hide-title="true" class="input-field-50"/>
                <GuiText>Если договор будет оформляться на организацию или юрлицо, укажите наименование в поле ниже</GuiText>
                <div style="margin-top: 30px"/>
                <GuiHeading>Название организации</GuiHeading>
                <LeadFormString :form="form" :name="'organization_name'"/>
            </LeadBlockForm>

            <div style="margin-top: 30px"/>

            <LeadBlockForm label="Заявка на группу" :right-ball="true">
                <div class="group-block-row">
                  <GuiHeading group-block-row__title>Состав группы</GuiHeading>
                  <GuiText group-block-row__input>Кол-во девочек</GuiText>
                  <GuiText group-block-row__input>Кол-во мальчиков</GuiText>
                  <div></div>
                </div>

                <div class="group-block-row">
                  <GuiText group-block-row__title>До 10 лет</GuiText>
                  <LeadFormString class="group-block-row__input" :form="form" :hide-title="true" :name="'girls_1_count'"/>
                  <LeadFormString class="group-block-row__input" :form="form" :hide-title="true" :name="'boys_1_count'"/>
                  <div></div>
                </div>

                <div class="group-block-row">
                  <GuiText group-block-row__title>10-17 лет</GuiText>
                  <LeadFormString class="group-block-row__input" :form="form" :hide-title="true" :name="'girls_2_count'"/>
                  <LeadFormString class="group-block-row__input" :form="form" :hide-title="true" :name="'boys_2_count'"/>
                  <div></div>
                </div>

                <div class="group-block-row">
                  <GuiText group-block-row__title>старше 18 лет</GuiText>
                  <LeadFormString class="group-block-row__input" :form="form" :hide-title="true" :name="'girls_3_count'"/>
                  <LeadFormString class="group-block-row__input" :form="form" :hide-title="true" :name="'boys_3_count'"/>
                  <div></div>
                </div>

                <div class="group-block-row">
                  <GuiText group-block-row__title>Общее количество воспитанников</GuiText>
                  <LeadFormString class="group-block-row__input" :form="form" :hide-title="true" :name="'ward_count'"/>
                  <div class="group-block-row__input"></div>
                  <div></div>
                </div>


                <div class="container-lead-form-center">
                  <LeadFormString :form="form" :name="'trainer_count'"/>
                  <LeadFormString :form="form" :name="'attendant_count'"/>
                </div>
            </LeadBlockForm>

            <div style="margin-top: 30px"/>

            <LeadBlockForm label="Выберите локацию и смену">
                <LeadFormDropdownSingle
                  :top="true"
                  :form="form"
                  class="input-field"
                  :name="'region_id'"
                  :options="regions"
                  :identifier="'id'"
                  :show="'name'"
                  :placeholder="'Любой район'"
                  :has-null="true"
                  @change="regionChanged"
                />
                <div class="input-field-50__second"></div>

                <LeadFormDropdownSingle
                    :top="true"
                    class="vsc-services-drop input-field"
                    :form="form"
                    :name="'service_id'"
                    :options="regionServices"
                    :identifier="'id'" :show="'title'"
                    :placeholder="'Выберите услугу'"
                    :disabled="form.values['need_help'] === true"
                    @change="serviceChanged"
                />
                <LeadFormCheckBoxSingle
                    :form="form"
                    :name="'need_help'"
                    :hide-title="true"
                    @change="needHelpChanged"
                />

                <GuiContainer v-if="service_info" service_info>
                    <div class="service_info">
                        <GuiValue :title="'Тренировочная база'">{{ service_info['training_base_title'] }}</GuiValue>
                        <GuiValue :title="'Адрес'">{{ service_info['training_base_address'] }}</GuiValue>
                        <GuiValue :title="'Стоимость услуги руб. /с человека'">{{ service_info['price'] }} руб.</GuiValue>
                        <GuiValue :title="'Дата внесения средств'">{{ service_info['date_deposit_funds'] }}</GuiValue>
                        <GuiValue :title="'Дата внесения аванса'">{{ service_info['date_advance_payment'] }}</GuiValue>
                        <GuiValue :title="'Смена'">{{ service_info['service_start_at'] }} - {{ service_info['service_end_at'] }}</GuiValue>
                        <GuiValue :title="'Описание услуги'">{{ service_info['description'] }}</GuiValue>
                    </div>
                </GuiContainer>

                <FormText :form="form" :placeholder="'Здесь вы можете указать особые условия и пожелания, а также Ваши вопросы'" :name="'client_comments'"/>

                <LeadFormDropdownSingle
                    :top="true"
                    class="vsc-services-drop input-field"
                    :form="form"
                    :name="'client_origin_id'"
                    :options="clientOrigins"
                    :identifier="'id'" :show="'name'"
                    :placeholder="'Выберите источник'"
                />
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
import GuiText from "../../Components/GUI/GuiText.vue";
import GuiValueArea from "../../Components/GUI/GuiValueArea.vue";
import FormText from "../../Components/Form/FormText.vue";
import HeaderGroup from "@/Pages/Leads/Parts/HeaderGroup.vue";
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
        HeaderGroup,
        FormText,
        GuiValueArea, GuiValue, GuiHint, InputCheckbox, FormCheckBox, FormDate, GuiMessage, FormDropdown, GuiButton, GuiContainer, GuiHeading, GuiText, FormString, FormPhone},

    props: {
        crm_url: {type: String, default: null},
        debug: {type: Boolean, default: false},
        session: {type: String, default: null},
        regions: {type: Array, default: () => ([])},
        services: {type: Array, default: () => ([])},
        clientOrigins: {type: Array, default: () => ([])},
    },

    computed: {
        regionServices() {
            return this.services.filter(
                service => this.form.values['region_id'] === null || service.region_id === this.form.values['region_id']
            ).map(
                service => ({id: service['id'], title: service['title'], hint: service['address']})
            );
        },
        queryParams() {
            return this.parseQueryParams(window.location.search.substring(1))
        },
    },

    data: () => ({
        form: form(null, null),
        message: null,
        agreement: false,
        service_info: null,
    }),

    created() {
        this.form.save_url = this.url('/leads-group/send');
        this.form.set('lastname', null, 'required', 'Фамилия', true);
        this.form.set('firstname', null, 'required', 'Имя', true);
        this.form.set('patronymic', null, 'required', 'Отчество', true);
        this.form.set('phone', null, 'required', 'Телефон', true);
        this.form.set('email', null, 'required|email|bail', 'Email', true);

        this.form.set('is_contract_individual', null, null, 'Договор будет заключаться на это имя', true);
        this.form.set('organization_name', null, null, 'Наименование организации', true);

        this.form.set('girls_1_count', null, null, null, true);
        this.form.set('boys_1_count', null, null, null, true);
        this.form.set('girls_2_count', null, null, null, true);
        this.form.set('boys_2_count', null, null, null, true);
        this.form.set('girls_3_count', null, null, null, true);
        this.form.set('boys_3_count', null, null, null, true);
        this.form.set('ward_count', null, 'required', 'Общее количество воспитанников', true);
        this.form.set('trainer_count', 0, 'required', 'Количество тренеров', true);
        this.form.set('attendant_count', 0, 'required', 'Количество сопровождающих', true);

        this.form.set('region_id', null, null, 'Локация', true);

        this.form.set('client_comments', null, null, 'Комментарии или дополнительные пожелания', true);
        this.form.set('need_help', false, null, 'Мне сложно определиться, т.к. очень много вопросов, прошу со мной связаться по указанному номеру', true);
        this.form.set('client_origin_id', null, null, 'Откуда вы о нас узнали?', true);


        let serviceId = null
        if (this.queryParams['service_id']) {
            serviceId = parseInt(this.queryParams['service_id'])
            this.serviceChanged(serviceId)
        }

        this.form.set('service_id', serviceId, 'required_if:need_help,false', 'Услуга', true);

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
                  if (error.response.data['code'] === 422) {
                    for (let key in error.response.data.errors) {
                      this.form.valid[key] = false
                    }
                    this.form.is_valid = false
                    this.form.errors = error.response.data.errors
                  } else {
                    this.message = error.response.data['message'];
                  }
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
            this.setServiceIdInUrl(service_id)
            if (service_id === null) {
                this.service_info = null;
                return;
            }
            this.service_info_loading = true;
            axios.post('/leads-group/info', {service_id: service_id}, {headers: {'X-Vsc-Session': this.session}})
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

        setServiceIdInUrl(serviceId) {
          let uri = window.location.pathname
          let params = this.queryParams
          if (serviceId) {
            params['service_id'] = serviceId
          } else {
            delete params['service_id']
          }
          uri += '?'
          for (let param in params) {
            uri += `${param}=${params[param]}&`
          }
          uri = uri.replace(/&$/, '');

          window.history.pushState({}, '', uri)
        },
        parseQueryParams(queryParams) {
          const paramsObject = {}
          const paramsArray = queryParams.split('&')

          for (const param of paramsArray) {
            const [key, value] = param.split('=')
            if (key && value) {
              paramsObject[key] = decodeURIComponent(value)
            }
          }

          return paramsObject
        }
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

