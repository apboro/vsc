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

        <FormPopUp :form="registration_form" :title="'Создать клиента'" :save-button-caption="'Создать'" class="registration-form" ref="registration">
            <GuiContainer>
                <div style="display: flex">
                    <FormDictionary :form="registration_form" :name="'region_id'" :dictionary="'regions'" :search="true" :top="false" @change="regionChanged"/>
                    <FormDropdown :form="registration_form" :name="'service_id'" :options="regionServices" :identifier="'id'" :show="'title'" :search="true" :top="false"/>
                </div>
            <GuiContainer>
            <GuiContainer>
                <div style="display: flex">
                    <FieldDropDown
                        v-model="scd"
                                  :options="clientDuplicates"
                                  placeholder="Новый клиент"
                                  :has-null="true"
                                  identifier="id"
                                  show="name"
                                  @change="clientDuplicateSelected"
                    />
                </div>
            </GuiContainer>
            </GuiContainer>
<!--                <GuiContainer w-450px mr-20 inline>-->
<!--                    <FormString :form="duplicate_form"  :name="'lastname'"/>-->
<!--                    <FormString :form="duplicate_form" :name="'firstname'"/>-->
<!--                    <FormString :form="duplicate_form" :name="'patronymic'"/>-->
<!--                    <FormPhone :form="duplicate_form" :name="'phone'"/>-->
<!--                    <FormString :form="duplicate_form" :name="'email'"/>-->
<!--                </GuiContainer>-->
                <GuiContainer w-450px mr-20 inline>
                    <FormString :form="registration_form" :name="'lastname'"/>
                    <FormString :form="registration_form" :name="'firstname'"/>
                    <FormString :form="registration_form" :name="'patronymic'"/>
                    <FormPhone :form="registration_form" :name="'phone'"/>
                    <FormString :form="registration_form" :name="'email'"/>
                </GuiContainer>
                <GuiContainer w-450px inline>
                    <FormString :form="registration_form" :name="'ward_lastname'"/>
                    <FormString :form="registration_form" :name="'ward_firstname'"/>
                    <FormString :form="registration_form" :name="'ward_patronymic'"/>
                    <FormDate :form="registration_form" :name="'ward_birth_date'"/>
                    <FormText :form="registration_form" :name="'contract_comment'"/>
                </GuiContainer>
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

export default {
    components: {
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
        clientDuplicates: [
            {id: 1, name: 'test 1'},
            {id: 2, name: 'test 2'},
        ],
        wardDuplicates: [],
        scd: null,
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
                    console.log(333, this.data)
                    this.getDuplicates()
                })
                .catch(response => response.code === 404 && this.$router.replace({name: '404'}));
        },
        register() {
            this.registration_form.set('lastname', this.data.data['lastname'], 'required', 'Фамилия', true);
            this.registration_form.set('firstname', this.data.data['firstname'], 'required', 'Имя', true);
            this.registration_form.set('patronymic', this.data.data['patronymic'], 'required', 'Отчество', true);
            this.registration_form.set('phone', this.data.data['phone'], 'required', 'Телефон', true);
            this.registration_form.set('email', this.data.data['email'], 'required|email|bail', 'Email', true);
            this.registration_form.set('ward_lastname', this.data.data['ward_lastname'], 'required', 'Фамилия занимающегося', true);
            this.registration_form.set('ward_firstname', this.data.data['ward_firstname'], 'required', 'Имя занимающегося', true);
            this.registration_form.set('ward_patronymic', this.data.data['ward_patronymic'], 'required', 'Отчество занимающегося', true);
            this.registration_form.set('ward_birth_date', this.data.data['ward_birth_date'], 'required', 'Дата рождения занимающегося', true);
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
            axios.post('/api/leads/find-duplicates', data)
                .then(response => {
                    console.log(111, response)
                })
        },
        clientDuplicateSelected(id) {
            console.log(id)
        }
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
