<template>
    <LoadingProgress :loading="contracts.is_loading">
        <LayoutFilters>
            <LayoutFiltersItem :title="'Статус документа'">
                <DictionaryDropDown
                    :dictionary="'training_base_contract_statuses'"
                    v-model="contracts.filters['status_id']"
                    :original="contracts.filters_original['status_id']"
                    :placeholder="'Все'"
                    :has-null="true"
                    :small="true"
                    @change="contracts.load()"
                />
            </LayoutFiltersItem>
            <template #search v-if="can('training_base.contracts.modify')">
                <GuiButton :color="'blue'" @click="editDocument(null)">Добавить документ</GuiButton>
            </template>
        </LayoutFilters>

        <ListTable v-if="contracts.list && contracts.list.length > 0" :titles="contracts.titles" :has-action="can('training_base.contracts.modify')">
            <ListTableRow v-for="(contract, key) in contracts.list" :key="key">
                <ListTableCell>
                    <GuiActivityIndicator :active="contract['active']"/>
                    {{ contract['start_date'] }} - {{ contract['end_date'] }}
                </ListTableCell>
                <ListTableCell>
                    {{ contract['status'] }}
                </ListTableCell>
                <ListTableCell>
                    <div v-for="file in contract['files']">
                        <a class="link flex items-center" v-if="file['url']" :href="file['url']" :title="file['name']" target="_blank">
                            <IconFileType :type="file['type']" class="w-20px pr-5"/>
                            {{ file['name'] }}</a>
                    </div>
                </ListTableCell>
                <ListTableCell v-if="can('training_base.contracts.modify')">
                    <GuiActionsMenu :title="null">
                        <span class="link" @click="editDocument(contract)">Редактировать</span>
                        <span class="link" @click="remove(contract)">Удалить</span>
                    </GuiActionsMenu>
                </ListTableCell>
            </ListTableRow>
        </ListTable>

        <GuiMessage border v-else-if="contracts.is_loaded">Ничего не найдено</GuiMessage>

        <Pagination :pagination="contracts.pagination" @pagination="(page, per_page) => contracts.load(page, per_page)"/>

        <FormPopUp :title="''" :form="form" ref="popup">
            <FormDate :form="form" :name="'start_at'"/>
            <FormDate :form="form" :name="'end_at'" :from="form.values['start_at']"/>
            <FormDictionary :form="form" :dictionary="'training_base_contract_statuses'" :name="'status_id'" :placeholder="'Статус'"/>
            <FormFiles :form="form" :name="'files'"/>
        </FormPopUp>
    </LoadingProgress>
</template>

<script>
import list from "@/Core/List";
import LoadingProgress from "@/Components/LoadingProgress";
import LayoutFiltersItem from "@/Components/Layout/LayoutFiltersItem";
import LayoutFilters from "@/Components/Layout/LayoutFilters";
import DictionaryDropDown from "@/Components/Inputs/DictionaryDropDown";
import FormPopUp from "@/Components/FormPopUp";
import form from "@/Core/Form";
import GuiContainer from "@/Components/GUI/GuiContainer";
import GuiButton from "@/Components/GUI/GuiButton";
import DeleteEntry from "@/Mixins/DeleteEntry";
import Permissions from "@/Mixins/Permissions";
import FormDate from "@/Components/Form/FormDate";
import FormDictionary from "@/Components/Form/FormDictionary";
import FormFiles from "@/Components/Form/FormFiles";
import ListTable from "@/Components/ListTable/ListTable";
import ListTableRow from "@/Components/ListTable/ListTableRow";
import GuiMessage from "@/Components/GUI/GuiMessage";
import Pagination from "@/Components/Pagination";
import ListTableCell from "@/Components/ListTable/ListTableCell";
import GuiActivityIndicator from "@/Components/GUI/GuiActivityIndicator";
import IconFileType from "@/Components/FileTypeIcons/IconFileType";
import GuiActionsMenu from "@/Components/GUI/GuiActionsMenu";

export default {
    components: {
        GuiActionsMenu,
        IconFileType,
        GuiActivityIndicator,
        ListTableCell,
        Pagination,
        GuiMessage,
        ListTableRow,
        ListTable, FormFiles, FormDictionary, FormDate, GuiButton, GuiContainer, FormPopUp, DictionaryDropDown, LayoutFilters, LayoutFiltersItem, LoadingProgress
    },

    props: {
        baseId: {type: Number, required: true},
    },

    mixins: [DeleteEntry, Permissions],

    data: () => ({
        contracts: list('/api/training-base/contracts'),
        form: form(null, '/api/training-base/contracts/update'),
    }),

    created() {
        this.contracts.options = {base_id: this.baseId};
        this.contracts.initial();
        this.form.toaster = this.$toast;
    },

    methods: {
        editDocument(contract = null) {
            this.form.reset();
            this.form.set('start_at', contract !== null ? contract['start_at'] : null, 'required', 'Дата начала', true);
            this.form.set('end_at', contract !== null ? contract['end_at'] : null, 'required', 'Дата окончания', true);
            this.form.set('status_id', contract !== null ? contract['status_id'] : null, 'required', 'Статус', true);
            this.form.set('files', contract !== null ? contract['files'] : [], 'required', 'Документы', true);
            this.form.load();

            this.$refs.popup.show({base_id: this.baseId, id: contract !== null ? contract['id'] : 0}, contract === null ? 'Добавление документа' : 'Редактирование документа')
                .then(() => {
                    this.contracts.reload();
                });
        },
        remove(contract) {
            const name = contract['start_date'] + ' - ' + contract['end_date'];
            this.deleteEntry('Удалить документ ' + name + '?', '/api/training-base/contracts/delete', {id: contract['id']})
                .then(() => {
                    this.contracts.reload();
                });
        },
    }
}
</script>
