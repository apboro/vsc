<template>
    <LoadingProgress :loading="this.list.is_loading">

        <LayoutFilters>
            <template #search>
                <GuiActionsMenu :class="'self-align-end'" :title="'Действия'">
                    <span class="link" @click="editInvoice(null)">Создать счет</span>
                </GuiActionsMenu>
            </template>
        </LayoutFilters>

        <GuiContainer w-100>
            <ListTable v-if="list.list.length > 0" :titles="list.titles" :has-action="true">
                <ListTableRow v-for="invoice in list.list">
                    <ListTableCell>
                        {{ invoice['id'] }}
                    </ListTableCell>
                    <ListTableCell>
                        {{ invoice['created_at'] }}
                    </ListTableCell>
                    <ListTableCell>
                        {{ invoice['subscription'] }}
                        <div class="text-sm text-gray">
                            {{ invoice['ward'] }}
                        </div>
                    </ListTableCell>
                    <ListTableCell>
                        {{ invoice['dates'] }}
                    </ListTableCell>
                    <ListTableCell>
                        {{ invoice['amount_to_pay'] }}
                    </ListTableCell>
                    <ListTableCell>
                        {{ invoice['status'] }}<br>
                        {{ invoice['paid_at'] }}
                    </ListTableCell>
                    <ListTableCell>
                        {{ invoice['amount_paid'] }}
                    </ListTableCell>
                    <ListTableCell>
                        {{ invoice['payment_type'] }}
                    </ListTableCell>
                    <ListTableCell>
                        <GuiActionsMenu :title="null">
                            <span class="link" @click="editInvoice(invoice)">Редактировать</span>
                            <span class="link" @click="removeInvoice(invoice)">Аннулировать</span>
                            <span class="link" @click="resendInvoice(invoice)">Отправить повторно</span>
                            <span class="link" @click="payFromAccount(invoice)">Списать с лицевого счета</span>
                            <span class="link" @click="showComment(invoice)">Просмотр комментария</span>
                        </GuiActionsMenu>
                    </ListTableCell>
                </ListTableRow>
            </ListTable>
            <GuiMessage v-else-if="list.is_loaded">Нет счетов за выбранный период</GuiMessage>
            <Pagination :pagination="list.pagination" @pagination="(page, per_page) => list.load(page, per_page)"/>
        </GuiContainer>

        <FormPopUp
            ref="popup_update"
            :title="(invoiceId ? 'Редактирование' : 'Создание')  + 'счёта'"
            :form="update_form"
            :save-button-caption="'Сохранить'"
        >
            <GuiContainer w-500px>
                <FormDate :form="update_form" :name="'created_at'" disabled/>

                <FormString :form="update_form" :name="'client'" disabled/>

                <FormDropdown :form="update_form" :name="'subscription_id'" :options="subscriptions" :identifier="'id'" :show="'name'" :hint="'ward'"/>
                <FormDropdown :form="update_form" :name="'contract_id'" :options="contracts" :identifier="'id'" :show="'name'" :disabled="!selectedSubscription"/>

                <FormDictionary :form="update_form" :dictionary="'invoice_types'" :name="'type_id'"/>

                <FormDate :form="update_form" :name="'date_from'" :disabled="invoiceType === 1"/>
                <FormDate :form="update_form" :name="'date_to'" :disabled="invoiceType === 1"/>

                <FormText :form="update_form" :name="'comment'"/>

                <div v-if="selectedContract">
                    <br>
                    <GuiText>Количество занятий: {{ selectedContract['trainings_count'] }}</GuiText>
                    <br>
                    <GuiText>Стоимость за одно занятие: {{ selectedContract['training_price'] }} руб.</GuiText>
                    <br>
                    <GuiText>Льгота: {{ selectedContract['discount'] }}</GuiText>
                    <br>
                    <GuiText>Сумма к оплате: {{ selectedContract['total_price'] }} руб.</GuiText>
                </div>

                <FormCheckBox hide-title without-title :form="update_form" :name="'pay_with_account'"/>
            </GuiContainer>
        </FormPopUp>

        <PopUp
            ref="comment_show"
            :message="commentToShow"
        />

    </LoadingProgress>
</template>

<script>
import list from "../../../../Core/List";
import form from "../../../../Core/Form";
import DeleteEntry from "@/Mixins/DeleteEntry";
import ProcessEntry from "@/Mixins/ProcessEntry";

import LayoutFilters from "@/Components/Layout/LayoutFilters";

