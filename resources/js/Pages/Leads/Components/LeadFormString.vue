<template>
    <LeadFieldString
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
        :type="type"
        :autocomplete="autocomplete"
        :small="small"
        @change="change"
        ref="input"
    />
</template>


<script>
import LeadFormMixin from "@/Pages/Leads/Components/Helpers/LeadFormMixin.vue";
import LeadFieldString from "@/Pages/Leads/Components/Fields/LeadFieldString.vue";

export default {
    components: {LeadFieldString},
    props: {
        disabled: {type: Boolean, default: false},
        small: {type: Boolean, default: false},
        type: {type: String, default: 'text'},
        autocomplete: {type: String, default: 'off'},
    },

    mixins: [LeadFormMixin],
}
</script>
