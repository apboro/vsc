
<template>
    <label class="checkbox" :class="{'checkbox__disabled': disabled, 'checkbox__error': !valid}">
        <input class="checkbox__input" type="checkbox"
               v-model="proxyValue"
               :value="value"
               :disabled="disabled"
        >
        <span class="checkbox__check">
            <LeadIconCheck class="checkbox__check-checked"/>
        </span>
        <span class="checkbox__label" v-if="!label" :class="{'checkbox__label-small': small}"><slot/></span>
        <span class="checkbox__label" v-else :class="{'checkbox__label-small': small}">{{ label }}</span>
    </label>
</template>

<script>
import LeadIconCheck from "@/Pages/Leads/Components/Icons/LeadIconCheck.vue";

export default {
    components: {LeadIconCheck},
    props: {
        modelValue: {type: [String, Number, Boolean, Array], default: null},
        label: {type: String, default: null},
        value: {type: [String, Number, Boolean], default: null},
        valid: {type: Boolean, default: true},
        disabled: {type: Boolean, default: false},
        small: {type: Boolean, default: false},
    },

    emits: ['update:modelValue'],

    computed: {
        proxyValue: {
            get() {
                return this.modelValue;
            },
            set(value) {
                this.$emit('update:modelValue', value);
            }
        },
    }
}
</script>

<style lang="scss">
@import "../../../../../../css/fonts";

$project_font: -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Helvetica Neue, Arial, Noto Sans, sans-serif, Apple Color Emoji, Segoe UI Emoji, Segoe UI Symbol, Noto Color Emoji !default;
$base_size_unit: 40px !default;
$input_color: #1e1e1e !default;
$input_disabled_color: #626262 !default;
$input_active_color: #b61b21 !default;
$input_background_color: #fdc93c !default;
$input_placeholder_color: #757575;
$input_error_color: #FF1E00 !default;

.checkbox {
    height: 100%;
    display: flex;
    align-items: center;
    cursor: pointer;
    position: relative;

    &__disabled {
        cursor: not-allowed;
    }

    &__input {
        visibility: hidden;
        opacity: 0;
        position: absolute;
        width: 0;
        height: 0;
    }

    &__check {
        width: 35px;
        height: 35px;
        margin-right: 20px;
        border: none;
        border-radius: 6px;
        background-color: #dddddd;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;

        &-checked {
            color: inherit;
            display: none;
            width: 75%;
            height: 75%;
        }
    }

    &__error:not(&__disabled) &__check {
        border-color: $input_error_color !important;
    }

    &__disabled &__check {
        border-color: transparentize($input_disabled_color, 0.5) !important;
        color: $input_disabled_color !important;
        background-color: transparentize($input_disabled_color, 0.75) !important;
    }

    &__input:checked + &__check {
        border-color: $input_active_color;
        background-color: $input_active_color;
        color: $input_background_color;
    }

    &__input:checked + &__check > &__check-checked {
        display: block;
    }

    &__label {
        margin: 0 7px 0 7px;
        font-size: 16px;
        font-family: $norms_regular_font;
        display: inline-block;
        color: $input_color;
        position: relative;
        top: 1px;
        //@include no_selection;

        &-small {
            font-size: 14px;
        }
    }

    &__disabled &__label {
        color: $input_disabled_color;
    }
}
</style>
