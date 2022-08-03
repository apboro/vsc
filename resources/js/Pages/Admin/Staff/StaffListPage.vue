<template>
    <LayoutPage :title="$route.meta['title']" :loading="list.is_loading">
        <template #actions v-if="can('staff.edit')">
            <GuiActionsMenu>
                <router-link class="link" :to="{ name: 'staff-edit', params: { id: 0 }}">Добавить сотрудника</router-link>
            </GuiActionsMenu>
        </template>
        <LayoutFilters>
            <LayoutFiltersItem :title="'Статус сотрудника'">
                <DictionaryDropDown
                    :dictionary="'position_statuses'"
                    v-model="list.filters['position_status_id']"
                    :original="list.filters_original['position_status_id']"
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
            <ListTableRow v-for="(position, key) in list.list" :key="key">
                <ListTableCell>
                    <GuiActivityIndicator :active="position['active']"/>
                    <router-link class="link" :to="{ name: 'staff-view', params: { id: position['id'] }}" v-html="highlight(position['name'])"/>
                </ListTableCell>
                <ListTableCell>
                    {{ position['position'] }}
                </ListTableCell>
                <ListTableCell>
                    <a class="link" target="_blank" :href="'mailto:' + position['email']">{{ position['email'] }}</a>
                </ListTableCell>
                <ListTableCell>
                    {{ position['work_phone'] }}<span v-if="position['work_phone_add']"> доб. {{ position['work_phone_add'] }}</span>
                </ListTableCell>
                <ListTableCell>
                    {{ position['phone'] }}
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
import GuiActivityIndicator from "@/Components/GUI/GuiActivityIndicator";
import GuiMessage from "@/Components/GUI/GuiMessage";
import Pagination from "@/Components/Pagination";
import GuiAccessIndicator from "@/Components/GUI/GuiAccessIndicator";
import Permissions from "@/Mixins/Permissions";

export default {
    components: {
        GuiAccessIndicator,
        LayoutPage,
        GuiActionsMenu,
        LayoutFilters,
        LayoutFiltersItem,
        DictionaryDropDown,
        InputSearch,
        ListTable,
        ListTableRow,
        ListTableCell,
        GuiActivityIndicator,
        GuiMessage,
        Pagination,
    },

    mixins: [Permissions],

    data: () => ({
        list: list('/api/staff'),
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
