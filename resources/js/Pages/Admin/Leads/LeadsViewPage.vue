<template>
    <LayoutPage :loading="processing" :title="title"
                :breadcrumbs="[{caption: 'Лиды', to: {name: 'leads-list'}}]"
                :link="{name: 'leads-list'}"
                :link-title="'К списку лидов'"
    >
        <template v-slot:actions>
            <GuiActionsMenu v-if="canRegister || canDelete">
                <span class="link" v-if="canRegister" @click="register">Конвертировать лид</span>
                <span class="link" v-if="canDelete" @click="deleteLead">Удалить лид</span>
            </GuiActionsMenu>
        </template>

        <LeadInfo :data="data.data" :lead-id="leadId" @update="load"/>

        <FormPopUp :form="registration_form" :title="'Создать клиента'" :save-button-caption="'Создать'"
                   class="registration-form" ref="registration">
            <div style="width: 1250px">
                <GuiContainer w-50 inline>
                    <FormDictionary :form="registration_form"
                                    :name="'region_id'"
                                    :dictionary="'regions'"
                                    :search="true"
                                    :top="false"
                                    @change="regionChanged"
                                    style="max-width: 400px"
                    />
                </GuiContainer>

                <GuiContainer w-50 inline>
                    <FormDropdown :form="registration_form"
                                  :name="'service_id'"
                                  :options="regionServices"
                                  :identifier="'id'"
                                  :show="'title'"
                                  :search="true"
                                  :top="false"
                                  style="max-width: 400px; padding-left: 15px"
                    />
                </GuiContainer>

                <GuiContainer w-100>
                    <GuiContainer w-50 inline pr-50>
                        <GuiHeading bold my-10>Клиент</GuiHeading>
                        <template v-if="clientDuplicates">
                            <GuiText text-red mb-10>Обнаружены совпадения!</GuiText>
                            <FormDropdown
                                :form="registration_form"
                                name="client_id"
                                :options="clientDuplicates"
                                placeholder="Новый клиент"
                                :has-null="true"
                                identifier="id"
                                show="name"
                                @change="client"
                            />
                        </template>

                        <table style="width: 100%">
                            <tr v-if="client" style="overflow: hidden; height: 42px">
                                <td style="padding-left: 130px; width: auto;">
                                    <GuiText>Данные лида</GuiText>
                                </td>
                                <td v-if="client">
                                    <GuiText>Данные клиента</GuiText>
                                </td>
                                <td v-if="client">
                                    <GuiText>Обновить</GuiText>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <FormString :form="registration_form" :name="'lastname'"/>
                                </td>
                                <td v-if="client">
                                    <GuiText>{{ client.lastname }}</GuiText>
                                </td>
                                <td v-if="client" style="text-align: center">
                                    <FormCheckBox :form="registration_form" :name="'update_client_lastname'" style="max-width: 20px" hide-title/>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <FormString :form="registration_form" :name="'firstname'"/>
                                </td>
                                <td v-if="client">
                                    <GuiText>{{ client.firstname }}</GuiText>
                                </td>
                                <td v-if="client" style="text-align: center">
                                    <FormCheckBox :form="registration_form" :name="'update_client_firstname'" style="max-width: 20px" hide-title/>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <FormString :form="registration_form" :name="'patronymic'"/>
                                </td>
                                <td v-if="client">
                                    <GuiText>{{ client.patronymic }}</GuiText>
                                </td>
                                <td v-if="client" style="text-align: center">
                                    <FormCheckBox :form="registration_form" :name="'update_client_patronymic'" style="max-width: 20px" hide-title/>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <FormPhone :form="registration_form" :name="'phone'"/>
                                </td>
                                <td v-if="client">
                                    <GuiText>{{ client.phone }}</GuiText>
                                </td>
                                <td v-if="client" style="text-align: center">
                                    <FormCheckBox :form="registration_form" :name="'update_client_phone'" style="max-width: 20px" hide-title/>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <FormString :form="registration_form" :name="'email'"/>
                                </td>
                                <td v-if="client">
                                    <GuiText>{{ client.email }}</GuiText>
                                </td>
                                <td v-if="client" style="text-align: center">
                                    <FormCheckBox :form="registration_form" :name="'update_client_email'" style="max-width: 20px" hide-title/>
                                </td>
                            </tr>
                        </table>
                    </GuiContainer>

                    <GuiContainer w-50 inline>
                        <GuiHeading><span class="text__title" style="padding-left: 15px">Занимающийся</span></GuiHeading>
                        <table style="width: 100%; padding-left: 10px">
                            <tr v-if="wardDuplicates">
                                <td colspan="2">
                                    <GuiText><span class="text__warning">Обнаружены совпадения!</span></GuiText>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="32">
                                    <FormDropdown
                                        :form="registration_form"
                                        name="ward_id"
                                        :options="wardDuplicates"
                                        placeholder="Новый занимающийся"
                                        :has-null="true"
                                        identifier="id"
                                        show="name"
                                        @change="ward"
                                    />
                                </td>
                                <td></td>
                            </tr>
                        </table>
                        <table style="width: 50%; padding-left: 10px">
                            <tr v-if="ward" style="overflow: hidden; height: 42px;">
                                <td style="padding-left: 130px">
                                    <GuiText>Данные лида</GuiText>
                                </td>
                                <td v-if="ward">
                                    <GuiText>Данные занимающегося</GuiText>
                                </td>
                                <td v-if="ward">
                                    <GuiText>Обновить</GuiText>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <FormString :form="registration_form" :name="'ward_lastname'"/>
                                </td>
                                <td v-if="ward">
                                    <GuiText>{{ ward.lastname }}</GuiText>
                                </td>
                                <td v-if="ward" style="text-align: center">
                                    <FormCheckBox :form="registration_form" :name="'update_ward_lastname'"
                                                  style="max-width: 20px" hide-title/>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <FormString :form="registration_form" :name="'ward_firstname'"/>
                                </td>
                                <td v-if="ward">
                                    <GuiText>{{ ward.firstname }}</GuiText>
                                </td>
                                <td v-if="ward" style="text-align: center">
                                    <FormCheckBox :form="registration_form" :name="'update_ward_firstname'"
                                                  style="max-width: 20px" hide-title/>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <FormString :form="registration_form" :name="'ward_patronymic'"/>
                                </td>
                                <td v-if="ward">
                                    <GuiText>{{ ward.patronymic }}</GuiText>
                                </td>
                                <td v-if="ward" style="text-align: center">
                                    <FormCheckBox :form="registration_form" :name="'update_ward_patronymic'"
                                                  style="max-width: 20px" hide-title/>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <FormDate :form="registration_form" :name="'ward_birth_date'"/>
                                </td>
                                <td v-if="ward">
                                    <GuiText>{{ ward.birthdate }}</GuiText>
                                </td>
                                <td v-if="ward" style="text-align: center">
                                    <FormCheckBox :form="registration_form" :name="'update_ward_birth_date'"
                                                  style="max-width: 20px" hide-title/>
                                </td>
                            </tr>
                        </table>
                    </GuiContainer>
                </GuiContainer>

                <GuiContainer mt-10>
                    <GuiText><span class="text__title">Комментарий менеджера (отобразится в уведомлении клиенту)</span></GuiText>
                    <FormText :form="registration_form" :name="'contract_comment'" hide-title/>
                </GuiContainer>
            </div>
        </FormPopUp>
    </LayoutPage>
