<template>
    <LayoutPage :loading="processing" :title="title"
                :breadcrumbs="[{caption: 'Лиды', to: {name: 'leads-list'}}]"
                :link="{name: 'leads-list'}"
                :link-title="'К списку лидов'"
    >
        <template v-slot:actions>
            <GuiActionsMenu v-if="isRegistrable">
                <span class="link" v-if="isRegistrable" @click="register">Создать клиента</span>
            </GuiActionsMenu>
        </template>

        <LeadInfo :data="data.data" :lead-id="leadId" @update="load"/>

        <FormPopUp :form="registration_form" :title="'Создать клиента'" :save-button-caption="'Создать'"
                   class="registration-form" ref="registration">
            <GuiContainer>
                <div style="display: flex">
                    <FormDictionary :form="registration_form" :name="'region_id'" :dictionary="'regions'" :search="true"
                                    :top="false" @change="regionChanged"/>
                    <FormDropdown :form="registration_form" :name="'service_id'" :options="regionServices"
                                  :identifier="'id'" :show="'title'" :search="true" :top="false"/>
                </div>
                <GuiContainer>
                    <div style="display: flex">
                        <FormDropdown
                            :form="registration_form"
                            name="client_id"
                            :options="clientDuplicates"
                            placeholder="Новый клиент"
                            :has-null="true"
                            identifier="id"
                            show="name"
                            @change="clientDuplicateSelected"
                        />
                        <FormDropdown
                            :form="registration_form"
                            name="ward_id"
                            v-model="scd2"
                            :options="wardDuplicates"
                            placeholder="Новый занимающийся"
                            :has-null="true"
                            identifier="id"
                            show="fullName"
                            @change="clientDuplicateSelected"
                        />
                    </div>
                </GuiContainer>
                <div style="display: flex; align-items: flex-start">
                    <table style="width: 50%; margin-right: 20px">
                        <tr>
                            <td></td>
                            <td>Данные лида</td>
                            <td v-if="registration_form.values['client_id'] !== null">Данные клиента</td>
                            <td v-if="registration_form.values['client_id'] !== null">Обновить</td>
                        </tr>
                        <tr>
                            <td>Фамилия</td>
                            <td><FormString :form="registration_form" :name="'lastname'" hide-title/></td>
                            <td v-if="registration_form.values['client_id'] !== null"><FormString :form="registration_form" :name="'duplicate_client_lastname'" v-model="client.lastname" hide-title/></td>
                            <td v-if="registration_form.values['client_id'] !== null"><FormCheckBox :form="registration_form" :name="'update_client_lastname'" v-model="clientCheckbox.lastname" hide-title/></td>
                        </tr>
                        <tr>
                            <td>Имя</td>
                            <td><FormString :form="registration_form" :name="'firstname'" hide-title/></td>
                            <td v-if="registration_form.values['client_id'] !== null"><FormString :form="registration_form" :name="'duplicate_client_firstname'" v-model="client.firstname" hide-title/></td>
                            <td v-if="registration_form.values['client_id'] !== null"><FormCheckBox :form="registration_form" :name="'update_client_firstname'" v-model="clientCheckbox.firstname" hide-title/></td>
                        </tr>
                        <tr>
                            <td>Отчество</td>
                            <td><FormString :form="registration_form" :name="'patronymic'" hide-title/></td>
                            <td v-if="registration_form.values['client_id'] !== null"><FormString :form="registration_form" :name="'duplicate_client_patronymic'" v-model="client.patronymic" hide-title/></td>
                            <td v-if="registration_form.values['client_id'] !== null"><FormCheckBox :form="registration_form" :name="'update_client_patronymic'" v-model="clientCheckbox.patronymic" hide-title/></td>
                        </tr>
                        <tr>
                            <td>Телефон</td>
                            <td><FormPhone :form="registration_form" :name="'phone'" hide-title/></td>
                            <td v-if="registration_form.values['client_id'] !== null"><FormPhone :form="registration_form" :name="'duplicate_client_phone'" v-model="client.phone" hide-title/></td>
                            <td v-if="registration_form.values['client_id'] !== null"><FormCheckBox :form="registration_form" :name="'update_client_phone'" v-model="clientCheckbox.phone" hide-title/></td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td><FormString :form="registration_form" :name="'email'" hide-title/></td>
                            <td v-if="registration_form.values['client_id'] !== null"><FormString :form="registration_form" :name="'duplicate_client_email'" v-model="client.email" hide-title/></td>
                            <td v-if="registration_form.values['client_id'] !== null"><FormCheckBox :form="registration_form" :name="'update_client_email'" v-model="clientCheckbox.email" hide-title/></td>
                        </tr>
                    </table>
                    <table style="width: 50%">
                        <tr>
                            <td></td>
                            <td>Данные лида</td>
                            <td v-if="registration_form.values['ward_id'] !== null">Данные занимающегося</td>
                            <td v-if="registration_form.values['ward_id'] !== null">Обновить</td>
                        </tr>
                        <tr>
                            <td>Фамилия</td>
                            <td><FormString :form="registration_form" :name="'ward_lastname'" hide-title/></td>
                            <td v-if="registration_form.values['ward_id'] !== null"><FormString :form="registration_form" :name="'duplicate_ward_lastname'" v-model="ward.lastname" hide-title/></td>
                            <td v-if="registration_form.values['ward_id'] !== null"><FormCheckBox :form="registration_form" :name="'update_ward_lastname'" v-model="wardCheckbox.lastname" hide-title/></td>
                        </tr>
                        <tr>
                            <td>Имя</td>
                            <td><FormString :form="registration_form" :name="'ward_firstname'" hide-title/></td>
                            <td v-if="registration_form.values['ward_id'] !== null"><FormString :form="registration_form" :name="'duplicate_ward_firstname'" v-model="ward.firstname" hide-title/></td>
                            <td v-if="registration_form.values['ward_id'] !== null"><FormCheckBox :form="registration_form" :name="'update_ward_firstname'" v-model="wardCheckbox.firstname" hide-title/></td>
                        </tr>
                        <tr>
                            <td>Отчество</td>
                            <td><FormString :form="registration_form" :name="'ward_patronymic'" hide-title/></td>
                            <td v-if="registration_form.values['ward_id'] !== null"><FormString :form="registration_form" :name="'duplicate_ward_patronymic'" v-model="ward.patronymic" hide-title/></td>
                            <td v-if="registration_form.values['ward_id'] !== null"><FormCheckBox :form="registration_form" :name="'update_ward_patronymic'" v-model="wardCheckbox.patronymic" hide-title/></td>
                        </tr>
                        <tr>
                            <td>Дата рождения</td>
                            <td><FormDate :form="registration_form" :name="'ward_birth_date'" hide-title/></td>
                            <td v-if="registration_form.values['ward_id'] !== null"><FormDate :form="registration_form" :name="'duplicate_ward_birth_date'" v-model="ward.birthday" hide-title/></td>
                            <td v-if="registration_form.values['ward_id'] !== null"><FormCheckBox :form="registration_form" :name="'update_ward_birth_date'" v-model="wardCheckbox.birthday" hide-title/></td>
                        </tr>
                    </table>
                </div>
                <FormText :form="registration_form" :name="'contract_comment'"/>
            </GuiContainer>
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

