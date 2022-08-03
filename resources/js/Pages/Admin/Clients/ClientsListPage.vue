<template>
    <LayoutPage :title="$route.meta['title']" :loading="list.is_loading">
        <!--
        <template #actions v-if="can('services.edit')">
            <GuiActionsMenu>
                <router-link class="link" :to="{ name: 'services-edit', params: { id: 0 }}">Добавить услугу</router-link>
            </GuiActionsMenu>
        </template>
            -->
        <LayoutFilters>
            <LayoutFiltersItem :title="'Статус клиента'">
                <DictionaryDropDown
                    :dictionary="'client_statuses'"
                    v-model="list.filters['client_status_id']"
                    :original="list.filters_original['client_status_id']"
                    :placeholder="'Все'"
                    :has-null="true"
                    :small="true"
                    @change="list.load()"
                />
            </LayoutFiltersItem>
            <template #search>
                <LayoutFiltersItem :title="'Поиск по ФИО'">
                    <InputSearch v-model="list.search" @change="list.load()"/>
                </LayoutFiltersItem>
            </template>
        </LayoutFilters>

        <ListTable v-if="list.list && list.list.length > 0" :titles="list.titles">
            <ListTableRow v-for="client in list.list">
                <ListTableCell>
                    {{ client['id'] }}
                </ListTableCell>
                <ListTableCell>
                    <RouterLink class="link" :to="{name: 'clients-view', params: {id: client['id']}}" v-html="highlight(client['name'])"/>
                </ListTableCell>
                <ListTableCell>
                    {{ client['status'] }}
                </ListTableCell>
                <ListTableCell>
                    {{ client['email'] }}
                </ListTableCell>
                <ListTableCell>
                    {{ client['phone'] }}
                </ListTableCell>
            </ListTableRow>
        </ListTable>

        <GuiMessage border v-else-if="list.is_loaded">Ничего не найдено</GuiMessage>

        <Pagination :pagination="list.pagination" @pagination="(page, per_page) => list.load(page, per_page)"/>
    </LayoutPage>
</template>

<script>
import list from "@/Core/List";
import LayoutPage from "@/Components/Layout/LayoutPage";
import GuiActionsMenu from "@/Components/GUI/GuiActionsMenu";
import LayoutFilters from "@/Components/Layout/LayoutFilters";
import LayoutFiltersItem from "@/Components/Layout/LayoutFiltersItem";
import DictionaryDropDown from "@/Components/Inputs/DictionaryDropDown";
import InputSearch from "@/Components/Inputs/InputSearch";
import ListTable from "@/Components/ListTable/ListTable";
import ListTableRow from "@/Components/ListTable/ListTableRow";
import ListTableCell from "@/Components/ListTable/ListTableCell";
import GuiMessage from "@/Components/GUI/GuiMessage";
import Pagination from "@/Components/Pagination";

export default {
    components: {
        LayoutPage,
        GuiActionsMenu,
        LayoutFilters,
        LayoutFiltersItem,
        DictionaryDropDown,
        InputSearch,
        ListTable,
        ListTableRow,
        ListTableCell,
        GuiMessage,
        Pagination,
    },

    data: () => ({
        list: list('/api/clients'),
    }),

    created() {
        this.list.initial();
    },

    methods: {
        highlight(text) {
            return this.$highlight(text, this.list.search);
        },
    },
}
</script>
