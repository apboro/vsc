<template>
    <LayoutPage :loading="processing" :title="title"
                :breadcrumbs="[{caption: 'Клиенты', to: {name: 'clients-list'}}]"
                :link="{name: 'clients-list'}"
                :link-title="'К списку клиентов'"
    >
        <ClientInfo :data="data.data"/>
    </LayoutPage>
</template>

<script>
import data from "@/Core/Data";
import LayoutPage from "@/Components/Layout/LayoutPage";
import GuiContainer from "@/Components/GUI/GuiContainer";
import ClientInfo from "@/Pages/Admin/Clients/Parts/ClientInfo";

export default {
    components: {
        ClientInfo,
        GuiContainer,
        LayoutPage,
    },

    data: () => ({
        data: data('/api/clients/view'),
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
