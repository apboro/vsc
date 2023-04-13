<template>

    <GuiContainer v-if="data.data.patterns != null" w-50 mt-30>
        <template v-for="patterns in data.data.patterns">
                <FieldCheckBox v-model="data.data.patternIDs" :label="patterns.name" :value="patterns.id" :hide-title="true" :name="patterns.id"/>
        </template>
    </GuiContainer>

    <GuiContainer mt-30>
        <GuiButton @click="save" :color="'blue'">Сохранить</GuiButton>
    </GuiContainer>

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
import FieldCheckBox from "@/Components/Fields/FieldCheckBox.vue";
import GuiButton from "@/Components/GUI/GuiButton.vue";

export default {
    components: {
        GuiButton,
        FieldCheckBox,
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

    computed: {
        organizationId() {
            return Number(this.$route.params.id);
        },
    },

    data: () => ({
        data: data('/api/contracts'),
        tab: null,
    }),


    created() {
        this.data.load({
            organization_id: this.organizationId
        })
            .catch(response => response.code === 404 && this.$router.replace({name: '404'}));
    },

    methods: {
        save() {
            axios.post('/api/contracts/update', {
                patternIDs: this.data.data.patternIDs,
                organization_id: this.organizationId
            }).catch(error => {
                this.$toast.error(error.response.data['message'], 5000);
            });
        },

    }
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
