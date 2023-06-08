<template>
    <LeadFieldWrapper :title="title" :hide-title="hideTitle" :required="required" :disabled="disabled" :valid="valid" :errors="errors">
        <LeadInputPhone
            v-model="proxyValue"
            :name="name"
            :original="original"
            :valid="valid"
            :disabled="disabled"
            :autocomplete="autocomplete"
            :placeholder="placeholder"
            :small="small"
            @change="change"
            ref="input"
        />
    </LeadFieldWrapper>
</template>

<script>
import FieldWrapper from "@/Components/Fields/Helpers/FieldWrapper";
import InputPhone from "@/Components/Inputs/InputPhone";
import LeadFieldWrapper from "@/Pages/Leads/Components/Fields/Helpers/LeadFieldWrapper.vue";
import LeadInputPhone from "@/Pages/Leads/Components/Inputs/LeadInputPhone.vue";

export default {
    components: {LeadInputPhone, LeadFieldWrapper},
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

        autocomplete: {type: String, default: 'off'},
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

