<template>
    <LayoutPage :title="$route.meta['title']" :loading="list.is_loading">
        <template #actions v-if="can('training_base.edit')">
            <GuiActionsMenu>
                <router-link class="link" :to="{ name: 'training-base-edit', params: { id: 0 }}">Добавить объект</router-link>
            </GuiActionsMenu>
        </template>
        <LayoutFilters>
            <LayoutFiltersItem :title="'Статус объекта'">
                <DictionaryDropDown
                    :dictionary="'training_base_statuses'"
                    v-model="list.filters['status_id']"
                    :original="list.filters_original['status_id']"
                    :placeholder="'Все'"
                    :has-null="true"
                    :small="true"
                    @change="list.load()"
                />
            </LayoutFiltersItem>
            <LayoutFiltersItem :title="'Виды спорта'">
                <DictionaryDropDown
                    :dictionary="'sport_kinds'"
                    v-model="list.filters['sport_kinds']"
                    :original="list.filters_original['sport_kinds']"
                    :placeholder="'Все'"
                    :has-null="true"
                    :small="true"
                    :multi="true"
                    @change="list.load()"
                />
            </LayoutFiltersItem>
            <template #search>
                <LayoutFiltersItem :title="'Поиск по названию, адресу, телефону, email'">
                    <InputSearch v-model="list.search" @change="list.load()"/>
                </LayoutFiltersItem>
            </template>
        </LayoutFilters>

        <ListTable v-if="list.list && list.list.length > 0" :titles="list.titles">
            <ListTableRow v-for="(base, key) in list.list" :key="key">
                <ListTableCell>
                    <div>
                        <GuiActivityIndicator :active="base['active']"/>
                        <router-link class="link" :to="{ name: 'training-base-view', params: { id: base['id'] }}" v-html="highlight(base['title'])"/>
                    </div>
                    <div class="text-sm text-gray" v-html="highlight(base['address'])"></div>
                </ListTableCell>
                <ListTableCell>
                    {{ base['sport_kinds'].join(', ') }}
                </ListTableCell>
                <ListTableCell>
                    <div>Телефон: <span v-html="highlight(base['phone'])"></span></div>
                    <div>Email: <a class="link" target="_blank" :href="'mailto:' + base['email']" v-html="highlight(base['email'])"></a></div>
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
        list: list('/api/training-base'),
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
