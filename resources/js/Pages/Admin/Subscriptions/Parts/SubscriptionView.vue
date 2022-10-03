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
            <GuiActionsMenu v-if="can('subscriptions.close') && data.data['is_closeable'] || can ('subscriptions.create') && data.data['is_changeable']">
                <span class="link" v-if="can('subscriptions.close') && data.data['is_closeable']" @click="closeSubscription">Закрыть подписку</span>
                <span class="link" v-if="can('subscriptions.create') && data.data['is_changeable']">Заменить подписку</span>
            </GuiActionsMenu>
        </template>
        <LayoutRoutedTabs :tabs="{service: 'Услуга', contracts: 'Документы', /*payments: 'Оплаты'*/}" @change="tab = $event"/>

        <ServiceInfo v-if="tab === 'service' && data.is_loaded" :data="data.data['service']"/>

        <SubscriptionsDocumentsList v-if="tab === 'contracts'" :subscription-id="subscriptionId" :ready="data.is_loaded" @update="load" ref="contracts"/>

    </LayoutPage>
</template>

<script>
import LayoutPage from "@/Components/Layout/LayoutPage";
import data from "@/Core/Data";
import LayoutRoutedTabs from "@/Components/Layout/LayoutRoutedTabs";
import ServiceInfo from "@/Pages/Admin/Services/Parts/ServiceInfo";
import SubscriptionsDocumentsList from "@/Pages/Admin/Subscriptions/Parts/SubscriptionsDocumentsList";
import GuiActionsMenu from "../../../../Components/GUI/GuiActionsMenu";
import Permissions from "../../../../Mixins/Permissions";
import ProcessEntry from "../../../../Mixins/ProcessEntry";

export default {
    props: {
        clientId: {type: Number, default: null},
        subscriptionId: {type: Number, required: true},
    },

    components: {
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
    },

    data: () => ({
        data: data('/api/subscriptions/view'),
        tab: null,
    }),

    created() {
        this.load();
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
    }
}
</script>
