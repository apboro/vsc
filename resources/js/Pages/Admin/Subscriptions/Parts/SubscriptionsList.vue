<template>
    <LoadingProgress :loading="ready && list.is_loading">
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
            <LayoutFiltersItem :title="'Услуга'">
                <DictionaryDropDown
                    :dictionary="'services'"
                    v-model="list.filters['service_id']"
                    :original="list.filters_original['service_id']"
                    :placeholder="'Все'"
                    :has-null="true"
                    :small="true"
                    :search="true"
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
                <LayoutFiltersItem :title="'Поиск по ФИО'" v-if="clientId === null">
                    <InputSearch v-model="list.search" @change="list.load()"/>
                </LayoutFiltersItem>
            </template>
        </LayoutFilters>

        <ListTable v-if="list.list && list.list.length > 0" :titles="list.titles">
            <ListTableRow v-for="subscription in list.list">
                <ListTableCell>
                    <RouterLink v-if="clientId===null" class="link" :to="{name: 'subscriptions-view', params: {id: subscription['id']}}">{{ subscription['id'] }}</RouterLink>
                    <RouterLink v-else class="link" :to="{name: 'clients-subscriptions-view', params: {client: clientId, id: subscription['id']}}">{{
                            subscription['id']
                        }}
                    </RouterLink>
                </ListTableCell>
                <ListTableCell>
                    {{ subscription['status'] }}
                </ListTableCell>
                <ListTableCell>
                    <span v-html="highlight(subscription['ward'])"/>
                </ListTableCell>
                <ListTableCell>
                    <RouterLink class="link" :to="{name: 'clients-view', params: {id: subscription['client_id']}}" v-html="highlight(subscription['client'])"/>
                </ListTableCell>
                <ListTableCell>
                    <div>
                        <RouterLink class="link" :to="{name: 'services-view', params: {id: subscription['service_id']}}">{{ subscription['service'] }}</RouterLink>
                    </div>
                    <div><i>({{ subscription['sport_kind'] }})</i></div>
                </ListTableCell>
                <ListTableCell>
                    <div>{{ subscription['training_base'] }}</div>
                    <div><i>{{ subscription['training_base_address'] }}</i></div>
                </ListTableCell>
            </ListTableRow>
        </ListTable>

        <GuiMessage border v-else-if="list.is_loaded">Ничего не найдено</GuiMessage>

        <Pagination :pagination="list.pagination" @pagination="(page, per_page) => list.load(page, per_page)"/>
    </LoadingProgress>
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
import LoadingProgress from "@/Components/LoadingProgress";

export default {
    props: {
        clientId: {type: Number, default: null},
        ready: {type: Boolean, default: true},
    },

    components: {
        LoadingProgress,
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
        this.list.options = {client_id: this.clientId};
        this.list.initial();
    },

    methods: {
        highlight(text) {
            return this.$highlight(text, this.list.search);
        },
        reload() {
            this.list.reload();
        },
    },
}
</script>
