<template>
    <LeadFieldWrapper :title="title" :hide-title="hideTitle" :required="required" :disabled="disabled" :valid="valid" :errors="errors">
        <LeadInputDate
            v-model="proxyValue"
            :name="name"
            :original="original"
            :valid="valid"
            :disabled="disabled"
            :from="from"
            :to="to"
            :clearable="clearable"
            :pick-on-clear="pickOnClear"
            :placeholder="placeholder"
            :small="small"
            @change="change"
            ref="input"
        />
    </LeadFieldWrapper>
</template>

<script>

import LeadFieldWrapper from "@/Pages/Leads/Components/Fields/Helpers/LeadFieldWrapper.vue";
import LeadInputDate from "@/Pages/Leads/Components/Inputs/LeadInputDate.vue";

export default {
    components: {LeadInputDate, LeadFieldWrapper},
    props: {
        name: String,
        modelValue: {type: String, default: null},
        original: {type: String, default: null},

        title: {type: String, default: null},
        required: {type: Boolean, default: false},
        disabled: {type: Boolean, default: false},
        valid: {type: Boolean, default: true},
        errors: {type: Array, default: null},
        hideTitle: {type: Boolean, default: false},

        placeholder: {type: String, default: null},
        small: {type: Boolean, default: false},

        from: {type: String, default: null},
        to: {type: String, default: null},
        clearable: {type: Boolean, default: false},
        pickOnClear: {type: Boolean, default: true},
    },

    emits: ['update:modelValue', 'change'],

    computed: {
        proxyValue: {
            get() {
                return this.modelValue
            },
            set(value) {
                this.$emit('update:modelValue', value);
            }
        }
    },

    methods: {
        focus() {
            this.$refs.input.focus()
        },
        change(value, name) {
            this.$emit('change', value, name);
        },
    }
}
</script>