import LoadingProgress from "@/Components/LoadingProgress";
import GuiContainer from "@/Components/GUI/GuiContainer";
import GuiHint from "@/Components/GUI/GuiHint";
import GuiMessage from "@/Components/GUI/GuiMessage";
import GuiActionsMenu from "@/Components/GUI/GuiActionsMenu";
import GuiText from "@/Components/GUI/GuiText";
import PopUp from "@/Components/PopUp";

import ListTable from "@/Components/ListTable/ListTable";
import Pagination from "@/Components/Pagination";
import ListTableRow from "@/Components/ListTable/ListTableRow";
import ListTableCell from "@/Components/ListTable/ListTableCell";

import FormPopUp from "@/Components/FormPopUp";
import FormNumber from "@/Components/Form/FormNumber";
import FormDictionary from "@/Components/Form/FormDictionary";
import FormDate from "@/Components/Form/FormDate";
import FormString from "@/Components/Form/FormString";
import FormText from "@/Components/Form/FormText";
import FormDropdown from "@/Components/Form/FormDropdown";
import FormCheckBox from "@/Components/Form/FormCheckBox";

export default {
    components: {
        LoadingProgress,
        GuiContainer,
        GuiHint,
        GuiMessage,
        GuiText,
        GuiActionsMenu,
        ListTable,
        Pagination,
        ListTableRow,
        ListTableCell,
        LayoutFilters,
        FormPopUp,
        FormNumber,
        FormDictionary,
        FormDate,
        FormString,
        FormText,
        FormDropdown,
        FormCheckBox,
        PopUp
    },

    mixins: [DeleteEntry, ProcessEntry],

    watch: {
        invoiceType(newVal) {
            if (newVal === 1) {
                this.update_form.values['date_from'] = this.update_form.payload['first_day_of_last_month']
                this.update_form.values['date_to'] = this.update_form.payload['last_day_of_last_month']
            }
        },
        selectedContract(newVal) {
            if (newVal) {
                this.update_form.values['amount_to_pay'] = newVal['total_price']
            }
        },
    },

    computed: {
        selectedSubscription() {
            return this.update_form.values['subscription_id']
        },
        selectedContract() {
            if (!this.update_form.values['contract_id']) return null

            return this.contracts.find(el => {
                return el.id === this.update_form.values['contract_id']
            })
        },
        invoiceType() {
            return this.update_form.values['type_id']
        },
        clients() {
            return this.update_form.payload['clients'] ? this.update_form.payload['clients'] : []
        },
        subscriptions() {
            return this.update_form.payload['subscriptions'] ? this.update_form.payload['subscriptions'] : []
        },
        contracts() {
            if (!this.selectedSubscription) return []

            let sub = this.subscriptions.find(el => {
                return el.id === this.selectedSubscription
            })

            if (sub) {
                return sub.contracts
            }

            return []
        },
    },

    data: () => ({
        list: list('/api/invoices/list'),
        update_form: form('/api/invoices/get', '/api/invoices/save'),
        invoiceId: null,
        commentToShow: null,
    }),

    props: {
        clientId: { required: true, type: Number },
    },

    created() {
        this.list.options = { client_id: this.clientId }
        this.list.initial()

        this.update_form.toaster = this.$toast
    },

    methods: {
        editInvoice(invoice) {
            this.invoiceId = invoice ? invoice['id'] : 0
            // update dicts
            this.update_form.load({ client_id: this.clientId, invoice_id: this.invoiceId })
                .then(() => {
                    this.$refs.popup_update.show({ client_id: this.clientId })
                        .then(() => {
                            this.list.reload()
                        })
                })
        },
        removeInvoice(invoice) {
            this.deleteEntry(
                'Удалить счет?',
                '/api/invoices/remove',
                { id: invoice['id'] }
            )
                .then(() => {
                    this.list.reload()
                });
        },
        payFromAccount(invoice) {
            this.processEntry(
                'Внести наличные на лицевой счет и списать для оплаты счета?',
                'Продолжить',
                '/api/invoices/pay_from_account',
                { id: invoice['id'] }
            )
                .then(() => {
                    this.list.reload()
                });
        },
        showComment(invoice) {
            this.commentToShow = invoice['comment']
            this.$refs.comment_show.show()
        },
        resendInvoice(invoice) {
            this.processEntry(
                'Отправить счет клиенту?',
                'Отправить',
                '/api/invoices/resend',
                { id: invoice['id'] }
            )
                .then(() => {
                    this.list.reload()
                });
        }
    },
}
</script>

<style scoped>

</style>