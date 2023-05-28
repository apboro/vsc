<template>
    <LeadFieldCheckBox
        :model-value="value"
        :name="name"
        :title="withoutTitle ? null : title"
        :hide-title="hideTitle"
        :original="original"
        :valid="valid"
        :errors="errors"
        :required="required"
        :disabled="disabled"
        :value="val"
        :label="title"
        @change="change"
        ref="input"
    />
</template>

<script>
import LeadFormMixin from "@/Pages/Leads/Components/Helpers/LeadFormMixin.vue";
import LeadFieldCheckBox from "@/Pages/Leads/Components/Fields/LeadFieldCheckBox.vue";

export default {
    components: {LeadFieldCheckBox},
    props: {
        disabled: {type: Boolean, default: false},
        hideTitle: {type: Boolean, default: false},
        withoutTitle: {type: Boolean, default: false},

        val: {type: [Number, String, Boolean], default: null},

        small: {type: Boolean, default: false},
    },

    mixins: [LeadFormMixin],
}
</script>
