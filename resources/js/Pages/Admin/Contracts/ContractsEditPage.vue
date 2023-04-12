<template>
    <LayoutPage :loading="processing" :title="form.payload['title']"
                :breadcrumbs="[{caption: 'Договоры', to: {name: 'organizations-list'}}]"
                :link="{name: 'organizations-list'}"
                :link-title="'К списку договоров'"
    >
        <GuiContainer mt-30>
            <FormString :form="form" :name="'name'"/>
            <FormDictionary :form="form" :name="'pattern_id'" :dictionary="'patterns'" :placeholder="'Выберите шаблон'"/>
            <FormDropdown :form="form" :name="'organization_id'" :identifier="'id'" :show="'title'" :options="organizations" :placeholder="'Выберите организацию'"/>
        </GuiContainer>

        <GuiContainer mt-30>
            <GuiButton @click="save" :color="'blue'">Сохранить</GuiButton>
            <GuiButton @click="cancel">Отмена</GuiButton>
        </GuiContainer>
    </LayoutPage>
</template>

<script>

import LayoutPage from "@/Components/Layout/LayoutPage";
import GuiContainer from "@/Components/GUI/GuiContainer";
import form from "@/Core/Form";
import FormString from "@/Components/Form/FormString";
import FormDictionary from "@/Components/Form/FormDictionary";
import GuiButton from "@/Components/GUI/GuiButton";
import FormDropdown from "@/Components/Form/FormDropdown.vue";
import InputDropDown from "@/Components/Inputs/InputDropDown.vue";
import empty from "@/Core/Helpers/Empty";

export default {
    components: {
        InputDropDown,
        FormDropdown,
        GuiButton,
        FormDictionary,
        FormString,
        GuiContainer,
        LayoutPage
    },

    data: () => ({
        organizations: [],
        form: form('/api/contracts/get', '/api/contracts/update'),
    }),

    computed: {
        contractId() {
            return Number(this.$route.params.id);
        },
        processing() {
            return this.form.is_loading || this.form.is_saving;
        },
    },

    created() {
        axios.post('/api/organizations/list', {})
            .then(response => {
                this.organizations = response.data.data['organizations'];
            })
            .catch(error => {
                this.$toast.error(error.response.data['message']);
            })
        this.form.toaster = this.$toast;
        this.form.load({id: this.contractId});
    },

    methods: {
        save() {
            if (!this.form.validate()) {
                return;
            }
            this.form.save({id: this.contractId})
                .then(response => {
                    if (this.contractId === 0) {
                        this.$router.push({name: 'contracts-view', params: {id: response.payload['id']}});
                    } else {
                        this.$router.push({name: 'contracts-view', params: {id: this.contractId}});
                    }
                })
        },
        cancel() {
            if (this.contractId === 0) {
                this.$router.push({name: 'contracts-list'});
            } else {
                this.$router.push({name: 'contracts-view', params: {id: this.contractId}});
            }
        },
    }
}
</script>