</template>

<script>
import data from "@/Core/Data";
import LayoutPage from "@/Components/Layout/LayoutPage";
import GuiActionsMenu from "@/Components/GUI/GuiActionsMenu";
import LeadInfo from "@/Pages/Admin/Leads/Parts/LeadInfo";
import FormPopUp from "@/Components/FormPopUp";
import form from "@/Core/Form";
import FormString from "@/Components/Form/FormString";
import FormPhone from "@/Components/Form/FormPhone";
import FormDictionary from "@/Components/Form/FormDictionary";
import GuiContainer from "@/Components/GUI/GuiContainer";
import FormDate from "../../../Components/Form/FormDate";
import FormDropdown from "../../../Components/Form/FormDropdown";
import FormText from "../../../Components/Form/FormText";
import FieldDropDown from "@/Components/Fields/FieldDropDown";
import InputString from "@/Components/Inputs/InputString";
import InputPhone from "@/Components/Inputs/InputPhone";
import InputCheckbox from "@/Components/Inputs/InputCheckbox";
import FormCheckBox from "@/Components/Form/FormCheckBox";
import GuiText from "@/Components/GUI/GuiText";
import GuiHeading from "@/Components/GUI/GuiHeading";
import DeleteEntry from "@/Mixins/DeleteEntry";

