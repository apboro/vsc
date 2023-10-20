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
                    :multi="true"
                    @change="list.load()"
                />
            </LayoutFiltersItem>
            <LayoutFiltersItem :title="'Район'">
                <DictionaryDropDown
                    :dictionary="'regions'"
                    v-model="list.filters['region_id']"
                    :original="list.filters_original['region_id']"
                    :placeholder="'Все'"
                    :has-null="true"
                    :small="true"
                    :multi="true"
                    @change="list.load()"
                />
            </LayoutFiltersItem>
            <template #search>
                <LayoutFiltersItem :title="'Поиск по ФИО / (или) номеру телефона'" v-if="clientId === null">
                    <InputSearch v-model="list.search" @change="list.load()"/>
                </LayoutFiltersItem>
                <div v-if="clientId === null" style="display: flex; align-items: flex-end; margin-left: 10px;">
                    <GuiActionsMenu :title="null">
                        <span class="link" @click="excelExport">Экспорт в Excel</span>
                    </GuiActionsMenu>
                </div>
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
                    <div>
                        <RouterLink class="link" :to="{name: 'clients-view', params: {id: subscription['client_id']}}" v-html="highlight(subscription['client'])"/>
                    </div>
                    <div>
                        <span v-html="highlight(subscription['ward'])"/>
                    </div>
                </ListTableCell>
                <ListTableCell>
                    <div v-for="contract in subscription['contracts']">
                        <div style="margin-bottom: 5px;">{{ contract['title'] }}</div>
                        <div style="font-size: 12px; margin-bottom: 5px; color: #555; white-space: nowrap">{{ contract['start_at'] }} - {{ contract['end_at'] }}</div>
                        <div v-if="contract['discount']" style="font-size: 12px; margin-bottom: 5px; color: #555; white-space: nowrap">{{ contract['discount'] }}%
                            {{ contract['discount_name'] }}
                        </div>
                        <div style="font-size: 12px; color: #555; white-space: nowrap">{{ contract['monthly_price'] }} руб./мес.</div>
                    </div>
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
        is_exporting: false,
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
        excelExport() {
            this.$dialog.show('Экспортировать ' + this.list.pagination.total + ' записей в Excel?',
                null,
                'blue',
                [
                    this.$dialog.button('yes', 'Экспортировать', 'blue'),
                    this.$dialog.button('no', 'Отмена', 'default'),
                ]
            )
                .then(result => {
                    if (result === 'yes') {
                        this.is_exporting = true;
                        let options = {
                            filters: this.list.filters,
                            search: this.list.search,
                        }
                        axios.post('/api/subscriptions/export', options)
                            .then(response => {
                                let file = atob(response.data.data['file']);
                                let byteNumbers = new Array(file.length);
                                for (let i = 0; i < file.length; i++) {
                                    byteNumbers[i] = file.charCodeAt(i);
                                }
                                let byteArray = new Uint8Array(byteNumbers);
                                let blob = new Blob([byteArray], {type: response.data.data['type']});

                                saveAs(blob, response.data.data['file_name'], {autoBom: true});
                            })
                            .catch(error => {
                                this.$toast.error(error.response.data['message']);
                            })
                            .finally(() => {
                                this.is_exporting = false;
                            });
                    }
                });
        },
    },
}
</script>
