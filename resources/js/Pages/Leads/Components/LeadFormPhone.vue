<template>
    <LeadFieldPhone
        :model-value="value"
        :name="name"
        :title="title"
        :hide-title="hideTitle"
        :original="original"
        :valid="valid"
        :errors="errors"
        :required="required"
        :disabled="disabled"
        :placeholder="title"
        :autocomplete="autocomplete"
        :small="small"
        @change="change"
        ref="input"
    />
</template>

<script>
import LeadFormMixin from "@/Pages/Leads/Components/Helpers/LeadFormMixin.vue";
import LeadFieldPhone from "@/Pages/Leads/Components/Fields/LeadFieldPhone.vue";

export default {
    components: {LeadFieldPhone},
    props: {
        disabled: {type: Boolean, default: false},
        small: {type: Boolean, default: false},
        autocomplete: {type: String, default: 'off'},
    },

    mixins: [LeadFormMixin],
}
</script>
