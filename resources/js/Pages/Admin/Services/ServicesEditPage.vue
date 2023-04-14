<template>
    <LayoutPage :loading="processing" :title="form.payload['title']"
                :breadcrumbs="[{caption: 'Услуги', to: {name: 'services-list'}}]"
                :link="{name: 'services-list'}"
                :link-title="'К списку услуг'"
    >
        <GuiContainer mt-30>
            <FormString :form="form" :name="'title'"/>
            <FormDictionary :form="form" :name="'status_id'" :dictionary="'service_statuses'"/>
            <FormDictionary :form="form" :name="'type_program_id'" :dictionary="'service_programs'"/>
            <FormDictionary :form="form" :name="'training_base_id'" :dictionary="'training_bases'" :search="true"/>
            <FormDate :form="form" :name="'start_at'"/>
            <FormDate :form="form" :name="'end_at'"/>
        </GuiContainer>

        <GuiContainer mt-30 v-if="regularTypeProgram.includes(form.values['type_program_id'])">
            <FormNumber :form="form" :name="'training_duration'"/>
            <FormNumber :form="form" :name="'trainings_per_month'"/>
            <FormNumber :form="form" :name="'trainings_per_week'"/>

            <FormCheckBox :form="form" :name="'schedule_day_mon'" :without-title="true"/>
            <FormTime :form="form" :name="'schedule_time_mon'"/>

            <FormCheckBox :form="form" :name="'schedule_day_tue'" :without-title="true"/>
            <FormTime :form="form" :name="'schedule_time_tue'"/>

            <FormCheckBox :form="form" :name="'schedule_day_wed'" :without-title="true"/>
            <FormTime :form="form" :name="'schedule_time_wed'"/>

            <FormCheckBox :form="form" :name="'schedule_day_thu'" :without-title="true"/>
            <FormTime :form="form" :name="'schedule_time_thu'"/>

            <FormCheckBox :form="form" :name="'schedule_day_fri'" :without-title="true"/>
            <FormTime :form="form" :name="'schedule_time_fri'"/>

            <FormCheckBox :form="form" :name="'schedule_day_sat'" :without-title="true"/>
            <FormTime :form="form" :name="'schedule_time_sat'"/>

            <FormCheckBox :form="form" :name="'schedule_day_sun'" :without-title="true"/>
            <FormTime :form="form" :name="'schedule_time_sun'"/>
        </GuiContainer>

        <GuiContainer mt-30>
            <FormNumber :form="form" :name="'group_limit'"/>
            <FormDictionary :form="form" :name="'sport_kind_id'" :dictionary="'sport_kinds'"/>
        </GuiContainer>

        <GuiContainer mt-30 v-if="regularTypeProgram.includes(form.values['type_program_id'])">
            <FormNumber :form="form" :name="'monthly_price'"/>
            <FormNumber :form="form" :name="'training_price'"/>
            <FormNumber :form="form" :name="'training_return_price'"/>
        </GuiContainer>

        <GuiContainer mt-30 v-if="singleTypeProgram.includes(form.values['type_program_id'])">
            <FormNumber :form="form" :name="'price'"/>
            <FormDate :form="form" :name="'date_deposit_funds'"/>
            <FormNumber :form="form" :name="'advance_payment'"/>
            <FormDate :form="form" :name="'date_advance_payment'"/>
            <FormNumber :form="form" :name="'refund_amount'"/>
            <FormNumber :form="form" :name="'daily_price'"/>
            <FormNumber :form="form" :name="'price_deduction_advance'"/>
        </GuiContainer>


        <GuiContainer mt-30>
            <FormDictionary :form="form" :name="'requisites_id'" :dictionary="'organization_requisites'"/>
            <FormDictionary :form="form" :name="'contract_id'" :dictionary="'contracts'"/>
            <FormDictionary :form="form" :name="'letter_id'" :dictionary="'letters'"/>
            <FormText :form="form" :name="'description'"/>
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
import FormTime from "../../../Components/Form/FormTime";
import FormCheckBox from "../../../Components/Form/FormCheckBox";
import FieldText from "@/Components/Fields/FieldText.vue";

export default {
    components: {
        FieldText,
        FormCheckBox,
        FormTime,
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
        regularTypeProgram: [],
        singleTypeProgram: [],
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
        this.typePrograms();
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
        typePrograms() {
            axios.get('/api/services/type-programs')
                .then(response => {
                    this.regularTypeProgram = response.data.data['regulars'];
                    this.singleTypeProgram = response.data.data['singleType'];
                });
        }
    }
}
</script>
