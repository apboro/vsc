<template>
    <LayoutPage :loading="processing" :title="title"
                :breadcrumbs="[{caption: 'Сотрудники', to: {name: 'staff-list'}}]"
                :link="{name: 'staff-list'}"
                :link-title="'К списку сотрудников'"
    >
        <template v-slot:actions v-if="can(['staff.edit','staff.delete'])">
            <GuiActionsMenu>
                <span class="link" v-if="can('staff.edit')" @click="edit">Редактировать карточку сотрудника</span>
                <span class="link" v-if="can('staff.delete')" @click="remove">Удалить карточку сотрудника</span>
            </GuiActionsMenu>
        </template>

        <LayoutRoutedTabs :tabs="tabs" @change="tab = $event"/>

        <StaffInfo v-if="tab === 'personal'" :data="data.data"/>
        <StaffAccess v-if="tab === 'access' && can('staff.access')" :data="data.data" :staff-id="staffId" :editable="true" @update="update"/>
        <StaffPermissions v-if="tab === 'permissions' && can('staff.permissions')" :data="data.data" :staff-id="staffId" :ready="!processing" @update="update"/>

    </LayoutPage>
</template>

<script>
import data from "@/Core/Data";
import DeleteEntry from "@/Mixins/DeleteEntry";
import LayoutPage from "@/Components/Layout/LayoutPage";
import GuiActionsMenu from "@/Components/GUI/GuiActionsMenu";
import LayoutRoutedTabs from "@/Components/Layout/LayoutRoutedTabs";
import StaffInfo from "@/Pages/Admin/Staff/Parts/StaffInfo";
import StaffAccess from "@/Pages/Admin/Staff/Parts/StaffAccess";
import StaffPermissions from "@/Pages/Admin/Staff/Parts/StaffPermissions";
import Permissions from "@/Mixins/Permissions";

export default {
    components: {
        StaffPermissions,
        StaffAccess,
        StaffInfo,
        LayoutPage,
        GuiActionsMenu,
        LayoutRoutedTabs,
    },

    mixins: [Permissions, DeleteEntry],

    data: () => ({
        data: data('/api/staff/view'),
        tab: null,
    }),

    computed: {
        staffId() {
            return Number(this.$route.params.id);
        },
        processing() {
            return this.deleting || this.data.is_loading;
        },
        title() {
            return this.data.is_loaded ? this.data.data['full_name'] : '...';
        },
        tabs() {
            let tabs = {personal: 'Персональные данные'};
            if (this.can('staff.access')) tabs['access'] = 'Доступ';
            if (this.can('staff.permissions')) tabs['permissions'] = 'Права';//'Роли и права';
            return tabs;
        },
    },

    created() {
        this.data.load({id: this.staffId})
            .catch(response => response.code === 404 && this.$router.replace({name: '404'}));
    },

    methods: {
        edit() {
            this.$router.push({name: 'staff-edit', params: {id: this.staffId}});
        },
        remove() {
            const name = this.data.data['full_name'];
            this.deleteEntry('Удалить сотрудника "' + name + '"?', '/api/staff/delete', {id: this.staffId})
                .then(() => {
                    this.$router.push({name: 'staff-list'});
                });
        },
        update(payload) {
            Object.keys(payload).map(key => {
                this.data.data[key] = payload[key];
            })
        },
    }
}
</script>
