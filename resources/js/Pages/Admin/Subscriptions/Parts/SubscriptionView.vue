<template>
    <LayoutPage :title="data.data['title']"
                :breadcrumbs="breadcrumbs"
                :link="{name: backLink}"
                :link-title="backLinkTitle"
                :loading="data.is_loading"
                :title-new-line="true"
    >
        <template #comments v-if="data.is_loaded">
            <div class="inline mr-20"><b>статус</b>: {{ data.data['status'] }}</div>
            <div class="inline mr-20"><b>клиент</b>:
                <RouterLink class="link" :to="{name: 'clients-view', params: {id: data.data['client_id']}}">{{ data.data['client'] }}</RouterLink>
            </div>
        </template>
        <template #actions>
            <GuiActionsMenu v-if="can('subscriptions.close') && data.data['is_closeable'] || can('subscriptions.change') && data.data['is_changeable']">
                <span class="link" v-if="can('subscriptions.close') && data.data['is_closeable']" @click="closeSubscription">Закрыть подписку</span>
                <span class="link" v-if="can('subscriptions.change') && data.data['is_changeable']" @click="changeSubscription">Заменить подписку</span>
            </GuiActionsMenu>
        </template>
        <LayoutRoutedTabs :tabs="{service: 'Услуга', contracts: 'Документы', /*payments: 'Оплаты'*/}" @change="tab = $event"/>

        <ServiceInfo v-if="tab === 'service' && data.is_loaded" :data="data.data['service']"/>

        <SubscriptionsDocumentsList v-if="tab === 'contracts'" :subscription-id="subscriptionId" :subscription-repeatable="data.data['is_repeatable']" :ready="data.is_loaded" @update="load" ref="contracts"/>

        <FormPopUp :form="subscription_form" :title="'Заменить подписку'" :save-button-caption="'Заменить'" class="subscription-form" ref="subscription">
            <GuiContainer w-600px subscription-change>
                <FormDictionary :form="subscription_form" :name="'region_id'" :dictionary="'regions'" :search="true" @change="regionChanged" :has-null="true"
                                :placeholder="'Все районы'"/>
                <FormDropdown :form="subscription_form" :name="'object_id'" :options="objects" :identifier="'id'" :show="'name'" :search="true" @change="objectChanged"
                              :has-null="true" :placeholder="'Все объекты'"/>
                <FormDropdown :form="subscription_form" :name="'service_id'" :options="services" :identifier="'id'" :show="'title'" :search="true"/>
                <FormText :form="subscription_form" :name="'contract_comment'"/>
                <GuiText mt-15>Данная подписка будет закрыта, ссылка на заполнение договора будет отправлена клиенту на почту.</GuiText>
            </GuiContainer>
        </FormPopUp>
    </LayoutPage>
</template>

<script>
import LayoutPage from "@/Components/Layout/LayoutPage";
import data from "@/Core/Data";
import LayoutRoutedTabs from "@/Components/Layout/LayoutRoutedTabs";
import ServiceInfo from "@/Pages/Admin/Services/Parts/ServiceInfo";
import SubscriptionsDocumentsList from "@/Pages/Admin/Subscriptions/Parts/SubscriptionsDocumentsList";
import GuiActionsMenu from "@/Components/GUI/GuiActionsMenu";
import Permissions from "@/Mixins/Permissions";
import ProcessEntry from "@/Mixins/ProcessEntry";
import FormPopUp from "@/Components/FormPopUp";
import GuiContainer from "@/Components/GUI/GuiContainer";
import FormDictionary from "@/Components/Form/FormDictionary";
import FormDropdown from "@/Components/Form/FormDropdown";
import FormText from "@/Components/Form/FormText";
import form from "../../../../Core/Form";
import GuiHint from "../../../../Components/GUI/GuiHint";
import GuiText from "../../../../Components/GUI/GuiText";

export default {
    props: {
        clientId: {type: Number, default: null},
        subscriptionId: {type: Number, required: true},
    },

    components: {
        GuiText,
        GuiHint,
        FormText,
        FormDropdown,
        FormDictionary,
        GuiContainer,
        FormPopUp,
        GuiActionsMenu,
        SubscriptionsDocumentsList,
        ServiceInfo,
        LayoutRoutedTabs,
        LayoutPage,
    },

    mixins: [Permissions, ProcessEntry],

    computed: {
        breadcrumbs() {
            if (this.clientId === null) {
                return [{caption: 'Подписки на услуги', to: {name: 'subscriptions-list'}}];
            }

            return [
                {caption: 'Клиенты', to: {name: 'clients-list'}},
                {caption: this.data.data['client'], to: {name: 'clients-view', params: {id: this.clientId}}},
            ];
        },
        backLink() {
            return this.clientId === null ? 'subscriptions-list' : 'clients-list';
        },
        backLinkTitle() {
            return this.clientId === null ? 'К списку подписок' : 'К списку клиентов';
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
    },

    data: () => ({
        data: data('/api/subscriptions/view'),
        tab: null,
        subscription_form: form('/api/subscriptions/change/get', '/api/subscriptions/change/update'),
        objectsCache: [],
    }),

    created() {
        this.load();
        this.subscription_form.toaster = this.$toast;
    },

    methods: {
        load() {
            this.data.load({id: this.subscriptionId, client_id: this.clientId});
        },
        closeSubscription() {
            this.processEntry('Закрыть подписку и все действующие договоры?', 'Закрыть подписку', '/api/subscriptions/close', {
                subscription_id: this.subscriptionId
            })
                .then(() => {
                    this.load();
                    if (this.tab === 'contracts') {
                        this.$refs.contracts.reload();
                    }
                });
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
        changeSubscription() {
            this.updateObjects();
            this.subscription_form.load({subscription_id: this.subscriptionId})
                .then(() => {
                    this.$refs.subscription.show({subscription_id: this.subscriptionId})
                        .then(() => {
                            this.load();
                            if (this.tab === 'contracts') {
                                this.$refs.contracts.reload();
                            }
                        });
                });
        },
    }
}
</script>

<style scoped lang="scss">
.subscription-change:deep(.input-field__input) {
    width: 400px;
}
</style>
