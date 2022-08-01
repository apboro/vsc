<template>
    <LayoutPage :loading="processing" :title="form.payload['title']"
                :breadcrumbs="[{caption: 'Услуги', to: {name: 'services-list'}}]"
                :link="{name: 'services-list'}"
                :link-title="'К списку услуг'"
    >
        <GuiContainer mt-30>
            <FormString :form="form" :name="'title'"/>
            <FormDictionary :form="form" :name="'status_id'" :dictionary="'service_statuses'"/>
            <FormDictionary :form="form" :name="'training_base_id'" :dictionary="'training_bases'" :search="true"/>
            <FormDictionary :form="form" :name="'sport_kind_id'" :dictionary="'sport_kinds'"/>
            <FormNumber :form="form" :name="'monthly_price'"/>
        </GuiContainer>

        <GuiContainer mt-30>
            <FormText :form="form" :name="'schedule'"/>
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
import FormNumber from "@/Components/Form/FormNumber";
import FormText from "@/Components/Form/FormText";

export default {
    components: {
        FormText,
        FormNumber,
        GuiButton,
        FormDictionary,
        FormString,
        GuiContainer,
        LayoutPage

    },

    data: () => ({
        form: form('/api/services/get', '/api/services/update'),
    }),

    computed: {
        serviceId() {
            return Number(this.$route.params.id);
        },
        processing() {
            return this.form.is_loading || this.form.is_saving;
        },
    },

    created() {
        this.form.toaster = this.$toast;
        this.form.load({id: this.serviceId});
    },

    methods: {
        save() {
            if (!this.form.validate()) {
                return;
            }
            this.form.save({id: this.serviceId})
                .then(response => {
                    if (this.serviceId === 0) {
                        this.$router.push({name: 'services-view', params: {id: response.payload['id']}});
                    } else {
                        this.$router.push({name: 'services-view', params: {id: this.serviceId}});
                    }
                })
        },
        cancel() {
            if (this.serviceId === 0) {
                this.$router.push({name: 'services-list'});
            } else {
                this.$router.push({name: 'services-view', params: {id: this.serviceId}});
            }
        },
    }
}
</script>
