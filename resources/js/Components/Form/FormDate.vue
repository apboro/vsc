<template>
    <FieldDate
        :model-value="value"
        :name="name"
        :title="title"
        :hide-title="hideTitle"
        :original="original"
        :valid="valid"
        :errors="errors"
        :required="required"
        :disabled="disabled"
        :date-disabled="dateDisabled"
        :time-disabled="timeDisabled"
        :from="from"
        :to="to"
        :clearable="clearable"
        :pick-on-clear="pickOnClear"
        :placeholder="title"
        :small="small"
        @change="change"
        ref="input"
    />
</template>

<script>
import FormMixin from "@/Components/Form/Helpers/FormMixin";
import FieldDate from "@/Components/Fields/FieldDate";

export default {
    components: {FieldDate},
    props: {
        disabled: {type: Boolean, default: false},
        dateDisabled: {type: Boolean, default: false},
        timeDisabled: {type: Boolean, default: false},

        small: {type: Boolean, default: false},

        from: {type: String, default: null},
        to: {type: String, default: null},
        clearable: {type: Boolean, default: false},
        pickOnClear: {type: Boolean, default: true},
        hideTitle: {type: Boolean, default: false},
    },

    mixins: [FormMixin],
}
</script>

