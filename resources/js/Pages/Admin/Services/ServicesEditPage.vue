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
            <FormDictionary :form="form" :name="'requisites_id'" :dictionary="'organization_requisites'"/>
            <FormNumber :form="form" :name="'trainings_per_month'"/>
            <FormNumber :form="form" :name="'trainings_per_week'"/>
            <FormDaysOfWeek :form="form" :name="'schedule_days'"/>
            <FormTime :form="form" :name="'schedule_start_time'"/>
            <FormNumber :form="form" :name="'monthly_price'"/>
            <FormNumber :form="form" :name="'training_price'"/>
            <FormNumber :form="form" :name="'training_return_price'"/>
            <FormNumber :form="form" :name="'training_duration'"/>
            <FormNumber :form="form" :name="'group_limit'"/>
            <FormDate :form="form" :name="'start_at'"/>
            <FormDate :form="form" :name="'end_at'"/>
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
import FormDate from "@/Components/Form/FormDate";
import FormDateTime from "../../../Components/Form/FormDateTime";
import FormDaysOfWeek from "../../../Components/Form/FormDaysOfWeek";
import FormTime from "../../../Components/Form/FormTime";

export default {
    components: {
        FormTime,
        FormDaysOfWeek,
        FormDateTime,
        FormDate,
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
