<template>
    <LoadingProgress :loading="client_form.is_loading || client_form.is_saving">
        <div style="text-align: right; margin-bottom: 15px;">
            <GuiActionsMenu v-if="can('clients.edit')">
                <span class="link" v-if="can('clients.edit')" @click="editClient">Изменить данные клиента</span>
            </GuiActionsMenu>
        </div>

        <GuiContainer w-50 mt-30 pr-10 inline>
            <GuiValue :title="'Фамилия'">{{ data['lastname'] }}</GuiValue>
            <GuiValue :title="'Имя'">{{ data['firstname'] }}</GuiValue>
            <GuiValue :title="'Отчество'">{{ data['patronymic'] }}</GuiValue>
            <GuiValue :title="'Телефон'">{{ data['phone'] }}</GuiValue>
            <GuiValue :title="'Email'">{{ data['email'] }}</GuiValue>
            <GuiValue :title="'Статус'">{{ data['status'] }}</GuiValue>
        </GuiContainer>

        <FormPopUp :form="client_form" ref="client_edit" :save-button-caption="'Сохранить'">
            <FormString :form="client_form" :name="'lastname'"/>
            <FormString :form="client_form" :name="'firstname'"/>
            <FormString :form="client_form" :name="'patronymic'"/>
            <FormPhone :form="client_form" :name="'phone'"/>
            <FormString :form="client_form" :name="'email'"/>
        </FormPopUp>
    </LoadingProgress>
</template>

<script>
import GuiContainer from "@/Components/GUI/GuiContainer";
import GuiValue from "@/Components/GUI/GuiValue";
import Permissions from "../../../../Mixins/Permissions";
import GuiActionsMenu from "../../../../Components/GUI/GuiActionsMenu";
import FormPopUp from "../../../../Components/FormPopUp";
import FormPhone from "../../../../Components/Form/FormPhone";
import FormString from "../../../../Components/Form/FormString";
import form from "../../../../Core/Form";
import LoadingProgress from "../../../../Components/LoadingProgress";

export default {
    props: {
        data: {type: Object, required: true},
        clientId: {type: Number, required: true},
    },

    mixins: [Permissions],

    emits: ['update'],

    components: {
        LoadingProgress,
        FormString,
        FormPhone,
        FormPopUp,
        GuiActionsMenu,
        GuiValue,
        GuiContainer
    },

    data: () => ({
        client_form: form('/api/clients/get', '/api/clients/update'),
    }),

    methods: {
        editClient() {
            this.client_form.load({client_id: this.clientId})
                .then(() => {
                    this.$refs.client_edit.show({client_id: this.clientId})
                        .then(() => {
                            this.$emit('update');
                        })
                })
        }
    }
}
</script>