export default {
    components: {
        GuiHeading,
        GuiText,
        FormCheckBox,
        InputCheckbox,
        InputPhone,
        InputString,
        FieldDropDown,
        FormText,
        FormDropdown,
        FormDate,
        GuiContainer,
        FormDictionary,
        FormPhone,
        FormString,
        FormPopUp,
        LeadInfo,
        LayoutPage,
        GuiActionsMenu,
    },

    mixins: [DeleteEntry],

    data: () => ({
        data: data('/api/leads/view'),
        registration_form: form(null, '/api/leads/register'),
        clientDuplicates: null,
        wardDuplicates: null,
        is_duplicates_loading: false,
        clientCheckbox: {
            lastname: false,
            firstname: false,
            patronymic: false,
            phone: false,
            email: false
        },
        wardCheckbox: {
            lastname: false,
            firstname: false,
            patronymic: false,
            birthdate: false
        },
    }),

    computed: {
        leadId() {
            return Number(this.$route.params.id);
        },
        processing() {
            return this.data.is_loading || this.is_duplicates_loading;
        },
        title() {
            return this.data.is_loaded ? this.data.data['title'] : '...';
        },
        canRegister() {
            return this.data.is_loaded && this.data.data['can_register'];
        },
        canDelete() {
            return this.data.is_loaded && this.data.data['can_delete'];
        },
        regionServices() {
            if (!this.data.payload['services']) return [];
            return this.data.payload['services'].filter(
                service => this.registration_form.values['region_id'] === null || service.region_id === this.registration_form.values['region_id']
            ).map(
                service => ({id: service['id'], title: service['title'], hint: service['address']})
            );
        },
        client() {
            if (this.registration_form.values['client_id'] === null) {
                return null
            }

            return this.clientDuplicates.find(
                value => value.id === this.registration_form.values['client_id']
            )
        },
        ward() {
            if (this.registration_form.values['ward_id'] === null) {
                return null
            }

            return this.wardDuplicates.find(
                value => value.id === this.registration_form.values['ward_id']
            )
        },
    },

    created() {
        this.registration_form.toaster = this.$toast;
        this.load();
    },

    methods: {
        load() {
            this.data.load({id: this.leadId})
                .catch(response => response.code === 404 && this.$router.replace({name: '404'}));
        },
        register() {
            this.registration_form.set('client_id', null, null, 'Клиент', true);
            this.registration_form.set('ward_id', null, null, 'Занимающийся', true);
            this.registration_form.set('lastname', this.data.data['lastname'], 'required', 'Фамилия', true);
            this.registration_form.set('firstname', this.data.data['firstname'], 'required', 'Имя', true);
            this.registration_form.set('patronymic', this.data.data['patronymic'], 'required', 'Отчество', true);
            this.registration_form.set('phone', this.data.data['phone'], 'required', 'Телефон', true);
            this.registration_form.set('email', this.data.data['email'], 'required|email|bail', 'Email', true);
            this.registration_form.set('update_client_lastname', null, null, null, true);
            this.registration_form.set('update_client_firstname', null, null, null, true);
            this.registration_form.set('update_client_patronymic', null, null, null, true);
            this.registration_form.set('update_client_phone', null, null, null, true);
            this.registration_form.set('update_client_email', null, null, null, true);
            this.registration_form.set('ward_lastname', this.data.data['ward_lastname'], 'required', 'Фамилия', true);
            this.registration_form.set('ward_firstname', this.data.data['ward_firstname'], 'required', 'Имя', true);
            this.registration_form.set('ward_patronymic', this.data.data['ward_patronymic'], 'required', 'Отчество', true);
            this.registration_form.set('ward_birth_date', this.data.data['ward_birth_date'], 'required', 'Дата рождения', true);
            this.registration_form.set('update_ward_lastname', null, null, null, true);
            this.registration_form.set('update_ward_firstname', null, null, null, true);
            this.registration_form.set('update_ward_patronymic', null, null, null, true);
            this.registration_form.set('update_ward_birth_date', null, null, null, true);
            this.registration_form.set('region_id', this.data.data['region_id'], 'required', 'Район', true);
            this.registration_form.set('service_id', this.data.data['service_id'], 'required', 'Услуга', true);
            this.registration_form.set('contract_comment', null, null, 'Комментарий клиенту', true);
            this.registration_form.load();
            this.is_duplicates_loading = true;
            this.getDuplicates()
                .then(() => {
                    this.$refs.registration.show({lead_id: this.leadId})
                        .then(() => {
                            this.load();
                        });
                })
                .finally(() => {
                    this.is_duplicates_loading = false;
                })
        },
        regionChanged() {
            this.registration_form.update('service_id', null);
        },
        getDuplicates() {
            const data = {
                lastname: this.data.data['lastname'],
                firstname: this.data.data['firstname'],
                patronymic: this.data.data['patronymic'],
                phone: this.data.data['phone'],
                email: this.data.data['email'],
                ward_lastname: this.data.data['ward_lastname'],
                ward_firstname: this.data.data['ward_firstname'],
                ward_patronymic: this.data.data['ward_patronymic'],
                ward_birth_date: this.data.data['ward_birth_date'],
            }
            return axios.post('/api/leads/find-duplicates', {data: data})
                .then(response => {
                    this.clientDuplicates = response.data.data['clients_info'];
                    this.wardDuplicates = response.data.data['wards_info'];
                    console.log(this.wardDuplicates)
                })
        },
        deleteLead() {
            const leadId = this.leadId;
            this.deleteEntry('Удалить лид №' + leadId + '?', '/api/leads/delete', {lead_id: this.leadId}).then();
        }
    }
}
</script>

<style lang="scss" scoped>

.registration-form {
    td {
        padding: 5px 5px 5px 0;
    }

    :deep(.input-field__title) {
        width: 120px;
    }

    :deep(.input-field__wrapper) {
        width: 200px;
    }
}

.text {
    &__title {
        font-size: 20px;
        line-height: 24px;
        color: #0B68C2;
    }

    &__subtitle {
        font-size: 16px;
        line-height: 20px;
        color: #0B68C2;
    }

    &__warning {
        font-size: 16px !important;
        line-height: 20px !important;
        color: red !important;
    }
}
</style>
