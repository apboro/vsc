<template>
    <LayoutPage :loading="processing" :title="title"
                :breadcrumbs="[{caption: 'Организации', to: {name: 'organizations-list'}}]"
                :link="{name: 'organizations-list'}"
                :link-title="'К списку организаций'"
    >
        <template v-slot:actions v-if="can(['organizations.edit','organizations.delete'])">
            <GuiActionsMenu>
                <span class="link" v-if="can('organizations.edit')" @click="edit">Редактировать карточку организации</span>
                <span class="link" v-if="can('organizations.delete')" @click="remove">Удалить карточку организации</span>
            </GuiActionsMenu>
        </template>

        <GuiContainer w-50 mt-30>
            <GuiValue :title="'Организация'">{{ data.data['full_name'] }}</GuiValue>
            <GuiValue :title="'Статус'">
                <GuiActivityIndicator :active="data.data['active']"/>{{ data.data['status'] }}
            </GuiValue>
        </GuiContainer>

    </LayoutPage>
</template>

<script>
import data from "@/Core/Data";
import DeleteEntry from "@/Mixins/DeleteEntry";
import LayoutPage from "@/Components/Layout/LayoutPage";
import GuiActionsMenu from "@/Components/GUI/GuiActionsMenu";
import Permissions from "@/Mixins/Permissions";
import GuiContainer from "@/Components/GUI/GuiContainer";
import GuiValue from "@/Components/GUI/GuiValue";
import GuiActivityIndicator from "@/Components/GUI/GuiActivityIndicator";

export default {
    components: {
        GuiActivityIndicator,
        GuiValue,
        GuiContainer,
        LayoutPage,
        GuiActionsMenu,
    },

    mixins: [Permissions, DeleteEntry],

    data: () => ({
        data: data('/api/organizations/view'),
    }),

    computed: {
        organizationId() {
            return Number(this.$route.params.id);
        },
        processing() {
            return this.deleting || this.data.is_loading;
        },
        title() {
            return this.data.is_loaded ? this.data.data['title'] : '...';
        },
    },

    created() {
        this.data.load({id: this.organizationId})
            .catch(response => response.code === 404 && this.$router.replace({name: '404'}));
    },

    methods: {
        edit() {
            this.$router.push({name: 'organizations-edit', params: {id: this.organizationId}});
        },
        remove() {
            const name = this.data.data['title'];
            this.deleteEntry('Удалить организацию "' + name + '"?', '/api/organizations/delete', {id: this.organizationId})
                .then(() => {
                    this.$router.push({name: 'organizations-list'});
                });
        },
    }
}
</script>
