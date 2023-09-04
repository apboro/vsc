<template>
    <div class="col-9">
    <FieldPhones
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
        :autocomplete="autocomplete"
        :small="small"
        @change="change"
        ref="input"
    />
    </div>
    <div class="col-3">
        <GuiButton @click="clone">Добавить</GuiButton>
    </div>
</template>

<script>
import FormMixin from "@/Components/Form/Helpers/FormMixin";
import FieldPhones from "@/Components/Fields/FieldPhones";
import GuiButton from "@/Components/GUI/GuiButton.vue";


export default {
    components: {GuiButton, FieldPhones},
    props: {
        disabled: {type: Boolean, default: false},
        small: {type: Boolean, default: false},
        autocomplete: {type: String, default: 'off'},
    },

    mixins: [FormMixin],
    methods:{
        clone(){

        }
    }
}
</script>
