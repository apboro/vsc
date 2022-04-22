<template>
    <LoadingProgress :loading="processing">

        <!--
        <GuiContainer mt-30 roles-list>
            <GuiHeading mb-10>Роли</GuiHeading>
            <GuiContainer pl-15>
                <FormCheckBox v-for="role in rolesList" :form="roles" :name="role" :hide-title="true" :disabled="!roles_editing"/>
            </GuiContainer>
        </GuiContainer>
        <GuiContainer mt-30>
            <GuiButton v-if="!roles_editing" :color="'blue'" @click="editRoles">Редактировать</GuiButton>
            <GuiButton v-if="roles_editing" :color="'blue'" @click="saveRoles">Сохранить</GuiButton>
            <GuiButton v-if="roles_editing" @click="cancelRoles">Отмена</GuiButton>
        </GuiContainer>

        <GuiHeading mt-30 :expandable="true" @expand="expandPermissions">Дополнительные права</GuiHeading>
        <div v-show="permissions_shown">
        -->

        <GuiContainer mt-30 roles-list>
            <GuiHeading mb-10>Права</GuiHeading>

            <GuiContainer mt-30 roles-list v-for="module in modules">
                <GuiHeading mb-10 text-lg>{{ module['name'] }}</GuiHeading>
                <GuiContainer pl-15>
                    <template v-for="permission in module['permissions']">
                        <template v-if="this.roles.payload['permissions'] && this.roles.payload['permissions'].indexOf(String(permission).replace(/^permission\./, '')) !== -1">
                            <FieldCheckBox :name="permission" :hide-title="true" :model-value="true" :disabled="true" :label="permissions.titles[permission]"/>
                        </template>
                        <template v-else>
                            <FormCheckBox :form="permissions" :name="permission" :hide-title="true" :disabled="!permissions_editing"/>
                        </template>
                    </template>
                </GuiContainer>
            </GuiContainer>
            <GuiContainer mt-30>
                <GuiButton v-if="!permissions_editing" :color="'blue'" @click="editPermissions">Редактировать</GuiButton>
                <GuiButton v-if="permissions_editing" :color="'blue'" @click="savePermissions">Сохранить</GuiButton>
                <GuiButton v-if="permissions_editing" @click="cancelPermissions">Отмена</GuiButton>
            </GuiContainer>

        </GuiContainer>
        <!--
        </div>
        -->
    </LoadingProgress>
</template>

<script>
import LoadingProgress from "@/Components/LoadingProgress";
import GuiContainer from "@/Components/GUI/GuiContainer";
import form from "@/Core/Form";
import GuiHeading from "@/Components/GUI/GuiHeading";
import FormCheckBox from "@/Components/Form/FormCheckBox";
import GuiButton from "@/Components/GUI/GuiButton";
import FieldCheckBox from "@/Components/Fields/FieldCheckBox";

export default {
    components: {
        FieldCheckBox,
        GuiButton,
        FormCheckBox,
        GuiHeading,
        LoadingProgress,
        GuiContainer,
    },

    props: {
        ready: {type: Boolean, default: true},
        staffId: {type: Number, required: true},
    },

    data: () => ({
        roles: form('/api/staff/roles/get', '/api/staff/roles/update'),
        permissions: form('/api/staff/permissions/get', '/api/staff/permissions/update'),
        roles_editing: false,
        permissions_editing: false,
        permissions_shown: false,
    }),

    computed: {
        processing() {
            return this.ready && (this.roles.is_loading || this.roles.is_saving || this.permissions.is_loading || this.permissions.is_saving);
        },
        rolesList() {
            return this.roles.is_loaded ? Object.keys(this.roles.values) : null;
        },
        modules() {
            if (!this.permissions.is_loaded || !this.permissions.payload['modules']) {
                return null;
            }
            let modules = [];
            Object.keys(this.permissions.payload['modules']).map(key => {
                modules.push({
                    key: key,
                    name: this.permissions.payload['modules'][key],
                    permissions: Object.keys(this.permissions.values).filter(name => name.indexOf(`permission.${key}.`) === 0),
                })
            });
            return modules;
        }
    },

    created() {
        this.roles.toaster = this.$toast;
        this.permissions.toaster = this.$toast;
        this.roles.load({id: this.staffId});
        this.permissions.load({id: this.staffId});
    },

    methods: {
        editRoles() {
            this.roles_editing = true;
        },
        cancelRoles() {
            this.roles.revert();
            this.roles_editing = false;
        },
        saveRoles() {
            this.roles.save({id: this.staffId})
                .then(() => {
                    this.roles_editing = false;
                })
        },
        expandPermissions(expanded) {
            this.permissions_shown = expanded;
            if (!expanded) {
                this.cancelPermissions();
            }
        },
        editPermissions() {
            this.permissions_editing = true;
        },
        cancelPermissions() {
            this.permissions.revert();
            this.permissions_editing = false;
        },
        savePermissions() {
            this.permissions.save({id: this.staffId})
                .then(() => {
                    this.permissions_editing = false;
                })
        },
    }
}
</script>

<style lang="scss" scoped>
.roles-list:deep(.input-field) {
    padding: 0;
}

.roles-list:deep(.input-field__errors) {
    min-height: 0;
}
</style>
