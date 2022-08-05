<template>
    <LoadingProgress :loading="ready && list.is_loading">
        <ListTable v-if="list.list && list.list.length > 0" :titles="list.titles">
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
            </ListTableRow>
        </ListTable>

        <GuiMessage border v-else-if="list.is_loaded">Ничего не найдено</GuiMessage>

        <Pagination :pagination="list.pagination" @pagination="(page, per_page) => list.load(page, per_page)"/>
    </LoadingProgress>
</template>

<script>
import list from "@/Core/List";
import LoadingProgress from "@/Components/LoadingProgress";
import ListTable from "@/Components/ListTable/ListTable";
import ListTableRow from "@/Components/ListTable/ListTableRow";
import ListTableCell from "@/Components/ListTable/ListTableCell";
import GuiMessage from "@/Apps/LeadApp";
import Pagination from "@/Components/Pagination";

export default {
    props: {
        clientId: {type: Number, required: true},
        ready: {type: Boolean, default: true},
    },

    components: {
        Pagination,
        GuiMessage,
        ListTableCell,
        ListTableRow,
        ListTable,
        LoadingProgress,
    },

    data: () => ({
        list: list('/api/clients/wards'),
    }),

    created() {
        this.list.options = {client_id: this.clientId};
        this.list.initial();
    }
}
</script>
