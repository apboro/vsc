<template>
    <LeadInputWrapper class="input-string" :dirty="isDirty" :disabled="disabled" :valid="valid">
        <input
            class="input-string__input"
            :class="{'input-string__input-small': small}"
            :value="modelValue"
            :type="type"
            :disabled="disabled"
            :autocomplete="autocomplete"
            :placeholder="placeholder"
            @input="update"
            ref="input"
        />
    </LeadInputWrapper>
</template>

<script>

import LeadInputWrapper from "@/Pages/Leads/Components/Inputs/Helpers/LeadInputWrapper.vue";

export default {
    components: {LeadInputWrapper},
    props: {
        name: String,
        modelValue: {type: String, default: null},
        original: {type: String, default: null},
        valid: {type: Boolean, default: true},
        disabled: {type: Boolean, default: false},

        type: {type: String, default: 'text', validation: (value) => ['text', 'password'].indexOf(value) !== -1},
        autocomplete: {type: String, default: 'off'},
        placeholder: {type: String, default: null},
        small: {type: Boolean, default: false},
    },

    emits: ['update:modelValue', 'change'],

    computed: {
        isDirty() {
            return this.original !== this.modelValue;
        },
    },

    methods: {
        focus() {
            this.$refs.input.focus()
        },

        update(event) {
            let value = event.target.value !== '' ? String(event.target.value) : null;
            this.$emit('update:modelValue', value);
            this.$emit('change', value, this.name);
        },
    }
}
</script>

<style lang="scss">
@import "../../../../../css/fonts";

$base_size_unit: 40px !default;
$input_placeholder_color: #757575 !default;

.input-string {
    height: $base_size_unit !important;

    &__input {
        border: none !important;
        outline: none !important;
        box-sizing: border-box;
        height: $base_size_unit !important;
        line-height: $base_size_unit !important;
        font-size: 16px;
        color: inherit;
        padding: 0 10px;
        flex-grow: 1;
        flex-shrink: 1;
        width: 100%;
        background-color: transparent;
        display: block;
        cursor: inherit;

        &-small {
            font-size: 14px;
        }

        &::placeholder { /* Chrome, Firefox, Opera, Safari 10.1+ */
            color: $input_placeholder_color;
            opacity: 1; /* Firefox */
        }

        &:-ms-input-placeholder { /* Internet Explorer 10-11 */
            color: $input_placeholder_color;
        }

        &::-ms-input-placeholder { /* Microsoft Edge */
            color: $input_placeholder_color;
        }
    }
}
</style>
