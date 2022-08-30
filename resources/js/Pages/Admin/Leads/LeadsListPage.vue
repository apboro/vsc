<template>
    <LayoutPage :title="$route.meta['title']" :loading="list.is_loading">
        <!--
        <template #actions v-if="can('services.edit')">
            <GuiActionsMenu>
                <router-link class="link" :to="{ name: 'services-edit', params: { id: 0 }}">Добавить услугу</router-link>
            </GuiActionsMenu>
        </template>
        <LayoutFilters>
            <LayoutFiltersItem :title="'Статус услуги'">
                <DictionaryDropDown
                    :dictionary="'service_statuses'"
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
                <LayoutFiltersItem :title="'Поиск по названию'">
                    <InputSearch v-model="list.search" @change="list.load()"/>
                </LayoutFiltersItem>
            </template>
        </LayoutFilters>
            -->

        <ListTable v-if="list.list && list.list.length > 0" :titles="list.titles">
            <ListTableRow v-for="lead in list.list">
                <ListTableCell>
                    <div>{{ lead['created_date'] }}</div>
                    <div>{{ lead['created_time'] }}</div>
                </ListTableCell>
                <ListTableCell>
                    <RouterLink class="link" :to="{name: 'leads-view', params: {id: lead['id']}}">
                        {{ lead['ward_lastname'] }} {{ lead['ward_firstname'] }} {{ lead['ward_patronymic'] }}
                    </RouterLink>
                </ListTableCell>
                <ListTableCell>
                    <RouterLink class="link" :to="{name: 'leads-view', params: {id: lead['id']}}">
                        {{ lead['client_lastname'] }} {{ lead['client_firstname'] }} {{ lead['client_patronymic'] }}
                    </RouterLink>
                </ListTableCell>
                <ListTableCell>
                    {{ lead['status'] }}
                </ListTableCell>
                <ListTableCell>
                    <span v-if="lead['need_help']">Нужна помощь с выбором</span>
                    <template v-else>
                        <div>{{ lead['service'] }}</div>
                        <div><i>{{ lead['sport_kind'] }}, {{ lead['training_base'] }}</i></div>
                    </template>
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
        list: list('/api/leads'),
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
