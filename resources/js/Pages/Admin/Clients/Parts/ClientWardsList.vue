<template>
    <LoadingProgress :loading="ready && (list.is_loading || ward_form.is_loading || ward_form.is_saving)">
        <ListTable v-if="list.list && list.list.length > 0" :titles="list.titles" :has-action="can('clients.edit')">
            <ListTableRow v-for="ward in list.list">
                <ListTableCell>
                    {{ ward['id'] }}
                </ListTableCell>
                <ListTableCell>
                    {{ ward['name'] }}
                </ListTableCell>
                <ListTableCell>
                    {{ ward['birth_date'] }}
                </ListTableCell>
                <ListTableCell v-if="can('clients.edit')">
                    <GuiActionsMenu :title="null">
                        <span class="link" v-if="can('clients.edit')" @click="editWard(ward)">Изменить данные занимающегося</span>
                    </GuiActionsMenu>
                </ListTableCell>
            </ListTableRow>
        </ListTable>

        <GuiMessage border v-else-if="list.is_loaded">Ничего не найдено</GuiMessage>

        <Pagination :pagination="list.pagination" @pagination="(page, per_page) => list.load(page, per_page)"/>

        <FormPopUp :form="ward_form" ref="ward_edit" :save-button-caption="'Сохранить'">
            <FormString :form="ward_form" :name="'ward_lastname'"/>
            <FormString :form="ward_form" :name="'ward_firstname'"/>
            <FormString :form="ward_form" :name="'ward_patronymic'"/>
            <FormDate :form="ward_form" :name="'ward_birth_date'"/>
        </FormPopUp>
    </LoadingProgress>
</template>

<script>
import list from "@/Core/List";
import LoadingProgress from "@/Components/LoadingProgress";
import ListTable from "@/Components/ListTable/ListTable";
import ListTableRow from "@/Components/ListTable/ListTableRow";
import ListTableCell from "@/Components/ListTable/ListTableCell";
import GuiMessage from "@/Components/GUI/GuiMessage";
import Pagination from "@/Components/Pagination";
import Permissions from "../../../../Mixins/Permissions";
import GuiActionsMenu from "../../../../Components/GUI/GuiActionsMenu";
import form from "../../../../Core/Form";
import FormPopUp from "../../../../Components/FormPopUp";
import FormString from "../../../../Components/Form/FormString";
import FormDate from "../../../../Components/Form/FormDate";

export default {
    props: {
        clientId: {type: Number, required: true},
        ready: {type: Boolean, default: true},
    },

    components: {
        FormDate,
        FormString,
        FormPopUp,
        GuiActionsMenu,
        Pagination,
        GuiMessage,
        ListTableCell,
        ListTableRow,
        ListTable,
        LoadingProgress,
    },

    mixins: [Permissions],

    data: () => ({
        list: list('/api/clients/wards'),
        ward_form: form('/api/clients/wards/get', '/api/clients/wards/update'),
    }),

    created() {
        this.list.options = {client_id: this.clientId};
        this.list.initial();
        this.ward_form.toaster = this.$toast;
    },

    methods: {
        editWard(ward) {
            this.ward_form.load({client_id: this.clientId, ward_id: ward['id']})
                .then(() => {
                    this.$refs.ward_edit.show({client_id: this.clientId, ward_id: ward['id']})
                        .then(() => {
                            this.list.reload();
                        })
                })
        }
    }
}
</script>
