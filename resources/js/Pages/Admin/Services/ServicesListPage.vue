<template>
    <LayoutPage :title="$route.meta['title']" :loading="list.is_loading">
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
            <LayoutFiltersItem :title="'Вид услуг'">
                <DictionaryDropDown
                    :dictionary="'service_types'"
                    v-model="list.filters['service_type_id']"
                    :original="list.filters_original['service_type_id']"
                    :placeholder="'Все'"
                    :has-null="true"
                    :small="true"
                    @change="list.load()"
                />
            </LayoutFiltersItem>
            <LayoutFiltersItem :title="'Категории услуг'">
                <DictionaryDropDown
                    :dictionary="'service_categories'"
                    v-model="list.filters['service_category_id']"
                    :original="list.filters_original['service_category_id']"
                    :placeholder="'Все'"
                    :has-null="true"
                    :small="true"
                    @change="list.load()"
                />
            </LayoutFiltersItem>
            <template #search>
                <LayoutFiltersItem :title="'Поиск по названию'">
                    <InputSearch v-model="list.search" @change="list.load()"/>
                </LayoutFiltersItem>
            </template>
        </LayoutFilters>

        <LayoutFilters>
            <LayoutFiltersItem :title="'ФИО менеджера'">
                <DictionaryDropDown
                  :dictionary="'positions'"
                  v-model="list.filters['responsible_id']"
                  :original="list.filters_original['responsible_id']"
                  :placeholder="'Все'"
                  :has-null="true"
                  :small="true"
                  @change="list.load()"
                />
            </LayoutFiltersItem>
        </LayoutFilters>

        <ListTable v-if="list.list && list.list.length > 0" :titles="list.titles">
            <ListTableRow v-for="service in list.list">
                <ListTableCell>
                    {{ service['id'] }}
                </ListTableCell>
                <ListTableCell>
                    <GuiActivityIndicator :active="service['active']"/>
                    <router-link class="link" :to="{ name: 'services-view', params: { id: service['id'] }}" v-html="highlight(service['title'])"/>
                </ListTableCell>
                <ListTableCell>
                    {{ service['sport_kind'] }}
                </ListTableCell>
                <ListTableCell>
                    <div>{{ service['training_base'] }}</div>
                    <div><i>{{ service['training_base_address'] }}</i></div>
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
        list: list('/api/services'),
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
