<template>
        <div class="action__block-right">
            <GuiActionsMenu v-if="can('staff.edit')">
                <router-link class="link" :to="{ name: 'contracts-edit', params: { id: 0 }}">Добавить договор</router-link>
            </GuiActionsMenu>
        </div>

    <ListTable v-if="list.list && list.list.length > 0" :titles="list.titles">
        <ListTableRow v-for="(contract, key) in list.list" :key="key">
            <ListTableCell>
                <GuiActivityIndicator :active="contract['active']"/>
                {{ contract['id'] }}
            </ListTableCell>
            <ListTableCell>
                <RouterLink class="link" :to="{name: 'contracts-edit', params: {id: contract['id']}}">{{ contract['title'] }}</RouterLink>
            </ListTableCell>
        </ListTableRow>
    </ListTable>

    <GuiMessage border v-else-if="list.is_loaded">Ничего не найдено</GuiMessage>

    <Pagination :pagination="list.pagination" @pagination="(page, per_page) => list.load(page, per_page)"/>
</template>

<script>
import list from "@/Core/List";
import LayoutPage from "@/Components/Layout/LayoutPage.vue";
import GuiActionsMenu from "@/Components/GUI/GuiActionsMenu.vue";
import ListTable from "@/Components/ListTable/ListTable.vue";
import ListTableRow from "@/Components/ListTable/ListTableRow.vue";
import ListTableCell from "@/Components/ListTable/ListTableCell.vue";
import GuiActivityIndicator from "@/Components/GUI/GuiActivityIndicator.vue";
import GuiMessage from "@/Components/GUI/GuiMessage.vue";
import Pagination from "@/Components/Pagination.vue";
import Permissions from "@/Mixins/Permissions.vue";
import LayoutRoutedTabs from "@/Components/Layout/LayoutRoutedTabs.vue";
import data from "@/Core/Data";
import GuiContainer from "@/Components/GUI/GuiContainer.vue";
import IconToggleOn from "@/Components/Icons/IconToggleOn.vue";
import IconEdit from "@/Components/Icons/IconEdit.vue";
import IconLock from "@/Components/Icons/IconLock.vue";
import IconCross from "@/Components/Icons/IconCross.vue";
import IconGripVertical from "@/Components/Icons/IconGripVertical.vue";

export default {
    components: {
        IconGripVertical,
        IconCross,
        IconLock,
        IconEdit,
        IconToggleOn,
        GuiContainer,
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
        list: list('/api/contracts'),
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
