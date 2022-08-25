<template>
    <FieldTime
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
        :small="small"
        @change="change"
        ref="input"
    />
</template>

<script>
import FormMixin from "@/Components/Form/Helpers/FormMixin";
import FieldTime from "../Fields/FieldTime";

export default {
    components: {FieldTime},
    props: {
        disabled: {type: Boolean, default: false},
        small: {type: Boolean, default: false},
    },

    mixins: [FormMixin],
}
</script>
