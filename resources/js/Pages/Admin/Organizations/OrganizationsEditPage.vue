<template>
    <LayoutPage :loading="processing" :title="form.payload['title']"
                :breadcrumbs="[{caption: 'Организации', to: {name: 'organizations-list'}}]"
                :link="{name: 'organizations-list'}"
                :link-title="'К списку организаций'"
    >
        <GuiContainer mt-30>
            <FormString :form="form" :name="'title'"/>
            <FormDictionary :form="form" :name="'status_id'" :dictionary="'organization_statuses'" :placeholder="'Выберите статус организации'"/>
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

export default {
    components: {
        GuiButton,
        FormDictionary,
        FormString,
        GuiContainer,
        LayoutPage
    },

    data: () => ({
        form: form('/api/organizations/get', '/api/organizations/update'),
    }),

    computed: {
        organizationId() {
            return Number(this.$route.params.id);
        },
        processing() {
            return this.form.is_loading || this.form.is_saving;
        },
    },

    created() {
        this.form.toaster = this.$toast;
        this.form.load({id: this.organizationId});
    },

    methods: {
        save() {
            if (!this.form.validate()) {
                return;
            }
            this.form.save({id: this.organizationId})
                .then(response => {
                    if (this.organizationId === 0) {
                        this.$router.push({name: 'organizations-view', params: {id: response.payload['id']}});
                    } else {
                        this.$router.push({name: 'organizations-view', params: {id: this.organizationId}});
                    }
                })
        },
        cancel() {
            if (this.organizationId === 0) {
                this.$router.push({name: 'organizations-list'});
            } else {
                this.$router.push({name: 'organizations-view', params: {id: this.organizationId}});
            }
        },
    }
}
</script>
