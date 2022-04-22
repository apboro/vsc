<template>
    <LayoutPage :loading="processing" :title="title"
                :breadcrumbs="[{caption: 'Объекты', to: {name: 'training-base-list'}}]"
                :link="{name: 'training-base-list'}"
                :link-title="'К списку объектов'"
    >
        <template v-slot:actions v-if="can(['training_base.edit','training_base.delete'])">
            <GuiActionsMenu>
                <span class="link" v-if="can('training_base.edit')" @click="edit">Редактировать объект</span>
                <span class="link" v-if="can('training_base.delete')" @click="remove">Удалить объект</span>
            </GuiActionsMenu>
        </template>

        <LayoutRoutedTabs :tabs="tabs" @change="tab = $event"/>

        <TrainingBaseInfo v-if="tab === 'general'" :data="data.data"/>
        <TrainingBaseContracts v-if="tab === 'contracts' && can(['training_base.contracts.view', 'training_base.contracts.modify'])" :base-id="this.baseId"/>

    </LayoutPage>
</template>

<script>
import data from "@/Core/Data";
import DeleteEntry from "@/Mixins/DeleteEntry";
import LayoutPage from "@/Components/Layout/LayoutPage";
import GuiActionsMenu from "@/Components/GUI/GuiActionsMenu";
import LayoutRoutedTabs from "@/Components/Layout/LayoutRoutedTabs";
import Permissions from "@/Mixins/Permissions";
import TrainingBaseInfo from "@/Pages/Admin/TrainingBase/Parts/TrainingBaseInfo";
import TrainingBaseContracts from "@/Pages/Admin/TrainingBase/Parts/TrainingBaseContracts";

export default {
    components: {
        TrainingBaseContracts,
        TrainingBaseInfo,
        LayoutPage,
        GuiActionsMenu,
        LayoutRoutedTabs,
    },

    mixins: [Permissions, DeleteEntry],

    data: () => ({
        data: data('/api/training-base/view'),
        tab: null,
    }),

    computed: {
        baseId() {
            return Number(this.$route.params.id);
        },
        processing() {
            return this.deleting || this.data.is_loading;
        },
        title() {
            return this.data.is_loaded ? this.data.data['title'] : '...';
        },
        tabs() {
            let tabs = {general: 'Описание'};
            if (this.can(['training_base.contracts.view', 'training_base.contracts.modify'])) tabs['contracts'] = 'Документы';
            return tabs;
        },
    },

    created() {
        this.data.load({id: this.baseId})
            .catch(response => response.code === 404 && this.$router.replace({name: '404'}));
    },

    methods: {
        edit() {
            this.$router.push({name: 'training-base-edit', params: {id: this.baseId}});
        },
        remove() {
            const name = this.data.data['title'];
            this.deleteEntry('Удалить объект "' + name + '"?', '/api/training-base/delete', {id: this.baseId})
                .then(() => {
                    this.$router.push({name: 'training-base-list'});
                });
        },
    }
}
</script>
