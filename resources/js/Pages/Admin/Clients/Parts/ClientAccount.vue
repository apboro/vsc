<template>
    <LoadingProgress :loading="this.list.is_loading">
        <GuiContainer mt-15 mb-15 border p-20>
            <GuiContainer inline mr-50>
                <GuiHeading text-md mb-10>Баланс лицевого счёта</GuiHeading>
                <GuiHeading>{{ list.payload['balance'] }} руб.</GuiHeading>
            </GuiContainer>
            <!--
            <GuiContainer inline>
                <GuiHeading text-md mb-10>Доступный остаток по лицевому счёту</GuiHeading>
                <GuiHeading>{{ list.payload['balance'] - list.payload['limit'] }} руб.</GuiHeading>
            </GuiContainer>
            -->
        </GuiContainer>

        <LayoutFilters>
            <LayoutFiltersItem :title="'Период'">
                <InputDate
                    v-model="list.filters['date_from']"
                    :original="list.filters_original['date_from']"
                    @change="list.load()"
                />
                <InputDate
                    v-model="list.filters['date_to']"
                    :original="list.filters_original['date_to']"
                    :from="list.filters['date_from']"
                    @change="list.load()"
                />
            </LayoutFiltersItem>
            <LayoutFiltersItem :title="'Тип операции'">
                <DictionaryDropDown
                    v-model="list.filters['transaction_type_id']"
                    :dictionary="'transaction_primary_types'"
                    :original="list.filters_original['transaction_type_id']"
                    :placeholder="'Все'"
                    :has-null="true"
                    @change="list.load()"
                />
            </LayoutFiltersItem>
            <template #search>
                <GuiActionsMenu :class="'self-align-end'" :title="'Операции'">
                    <span class="link" @click="editRefill(null)">Пополнение счёта</span>
                    <span class="link" @click="editWithdrawal(null)">Списание баланса</span>
                </GuiActionsMenu>
            </template>
        </LayoutFilters>

        <GuiContainer w-100>
            <ListTable v-if="list.list.length > 0" :titles="list.titles" :has-action="true">
                <ListTableRow v-for="transaction in list.list">
                    <ListTableCell>
                        {{ transaction['timestamp'] }}
                    </ListTableCell>
                    <ListTableCell>
                        {{ transaction['type'] }}
                    </ListTableCell>
                    <ListTableCell :nowrap="true" :class="{'text-dark-green': transaction['sign'] === 1, 'text-dark-red': transaction['sign'] === -1}">
                        {{ transaction['sign'] === -1 ? '-' : '+' }} {{ transaction['amount'] }} руб.
                    </ListTableCell>
                    <ListTableCell>
                        <template v-if="transaction['reason_raw']">
                            {{ transaction['reason_raw']['title'] }}
                            <template v-if="transaction['reason_raw']['object'] === 'order'">
                                <router-link v-if="transaction['reason_raw']['object_id']" class="link"
                                             :to="{name: 'order-info', params: {id: transaction['reason_raw']['object_id'] }}">
                                    {{ transaction['reason_raw']['caption'] }}
                                </router-link>
                                <template v-else>
                                    {{ transaction['reason_raw']['caption'] }}
                                </template>
                            </template>
                            <template v-else-if="transaction['reason_raw']['object'] === 'ticket'">
                                <router-link v-if="transaction['reason_raw']['object_id']" class="link"
                                             :to="{name: 'ticket-info', params: {id: transaction['reason_raw']['object_id'] }}">
                                    {{ transaction['reason_raw']['caption'] }}
                                </router-link>
                                <template v-else>
                                    {{ transaction['reason_raw']['caption'] }}
                                </template>
                            </template>
                        </template>
                        <GuiHint v-if="transaction['comments']" mt-10>{{ transaction['comments'] }}</GuiHint>
                    </ListTableCell>
                    <ListTableCell :nowrap="true">
                        {{ transaction['operator'] }}
                    </ListTableCell>
                    <ListTableCell>
                        <GuiActionsMenu :title="null" v-if="transaction['editable'] || transaction['deletable']">
                            <span class="link" v-if="transaction['editable'] && transaction['type_id'] !== 4" @click="editRefill(transaction)">Редактировать</span>
                            <span class="link" v-if="transaction['editable'] && transaction['type_id'] === 4" @click="editWithdrawal(transaction)">Редактировать</span>
                            <span class="link" v-if="transaction['deletable']" @click="remove(transaction)">Удалить</span>
                        </GuiActionsMenu>
                    </ListTableCell>
                </ListTableRow>
            </ListTable>
            <GuiMessage v-else-if="list.is_loaded">Нет операций за выбранный период</GuiMessage>
            <Pagination :pagination="list.pagination" @pagination="(page, per_page) => list.load(page, per_page)"/>
        </GuiContainer>

        <FormPopUp
            ref="popup_refill"
            :title="'Пополнение счёта'"
            :form="refill_form"
            :options="{client_id: this.clientId, transactionId: transaction}"
        >
            <GuiContainer w-500px>
                <FormDictionary :form="refill_form" :dictionary="'transaction_refill_types'" :name="'type_id'" @change="val => typeChanged(val, true)" :disabled="transaction !== 0"/>
                <FormDate :form="refill_form" :name="'timestamp'" v-if="refill_form.values['type_id'] !== null"/>
                <FormString :form="refill_form" :name="'reason'" v-if="has_reason && refill_form.values['type_id'] !== null"/>
                <FormDate :form="refill_form" :name="'reason_date'" v-if="has_reason_date && refill_form.values['type_id'] !== null"/>
                <FormNumber :form="refill_form" :name="'amount'" :type="'number'" v-if="refill_form.values['type_id'] !== null"/>
                <FormText :form="refill_form" :name="'comments'" v-if="refill_form.values['type_id'] !== null"/>
            </GuiContainer>
        </FormPopUp>

        <FormPopUp
            ref="popup_withdrawal"
            :title="'Списание баланса'"
            :form="withdrawal_form"
            :options="{client_id: this.clientId, transactionId: transaction}"
        >
            <GuiContainer w-500px>
                <FormDictionary :form="withdrawal_form" :dictionary="'transaction_withdrawal_types'" :name="'type_id'" @change="val => typeChanged(val, false)" :disabled="transaction !== 0"/>
                <FormDate :form="withdrawal_form" :name="'timestamp'" v-if="withdrawal_form.values['type_id'] !== null"/>
                <FormString :form="withdrawal_form" :name="'reason'" v-if="has_reason && withdrawal_form.values['type_id'] !== null"/>
                <FormDate :form="withdrawal_form" :name="'reason_date'" v-if="has_reason_date && withdrawal_form.values['type_id'] !== null"/>
                <FormNumber :form="withdrawal_form" :name="'amount'" :type="'number'" v-if="withdrawal_form.values['type_id'] !== null"/>
                <FormText :form="withdrawal_form" :name="'comments'" v-if="withdrawal_form.values['type_id'] !== null"/>
            </GuiContainer>
        </FormPopUp>
    </LoadingProgress>
