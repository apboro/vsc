<template>
    <InputWrapper class="input-time" :dirty="isDirty" :disabled="disabled" :valid="valid">
        <TimeInput
            v-model="proxyValue"
            :placeholder="null"
            :disabled="disabled"
            :small="small"
            @change="change"
        />
    </InputWrapper>
</template>

<script>
import InputWrapper from "@/Components/Inputs/Helpers/InputWrapper";
import TimeInput from "@/Components/Inputs/Helpers/TimeInput";

export default {
    components: {TimeInput, InputWrapper},

    props: {
        name: String,
        modelValue: {type: String, default: null},
        original: {type: String, default: null},
        valid: {type: Boolean, default: true},
        disabled: {type: Boolean, default: false},
        placeholder: {type: String, default: null},
        small: {type: Boolean, default: false},
    },

    emits: ['update:modelValue', 'change'],

    computed: {
        isDirty() {
            return this.original !== this.modelValue;
        },
        proxyValue: {
            get() {
                return this.modelValue
            },
            set(value) {
                this.$emit('update:modelValue', value);
                this.change(value, this.name);
            }
        },
    },

    methods: {
        change(value, name) {
            this.$emit('change', value, name);
        },
    }
}
</script>

<style lang="scss">
@import "../variables";

$base_size_unit: 35px !default;

.input-time {
    height: $base_size_unit;
    display: flex;
    width: 100%;
}
</style>
