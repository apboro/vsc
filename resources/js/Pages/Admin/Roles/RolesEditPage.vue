<template>
    <LayoutPage :loading="processing" :title="form.payload['title']"
                :breadcrumbs="[{caption: 'Роли', to: {name: 'roles-list'}}]"
                :link="{name: 'roles-list'}"
                :link-title="'К списку ролей'"
    >
        <template v-slot:actions v-if="roleId !== 0">
            <GuiActionsMenu>
                <span class="link" @click="edit">Редактировать роль</span>
                <span class="link" @click="remove">Удалить роль</span>
            </GuiActionsMenu>
        </template>

        <GuiContainer mt-30 v-if="!editingMode">
            <GuiValue :title="form.titles['name']">{{ form.values['name'] }}</GuiValue>
            <GuiValue :title="form.titles['description']">{{ form.values['description'] }}</GuiValue>
            <GuiValue :title="form.titles['active']">
                <GuiActivityIndicator :active="form.values['active']"/>
                {{ form.values['active'] ? 'Активная' : 'Неактивная' }}
            </GuiValue>
        </GuiContainer>
        <GuiContainer mt-30 v-else>
            <FormString :form="form" :name="'name'"/>
            <FormString :form="form" :name="'description'"/>
            <FormDropdown :form="form" :name="'active'"
                          :options="[{value: true, name: 'Активная'}, {value: false, name: 'Неактивная'}]"
                          :identifier="'value'"
                          :show="'name'"
            />
        </GuiContainer>

        <GuiContainer mt-30 roles-list v-for="module in modules">
            <GuiHeading mb-10>{{ module['name'] }}</GuiHeading>
            <GuiContainer pl-15>
                <FormCheckBox v-for="permission in module['permissions']" :form="form" :name="permission" :hide-title="true" :disabled="!editingMode"/>
            </GuiContainer>
        </GuiContainer>

        <GuiContainer mt-30 v-if="editingMode">
            <GuiButton :color="'blue'" @click="save">Сохранить</GuiButton>
            <GuiButton @click="cancel">Отмена</GuiButton>
        </GuiContainer>
    </LayoutPage>
</template>

<script>
import form from "@/Core/Form";
import LayoutPage from "@/Components/Layout/LayoutPage";
import GuiContainer from "@/Components/GUI/GuiContainer";
import FormString from "@/Components/Form/FormString";
import FormDropdown from "@/Components/Form/FormDropdown";
import GuiButton from "@/Components/GUI/GuiButton";
import GuiValue from "@/Components/GUI/GuiValue";
import GuiActivityIndicator from "@/Components/GUI/GuiActivityIndicator";
import GuiActionsMenu from "@/Components/GUI/GuiActionsMenu";
import DeleteEntry from "@/Mixins/DeleteEntry";
import GuiHeading from "@/Components/GUI/GuiHeading";
import FormCheckBox from "@/Components/Form/FormCheckBox";

export default {
    mixins: [DeleteEntry],

    components: {
        FormCheckBox,
        GuiHeading,
        GuiActionsMenu,
        GuiActivityIndicator,
        GuiValue,
        GuiButton,
        FormDropdown,
        FormString,
        GuiContainer,
        LayoutPage,
    },

    data: () => ({
        form: form('/api/roles/get', '/api/roles/update'),
        is_editing: false,
    }),

    computed: {
        roleId() {
            return Number(this.$route.params.id);
        },
        processing() {
            return this.form.is_loading || this.form.is_saving;
        },
        editingMode() {
            return this.is_editing || this.roleId === 0;
        },
        modules() {
            if (!this.form.is_loaded || !this.form.payload['modules']) {
                return null;
            }
            let modules = [];
            Object.keys(this.form.payload['modules']).map(key => {
                modules.push({
                    key: key,
                    name: this.form.payload['modules'][key],
                    permissions: Object.keys(this.form.values).filter(name => name.indexOf(`permission.${key}.`) === 0),
                })
            });
            return modules;
        }
    },

    created() {
        this.form.toaster = this.$toast;
        this.form.load({id: this.roleId});
    },

    methods: {
        save() {
            if (!this.form.validate()) {
                return;
            }

            this.form.save({id: this.roleId})
                .then(response => {
                    this.is_editing = false;
                    if (this.roleId === 0) {
                        this.$router.push({name: 'roles-edit', params: {id: response.payload['id']}});
                    }
                })
        },
        cancel() {
            if (this.roleId === 0) {
                this.$router.push({name: 'roles-list'});
            } else {
                this.is_editing = false;
            }
        },
        edit() {
            this.is_editing = true;
        },
        remove() {
            const name = this.form.payload['title'];
            this.deleteEntry('Удалить роль "' + name + '"?', '/api/roles/delete', {id: this.roleId})
                .then(() => {
                    this.$router.push({name: 'roles-list'});
                });
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
