<template>
    <LayoutPage :title="$route.meta['title']" :loading="list.is_loading">
        <!--
        <template #actions v-if="can('services.edit')">
            <GuiActionsMenu>
                <router-link class="link" :to="{ name: 'services-edit', params: { id: 0 }}">Добавить услугу</router-link>
            </GuiActionsMenu>
        </template>
        -->
        <LayoutFilters>
            <LayoutFiltersItem :title="'Статус'">
                <DictionaryDropDown
                    :dictionary="'lead_statuses'"
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
            <LayoutFiltersItem :title="'Объект'" class="w-200px">
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
                <LayoutFiltersItem :title="'Поиск по ФИО / (или) номеру телефона'">
                    <InputSearch v-model="list.search" @change="list.load()"/>
                </LayoutFiltersItem>
                <div style="display: flex; align-items: flex-end; margin-left: 10px;">
                    <GuiActionsMenu :title="null">
                        <span class="link" @click="excelExport">Экспорт в Excel</span>
                    </GuiActionsMenu>
                </div>
            </template>
        </LayoutFilters>
        <LayoutFilters style="padding-top: 0;">
            <LayoutFiltersItem :title="'Услуга'">
                <InputDropDown
                    :options="services"
                    v-model="list.filters['service_id']"
                    :original="list.filters_original['service_id']"
                    :identifier="'id'"
                    :show="'title'"
                    :placeholder="'Все'"
                    :search="true"
                    :has-null="true"
                    :small="true"
                    @change="list.load()"
                />
            </LayoutFiltersItem>
            <LayoutFiltersItem :title="'Тип программы'">
                <DictionaryDropDown
                    :dictionary="'service_programs'"
                    v-model="list.filters['type_program_id']"
                    :original="list.filters_original['type_program_id']"
                    :placeholder="'Все'"
                    :has-null="true"
                    :small="true"
                    :multi="true"
                    @change="list.load()"
                />
            </LayoutFiltersItem>
        </LayoutFilters>

        <ListTable v-if="list.list && list.list.length > 0" :titles="list.titles">
            <ListTableRow v-for="lead in list.list">
                <ListTableCell>
                    <div>{{ lead['created_date'] }}</div>
                    <div>{{ lead['created_time'] }}</div>
                </ListTableCell>
                <ListTableCell>
                    <RouterLink class="link" :to="{name: 'leads-view', params: {id: lead['id']}}"
                                v-html="highlight(lead['ward_lastname'] + ' ' + lead['ward_firstname'] + ' ' + lead['ward_patronymic'])"
                    />
                </ListTableCell>
                <ListTableCell>
                    <RouterLink class="link" :to="{name: 'leads-view', params: {id: lead['id']}}"
                                v-html="highlight(lead['client_lastname'] + ' ' + lead['client_firstname'] + ' ' + lead['client_patronymic'])"
                    />
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
import InputDropDown from "@/Components/Inputs/InputDropDown";
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
        InputDropDown,
        ListTable,
        ListTableRow,
        ListTableCell,
        GuiMessage,
        Pagination,
    },

    data: () => ({
        list: list('/api/leads'),
        services: [],
    }),

    created() {
        this.list.initial();
        this.getServices()
    },

    methods: {
        highlight(text) {
            return this.$highlight(text, this.list.search);
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
                        axios.post('/api/leads/export', options)
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
        getServices() {
            axios.post('/api/leads/services')
                .then(res => {
                    this.services = res.data.payload
                })
                .catch(error => {
                    this.$toast.error(error.response.data['message']);
                })
        }
    },
}
</script>
