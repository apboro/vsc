<template>
    <LayoutPage :title="$route.meta['title']">
        <LayoutRoutedTabs :tabs="tabs" @change="tab = $event"/>


        <OrganizationsTabsPage v-if="tab === 'organizations'"/>
        <ContractsListPage v-if="tab === 'contracts'"/>
    </LayoutPage>
</template>

<script>
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
import OrganizationsTabsPage from "@/Pages/Admin/Organizations/OrganizationsTabsPage.vue";
import ContractsListPage from "@/Pages/Admin/Contracts/ContractsListPage.vue";

export default {
    components: {
        ContractsListPage,
        OrganizationsTabsPage,
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
        tab: null,
    }),

    computed: {
        tabs() {
            return {
                organizations: 'Организации',
                contracts: 'Договоры',
            }
        },
    },
}
</script>