export default {
    components: {
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

    data: () => ({
        data: data('/api/leads/view'),
        registration_form: form(null, '/api/leads/register'),
        clientDuplicates: null,
        wardDuplicates: null,
        scd: null,
        scd2: null,
        client: {
            lastname: '',
            firstname: '',
            patronymic: '',
            phone: '',
            email: ''
        },
        ward: {
            lastname: '',
            firstname: '',
            patronymic: '',
            birthday: '',
        },
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
            birthday: false
        },
    }),

    computed: {
        leadId() {
            return Number(this.$route.params.id);
        },
        processing() {
            return this.data.is_loading;
        },
        title() {
            return this.data.is_loaded ? this.data.data['title'] : '...';
        },
        isRegistrable() {
            return this.data.is_loaded && this.data.data['is_registrable'];
        },
        regionServices() {
            if (!this.data.payload['services']) return [];
            return this.data.payload['services'].filter(
                service => this.registration_form.values['region_id'] === null || service.region_id === this.registration_form.values['region_id']
            ).map(
                service => ({id: service['id'], title: service['title'], hint: service['address']})
            );
        },
    },

    created() {
        this.registration_form.toaster = this.$toast;
        this.load();
    },

    methods: {
        load() {
            this.data.load({id: this.leadId})
                .then(() => {
                    this.getDuplicates()
                })
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
            this.registration_form.set('duplicate_client_lastname', null, null, 'фамилия дубля клиента', true);
            this.registration_form.set('duplicate_client_firstname', null, null, 'Имя дубля клиента', true);
            this.registration_form.set('duplicate_client_patronymic', null, null, 'Отчество дубля клиента', true);
            this.registration_form.set('duplicate_client_phone', null, null, 'Телефон дубля клиента', true);
            this.registration_form.set('duplicate_client_email', null, null, 'Email дубля клиента', true);
            this.registration_form.set('update_client_lastname', null, null, null, true);
            this.registration_form.set('update_client_firstname', null, null, null, true);
            this.registration_form.set('update_client_patronymic', null, null, null, true);
            this.registration_form.set('update_client_phone', null, null, null, true);
            this.registration_form.set('update_client_email', null, null, null, true);
            this.registration_form.set('ward_lastname', this.data.data['ward_lastname'], 'required', 'Фамилия занимающегося', true);
            this.registration_form.set('ward_firstname', this.data.data['ward_firstname'], 'required', 'Имя занимающегося', true);
            this.registration_form.set('ward_patronymic', this.data.data['ward_patronymic'], 'required', 'Отчество занимающегося', true);
            this.registration_form.set('ward_birth_date', this.data.data['ward_birth_date'], 'required', 'Дата рождения занимающегося', true);
            this.registration_form.set('duplicate_ward_lastname', null, null, 'фамилия дубля занимающегося', true);
            this.registration_form.set('duplicate_ward_firstname', null, null, 'Имя дубля занимающегося', true);
            this.registration_form.set('duplicate_ward_patronymic', null, null, 'Отчество дубля занимающегося', true);
            this.registration_form.set('duplicate_ward_birth_date', null, null, 'День рождения дубля занимающегося', true);
            this.registration_form.set('update_ward_lastname', null, null, null, true);
            this.registration_form.set('update_ward_firstname', null, null, null, true);
            this.registration_form.set('update_ward_patronymic', null, null, null, true);
            this.registration_form.set('update_ward_birth_date', null, null, null, true);
            this.registration_form.set('region_id', this.data.data['region_id'], 'required', 'Район', true);
            this.registration_form.set('service_id', this.data.data['service_id'], 'required', 'Услуга', true);
            this.registration_form.set('contract_comment', null, null, 'Комментарий клиенту', true);
            this.registration_form.load();
            this.$refs.registration.show({lead_id: this.leadId})
                .then(() => {
                    this.load();
                });
        },
        regionChanged() {
            this.registration_form.update('service_id', null);
        },
        getDuplicates() {
            const data = {
                data: {
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
            }
            axios.post('/api/leads/find-duplicates', data)
                .then(response => {
                    this.clientDuplicates = response.data.data.result.clients_info;
                    this.wardDuplicates = response.data.data.result.wards_info;
                    console.log(this.wardDuplicates)
                })
        },
        clientDuplicateSelected(id) {
            this.client = this.clientDuplicates.find(
                value => value.id === id
            );
        },
        wardDuplicateSelected(id) {
            this.ward = this.clientDuplicates.find(
                value => value.user_id === id
            );
        },
    }
}
</script>

<style lang="scss" scoped>
.registration-form {
    :deep(.input-field__title) {
        width: 150px;
    }

    :deep(.input-field__wrapper) {
        width: 300px;
    }
}
</style>
