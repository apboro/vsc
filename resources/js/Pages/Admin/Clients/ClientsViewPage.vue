<template>
    <LayoutPage :loading="processing" :title="title"
                :breadcrumbs="[{caption: 'Клиенты', to: {name: 'clients-list'}}]"
                :link="{name: 'clients-list'}"
                :link-title="'К списку клиентов'"
    >
        <LayoutRoutedTabs :tabs="tabs" @change="tab = $event"/>

        <ClientInfo v-if="tab === 'general'" :client-id="clientId" :data="data.data" @update="load"/>
        <ClientWardsList v-if="tab === 'wards'" :client-id="clientId" :ready="data.is_loaded"/>
        <div v-if="tab === 'subscriptions'">
            <div v-if="can('subscriptions.create')" style="text-align: right;">
                <GuiActionsMenu>
                    <span class="link" @click="addSubscription">Добавить подписку</span>
                </GuiActionsMenu>
            </div>
            <SubscriptionsList :client-id="clientId" :ready="data.is_loaded" ref="subscriptions"/>
        </div>

        <FormPopUp :form="subscription_form" :title="'Добавить подписку'" :save-button-caption="'Добавить'" class="subscription-form" ref="subscription">
            <GuiContainer w-600px client-subscription-add>
                <FormDictionary :form="subscription_form" :name="'region_id'" :dictionary="'regions'" :search="true" @change="regionChanged" :has-null="true"
                                :placeholder="'Все районы'"/>
                <FormDropdown :form="subscription_form" :name="'object_id'" :options="objects" :identifier="'id'" :show="'name'" :search="true" @change="objectChanged"
                              :has-null="true" :placeholder="'Все объекты'"/>
                <FormDropdown :form="subscription_form" :name="'service_id'" :options="services" :identifier="'id'" :show="'title'" :search="true"/>
                <FormDropdown :form="subscription_form" :name="'ward_id'" :options="clientWards" :identifier="'id'" :show="'name'" :search="true" :has-null="true"
                              :placeholder="'Добавить нового'"/>
                <FormString :form="subscription_form" :name="'ward_lastname'" v-if="subscription_form.values['ward_id'] === null"/>
                <FormString :form="subscription_form" :name="'ward_firstname'" v-if="subscription_form.values['ward_id'] === null"/>
                <FormString :form="subscription_form" :name="'ward_patronymic'" v-if="subscription_form.values['ward_id'] === null"/>
                <FormDate :form="subscription_form" :name="'ward_birth_date'" v-if="subscription_form.values['ward_id'] === null"/>
                <FormText :form="subscription_form" :name="'contract_comment'"/>
                <GuiText mt-15>Ссылка на заполнение договора будет отправлена клиенту на почту.</GuiText>
            </GuiContainer>
        </FormPopUp>
    </LayoutPage>
</template>

<script>
import data from "@/Core/Data";
import LayoutPage from "@/Components/Layout/LayoutPage";
import GuiContainer from "@/Components/GUI/GuiContainer";
import ClientInfo from "@/Pages/Admin/Clients/Parts/ClientInfo";
import LayoutRoutedTabs from "@/Components/Layout/LayoutRoutedTabs";
import SubscriptionsList from "@/Pages/Admin/Subscriptions/Parts/SubscriptionsList";
import ClientWardsList from "@/Pages/Admin/Clients/Parts/ClientWardsList";
import Permissions from "../../../Mixins/Permissions";
import GuiActionsMenu from "../../../Components/GUI/GuiActionsMenu";
import FormPopUp from "../../../Components/FormPopUp";
import form from "../../../Core/Form";
import FormDictionary from "../../../Components/Form/FormDictionary";
import FormDropdown from "../../../Components/Form/FormDropdown";
import FormString from "../../../Components/Form/FormString";
import FormDate from "../../../Components/Form/FormDate";
import FormText from "../../../Components/Form/FormText";
import GuiText from "../../../Components/GUI/GuiText";

export default {
    components: {
        GuiText,
        FormText,
        FormDate,
        FormString,
        FormDropdown,
        FormDictionary,
        FormPopUp,
        GuiActionsMenu,
        ClientWardsList,
        SubscriptionsList,
        LayoutRoutedTabs,
        ClientInfo,
        GuiContainer,
        LayoutPage,
    },

    mixins: [Permissions],

    data: () => ({
        data: data('/api/clients/view'),
        tab: null,
        subscription_form: form('/api/clients/add_subscription/get', '/api/clients/add_subscription/update'),
        objectsCache: [],
    }),

    computed: {
        clientId() {
            return Number(this.$route.params.id);
        },
        processing() {
            return this.data.is_loading || this.subscription_form.is_loading || this.subscription_form.is_saving;
        },
        title() {
            return this.data.is_loaded ? this.data.data['title'] : '...';
        },
        tabs() {
            return {
                general: 'Данные клиента',
                wards: 'Занимающиеся',
                subscriptions: 'Подписки на услуги',
            }
        },
        objects() {
            if (!this.objectsCache) return [];
            return this.objectsCache.filter(
                object => this.subscription_form.values['region_id'] === null || object.region_id === this.subscription_form.values['region_id']
            ).map(
                object => ({id: object['id'], name: object['name'], hint: object['address']})
            );
        },
        services() {
            if (!this.subscription_form.payload['services']) return [];

            return this.subscription_form.payload['services'].filter(service => {
                if(this.subscription_form.values['object_id'] !== null) {
                    return service.training_base_id === this.subscription_form.values['object_id'];
                }
                return this.subscription_form.values['region_id'] === null || service.region_id === this.subscription_form.values['region_id'];
            }).map(
                service => ({id: service['id'], title: service['title'], hint: service['address']})
            );
        },
        clientWards() {
            if (!this.subscription_form.payload['wards']) return [];
            return this.subscription_form.payload['wards'].map(
                ward => ({id: ward['id'], name: ward['name']})
            );
        },
    },

    created() {
        this.load();
        this.subscription_form.toaster = this.$toast;
    },

    methods: {
        load() {
            this.data.load({id: this.clientId})
                .catch(response => response.code === 404 && this.$router.replace({name: '404'}));
        },
        updateObjects() {
            this.$store.dispatch('dictionary/refresh', 'training_bases')
                .then(() => {
                    this.objectsCache = this.$store.getters['dictionary/dictionary']('training_bases');
                });
        },
        regionChanged() {
            this.subscription_form.update('service_id', null);
            this.subscription_form.update('object_id', null);
        },
        objectChanged() {
            this.subscription_form.update('service_id', null);
        },
        addSubscription() {
            this.updateObjects();
            this.subscription_form.load({client_id: this.clientId})
                .then(() => {
                    this.$refs.subscription.show({client_id: this.clientId})
                        .then(() => {
                            this.load();
                            this.$refs.subscriptions.reload();
                        })
                })
        },
    }
}
</script>

<style scoped lang="scss">
.client-subscription-add:deep(.input-field__input) {
    width: 400px;
}
</style>
