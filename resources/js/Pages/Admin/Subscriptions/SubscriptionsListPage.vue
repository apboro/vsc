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
            <LayoutFiltersItem :title="'Статус'">
                <DictionaryDropDown
                    :dictionary="'subscription_statuses'"
                    v-model="list.filters['status_id']"
                    :original="list.filters_original['status_id']"
                    :placeholder="'Все'"
                    :has-null="true"
                    :small="true"
                    @change="list.load()"
                />
            </LayoutFiltersItem>
            <LayoutFiltersItem :title="'Вид спорта'">
                <DictionaryDropDown
                    :dictionary="'sport_kinds'"
                    v-model="list.filters['sport_kind_id']"
                    :original="list.filters_original['sport_kind_id']"
                    :placeholder="'Все'"
                    :has-null="true"
                    :small="true"
                    @change="list.load()"
                />
            </LayoutFiltersItem>
            <LayoutFiltersItem :title="'Объект'">
                <DictionaryDropDown
                    :dictionary="'training_bases'"
                    v-model="list.filters['training_base_id']"
                    :original="list.filters_original['training_base_id']"
                    :placeholder="'Все'"
                    :has-null="true"
                    :small="true"
                    :search="true"
                    @change="list.load()"
                />
            </LayoutFiltersItem>
            <template #search>
                <LayoutFiltersItem :title="'Поиск по ФИО клиента'">
                    <InputSearch v-model="list.search" @change="list.load()"/>
                </LayoutFiltersItem>
            </template>
        </LayoutFilters>

        <ListTable v-if="list.list && list.list.length > 0" :titles="list.titles">
            <ListTableRow v-for="subscription in list.list">
                <ListTableCell>
                    {{ subscription['id'] }}
                </ListTableCell>
                <ListTableCell>
                    {{ subscription['status'] }}
                </ListTableCell>
                <ListTableCell>
                    <RouterLink class="link" :to="{name: 'clients-view', params: {id: subscription['client_id']}}" v-html="highlight(subscription['client'])"/>
                </ListTableCell>
                <ListTableCell>
                    {{ subscription['sport_kind'] }}
                </ListTableCell>
                <ListTableCell>
                    {{ subscription['training_base'] }}
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
        list: list('/api/subscriptions'),
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
