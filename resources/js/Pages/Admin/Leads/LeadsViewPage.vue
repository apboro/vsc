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

        <LeadInfo :data="data.data"/>

        <FormPopUp :form="registration_form" :title="'Создать клиента'" :save-button-caption="'Создать'" class="registration-form" ref="registration">
            <GuiContainer w-500px>
                <FormString :form="registration_form" :name="'lastname'"/>
                <FormString :form="registration_form" :name="'firstname'"/>
                <FormString :form="registration_form" :name="'patronymic'"/>
                <FormPhone :form="registration_form" :name="'phone'"/>
                <FormString :form="registration_form" :name="'email'"/>
                <div class="mt-10"></div>
                <FormString :form="registration_form" :name="'ward_lastname'"/>
                <FormString :form="registration_form" :name="'ward_firstname'"/>
                <FormString :form="registration_form" :name="'ward_patronymic'"/>
                <FormDate :form="registration_form" :name="'ward_birth_date'"/>
                <div class="mt-10"></div>

                <FormDictionary :form="registration_form" :name="'region_id'" :dictionary="'regions'" :search="true" :top="true" @change="regionChanged"/>
                <FormDropdown :form="registration_form" :name="'service_id'" :options="regionServices" :identifier="'id'" :show="'title'" :search="true" :top="true"/>
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

export default {
    components: {
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
            if(!this.data.payload['services']) return [];
            return this.data.payload['services'].filter(
                service => service.region_id === this.registration_form.values['region_id']
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
            this.registration_form.load();
            this.$refs.registration.show({lead_id: this.leadId})
                .then(() => {
                    this.load();
                });
        },
        regionChanged() {
            this.registration_form.update('service_id', null);
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
        width: 350px;
    }
}
</style>
