<template>
    <LayoutPage :loading="processing" :title="title"
                :breadcrumbs="[{caption: 'Услуги', to: {name: 'services-list'}}]"
                :link="{name: 'services-list'}"
                :link-title="'К списку услуг'"
    >
        <template v-slot:actions v-if="can(['services.edit','services.delete'])">
            <GuiActionsMenu>
                <span class="link" v-if="can('services.edit')" @click="edit">Редактировать услугу</span>
                <span class="link" v-if="can('services.delete')" @click="remove">Удалить услугу</span>
            </GuiActionsMenu>
        </template>

        <LayoutRoutedTabs :tabs="tabs" @change="tab = $event"/>

        <ServiceInfo v-if="tab === 'general'" :data="data.data"/>

    </LayoutPage>
</template>

<script>
import data from "@/Core/Data";
import DeleteEntry from "@/Mixins/DeleteEntry";
import LayoutPage from "@/Components/Layout/LayoutPage";
import GuiActionsMenu from "@/Components/GUI/GuiActionsMenu";
import LayoutRoutedTabs from "@/Components/Layout/LayoutRoutedTabs";
import Permissions from "@/Mixins/Permissions";
import ServiceInfo from "@/Pages/Admin/Services/Parts/ServiceInfo";

export default {
    components: {
        ServiceInfo,
        LayoutPage,
        GuiActionsMenu,
        LayoutRoutedTabs,
    },

    mixins: [Permissions, DeleteEntry],

    data: () => ({
        data: data('/api/services/view'),
        tab: null,
    }),

    computed: {
        serviceId() {
            return Number(this.$route.params.id);
        },
        processing() {
            return this.deleting || this.data.is_loading;
        },
        title() {
            return this.data.is_loaded ? this.data.data['title'] : '...';
        },
        tabs() {
            // add tabs here
            return {general: 'Описание'};
        },
    },

    created() {
        this.data.load({id: this.serviceId})
            .catch(response => response.code === 404 && this.$router.replace({name: '404'}));
    },

    methods: {
        edit() {
            this.$router.push({name: 'services-edit', params: {id: this.serviceId}});
        },
        remove() {
            const name = this.data.data['title'];
            this.deleteEntry('Удалить услугу "' + name + '"?', '/api/services/delete', {id: this.serviceId})
                .then(() => {
                    this.$router.push({name: 'services-list'});
                });
        },
    }
}
</script>
