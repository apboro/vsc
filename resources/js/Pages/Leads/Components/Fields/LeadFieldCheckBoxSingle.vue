<template>
    <LeadFieldWrapper :title="title" :hide-title="hideTitle" :required="required" :disabled="disabled" :valid="valid" :errors="errors">
        <LeadInputCheckboxSingle
            v-model="proxyValue"
            :name="name"
            :original="original"
            :valid="valid"
            :disabled="disabled"
            :value="value"
            :label="label"
            :small="small"
            @change="change"
            ref="input"/>
    </LeadFieldWrapper>
</template>

<script>
import LeadFieldWrapper from "@/Pages/Leads/Components/Fields/Helpers/LeadFieldWrapper.vue";
import LeadInputCheckboxSingle from "@/Pages/Leads/Components/Inputs/LeadInputCheckboxSingle.vue";

export default {
    components: {LeadInputCheckboxSingle, LeadFieldWrapper},
    props: {
        name: {type: String, required: true},
        modelValue: {type: [Number, String, Boolean, Array], default: null},
        original: {type: [Number, String, Boolean, Array], default: null},

        title: {type: String, default: null},
        required: {type: Boolean, default: false},
        disabled: {type: Boolean, default: false},
        valid: {type: Boolean, default: true},
        errors: {type: Array, default: null},
        hideTitle: {type: Boolean, default: false},

        label: {type: String, default: null},
        value: {type: [Number, String, Boolean], default: null},

        small: {type: Boolean, default: false},
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

