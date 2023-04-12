<template>
        <div v-if="can('staff.edit')" class="action__block-right">
            <GuiActionsMenu v-if="can('staff.edit')">
                <router-link class="link" :to="{ name: 'organizations-edit', params: { id: 0 }}">Добавить организацию</router-link>
            </GuiActionsMenu>
        </div>

    <ListTable v-if="list.list && list.list.length > 0" :titles="list.titles">
        <ListTableRow v-for="(organization, key) in list.list" :key="key">
            <ListTableCell>
                <GuiActivityIndicator :active="organization['active']"/>
                {{ organization['id'] }}
            </ListTableCell>
            <ListTableCell>
                <RouterLink class="link" :to="{name: 'organizations-view', params: {id: organization['id']}}">{{ organization['title'] }}</RouterLink>
            </ListTableCell>
        </ListTableRow>
    </ListTable>

    <GuiMessage border v-else-if="list.is_loaded">Ничего не найдено</GuiMessage>

    <Pagination :pagination="list.pagination" @pagination="(page, per_page) => list.load(page, per_page)"/>
</template>

<script>
import list from "@/Core/List";
import LayoutPage from "@/Components/Layout/LayoutPage";
import GuiActionsMenu from "@/Components/GUI/GuiActionsMenu";
import ListTable from "@/Components/ListTable/ListTable";
import ListTableRow from "@/Components/ListTable/ListTableRow";
import ListTableCell from "@/Components/ListTable/ListTableCell";
import GuiActivityIndicator from "@/Components/GUI/GuiActivityIndicator";
import GuiMessage from "@/Components/GUI/GuiMessage";
import Pagination from "@/Components/Pagination";
import Permissions from "@/Mixins/Permissions";
import LayoutRoutedTabs from "@/Components/Layout/LayoutRoutedTabs.vue";

export default {
    components: {
        LayoutRoutedTabs,
        LayoutPage,
        GuiActionsMenu,
        ListTable,
        ListTableRow,
        ListTableCell,
        GuiActivityIndicator,
        GuiMessage,
        Pagination,
    },

    mixins: [Permissions],

    data: () => ({
        list: list('/api/organizations'),
        tab: null,
    }),

    created() {
        this.list.initial();
    },
}
</script>

<style lang="scss">

$project_font: -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Helvetica Neue, Arial, Noto Sans, sans-serif, Apple Color Emoji, Segoe UI Emoji, Segoe UI Symbol, Noto Color Emoji !default;
$list_table_cell_color: #1e1e1e;

.action__block-right {
    display: flex;
    justify-content: flex-end;
    margin-bottom: 15px;
}

</style>
