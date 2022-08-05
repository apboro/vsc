<template>
    <LayoutPage :loading="processing" :title="title"
                :breadcrumbs="[{caption: 'Клиенты', to: {name: 'clients-list'}}]"
                :link="{name: 'clients-list'}"
                :link-title="'К списку клиентов'"
    >
        <LayoutRoutedTabs :tabs="tabs" @change="tab = $event"/>

        <ClientInfo v-if="tab === 'general'" :data="data.data"/>
        <SubscriptionsList v-if="tab === 'subscriptions'" :client-id="clientId" :ready="data.is_loaded"/>
    </LayoutPage>
</template>

<script>
import data from "@/Core/Data";
import LayoutPage from "@/Components/Layout/LayoutPage";
import GuiContainer from "@/Components/GUI/GuiContainer";
import ClientInfo from "@/Pages/Admin/Clients/Parts/ClientInfo";
import LayoutRoutedTabs from "@/Components/Layout/LayoutRoutedTabs";
import SubscriptionsList from "@/Pages/Admin/Subscriptions/Parts/SubscriptionsList";

export default {
    components: {
        SubscriptionsList,
        LayoutRoutedTabs,
        ClientInfo,
        GuiContainer,
        LayoutPage,
    },

    data: () => ({
        data: data('/api/clients/view'),
        tab: null,
    }),

    computed: {
        clientId() {
            return Number(this.$route.params.id);
        },
        processing() {
            return this.data.is_loading;
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
        }
    },

    created() {
        this.load();
    },

    methods: {
        load() {
            this.data.load({id: this.clientId})
                .catch(response => response.code === 404 && this.$router.replace({name: '404'}));
        },
    }
}
</script>