</template>

<script>
import list from "@/Core/List";
import form from "@/Core/Form";

import deleteEntry from "@/Mixins/DeleteEntry";

import LayoutFilters from "@/Components/Layout/LayoutFilters";
import LayoutFiltersItem from "@/Components/Layout/LayoutFiltersItem";
import InputDate from "@/Components/Inputs/InputDate";
import DictionaryDropDown from "@/Components/Inputs/DictionaryDropDown";

import FormPopUp from "@/Components/FormPopUp";
import FormNumber from "@/Components/Form/FormNumber";
import FormDictionary from "@/Components/Form/FormDictionary";
import FormDate from "@/Components/Form/FormDate";
import FormString from "@/Components/Form/FormString";
import FormText from "@/Components/Form/FormText";

import LoadingProgress from "@/Components/LoadingProgress";
import GuiContainer from "@/Components/GUI/GuiContainer";
import GuiHeading from "@/Components/GUI/GuiHeading";
import GuiHint from "@/Components/GUI/GuiHint";
import GuiValue from "@/Components/GUI/GuiValue";
import GuiMessage from "@/Components/GUI/GuiMessage";
import GuiActionsMenu from "@/Components/GUI/GuiActionsMenu";

import ListTable from "@/Components/ListTable/ListTable";
import Pagination from "@/Components/Pagination";
import ListTableRow from "@/Components/ListTable/ListTableRow";
import ListTableCell from "@/Components/ListTable/ListTableCell";

export default {
    components: {
        LoadingProgress,
        GuiContainer,
        GuiHeading,
        GuiHint,
        GuiValue,
        GuiMessage,
        GuiActionsMenu,
        ListTable,
        Pagination,
        ListTableRow,
        ListTableCell,
        FormPopUp,
        FormDate,
        FormString,
        FormDictionary,
        FormText,
        FormNumber,
        LayoutFilters,
        LayoutFiltersItem,
        InputDate,
        DictionaryDropDown,
    },

    props: {
        clientId: { type: Number, required: true },
    },

    mixins: [deleteEntry],

    data: () => ({
        list: list('/api/account'),

        refill_form: form(null, '/api/account/refill'),
        withdrawal_form: form(null, '/api/account/withdrawal'),
        transaction: 0,
        has_reason: false,
        has_reason_date: false,
    }),

    created() {
        this.list.options = { client_id: this.clientId }
        this.list.initial()

        this.refill_form.toaster = this.$toast;
        this.withdrawal_form.toaster = this.$toast;
    },

    methods: {
        editRefill(transaction = null) {
            this.transaction = transaction ? transaction['id'] : 0;
            this.$store.dispatch('dictionary/refresh', 'transaction_refill_types')
                .then(() => {
                    this.refill_form.reset();
                    this.refill_form.set('type_id', transaction ? transaction['type_id'] : null, 'required', 'Способ пополнения', true);
                    this.refill_form.set('timestamp', transaction ? transaction['date'] : null, 'required', 'Дата операции', true);
                    this.refill_form.set('reason', transaction ? transaction['reason'] : null, null, 'Номер счёта', true);
                    this.refill_form.set('reason_date', transaction ? transaction['reason_date'] : null, null, 'Дата счёта', true);
                    this.refill_form.set('amount', transaction ? transaction['amount'] : null, 'required|numeric|min:1|bail', 'Сумма', true);
                    this.refill_form.set('comments', transaction ? transaction['comments'] : null, null, 'Комментарии', true);

                    if (transaction) {
                        this.typeChanged(transaction['type_id'], true)
                    }
                    this.refill_form.load();

                    this.$refs.popup_refill.show()
                        .then(() => {
                            this.list.load();
                        });
                });
        },

        editWithdrawal(transaction = null) {
            this.transaction = transaction ? transaction['id'] : 0;
            this.$store.dispatch('dictionary/refresh', 'transaction_withdrawal_types')
                .then(() => {
                    this.withdrawal_form.reset();
                    this.withdrawal_form.set('type_id', transaction ? transaction['type_id'] : null, 'required', 'Способ списания', true);
                    this.withdrawal_form.set('timestamp', transaction ? transaction['date'] : null, 'required', 'Дата операции', true);
                    this.withdrawal_form.set('reason', transaction ? transaction['reason'] : null, null, 'Номер счёта', true);
                    this.withdrawal_form.set('reason_date', transaction ? transaction['reason_date'] : null, null, 'Дата счёта', true);
                    this.withdrawal_form.set('amount', transaction ? transaction['amount'] : null, 'required|numeric|min:1|bail', 'Сумма', true);
                    this.withdrawal_form.set('comments', transaction ? transaction['comments'] : null, null, 'Комментарии', true);

                    if (transaction) {
                        this.typeChanged(transaction['type_id'], false)
                    }
                    this.withdrawal_form.load();

                    this.$refs.popup_withdrawal.show()
                        .then(() => {
                            this.list.load();
                        });
                });
        },

        typeChanged(value, isRefill = true) {
            let type = null;

            let dictName = isRefill ? 'transaction_refill_types' : 'transaction_withdrawal_types'

            this.$store.getters['dictionary/dictionary'](dictName).some(item => {
                if (item['id'] === value) {
                    type = item;
                    return true;
                }
                return false;
            });
            if (isRefill) {
                this.refill_form.set('reason', this.refill_form.values['reason'], type['has_reason'] ? 'required' : null, type['reason_title'], true);
                this.refill_form.set('reason_date', this.refill_form.values['reason_date'], type['has_reason_date'] ? 'required' : null, type['reason_date_title'], true);
            } else {
                this.withdrawal_form.set('reason', this.withdrawal_form.values['reason'], type['has_reason'] ? 'required' : null, type['reason_title'], true);
                this.withdrawal_form.set('reason_date', this.withdrawal_form.values['reason_date'], type['has_reason_date'] ? 'required' : null, type['reason_date_title'], true);
            }
            this.has_reason = Boolean(type['has_reason']);
            this.has_reason_date = Boolean(type['has_reason_date']);
        },

        remove(transaction) {
            this.deleteEntry(
                'Удалить операцию "' + transaction['type'] + '" от ' + transaction['date'] + '?',
                '/api/account/delete',
                {id: transaction.id})
                .then(() => {
                    this.list.load();
                });
        },
    }
}
</script>

<style scoped>

</style>
