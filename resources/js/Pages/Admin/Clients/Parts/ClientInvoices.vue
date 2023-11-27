<template>
    <LoadingProgress :loading="this.list.is_loading">

        <LayoutFilters>
            <template #search>
                <GuiActionsMenu v-if="list.payload['can_create']" :class="'self-align-end'" :title="'Действия'">
                    <span class="link" v-if="list.payload['can_create']" @click="editInvoice(null)">Создать счет</span>
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
                        {{ invoice['status'] }}
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
                        {{ invoice['status_id'] === 5 ? '-' : invoice['payment_status'] }}<br>
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
                            <span v-if="invoice['can_edit']" class="link" @click="editInvoice(invoice)">Редактировать</span>
                            <span v-if="invoice['can_remove']" class="link" @click="removeInvoice(invoice)">Аннулировать</span>
                            <span v-if="invoice['can_resend']" class="link" @click="resendInvoice(invoice)">Отправить повторно</span>
                            <span v-if="invoice['can_pay_by_account']" class="link" @click="payFromAccount(invoice)">Списать с лицевого счета</span>
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
            :title="(invoiceId ? 'Редактирование' : 'Создание')  + ' счёта'"
            :form="update_form"
            :save-button-caption="'Сохранить'"
        >
            <GuiContainer w-700px>
                <FormDate :form="update_form" :name="'created_at'" disabled/>

                <FormString :form="update_form" :name="'client'" disabled/>

                <FormDropdown :form="update_form" :name="'subscription_id'" :options="subscriptions" :identifier="'id'" :show="'name'" :hint="'ward'"/>
                <FormDropdown :form="update_form" :name="'contract_id'" :options="contracts" :identifier="'id'" :show="'name'" :disabled="!selectedSubscription"/>

                <FormDictionary :form="update_form" :dictionary="'invoice_types'" :name="'type_id'" :disabled="!selectedContract"/>

                <FieldWrapper
                    :title="'Период'"
                >
                    <div
                        style="display: flex; flex-direction: column"
                    >
                        <div style="display: flex;">
                            <span class="input-field__title" style="width:3px">С</span>
                            <span class="input-field__title" style="padding-left: 50%">По</span>
                        </div>
                        <div style="display: flex">
                            <FormDate hide-title :form="update_form" :name="'date_from'" :disabled="!invoiceType || invoiceType === 1"/>
                            <FormDate hide-title :form="update_form" :name="'date_to'" :disabled="!invoiceType || invoiceType === 1"/>
                        </div>
                    </div>
                </FieldWrapper>

                <FormText :form="update_form" :name="'comment'"/>

                <div v-if="selectedContract">
                    <template v-if="invoiceType === 1">
                        <br>
                        <GuiText>Количество занятий: {{ selectedContract['trainings_count'] }}</GuiText>
                        <br>
                        <GuiText>Стоимость за одно занятие: {{ selectedContract['training_price'] }} руб.</GuiText>
                        <br>
                        <GuiText>Льгота: {{ selectedContract['discount'] }}</GuiText>
                        <br v-if="paidFromAccount">
                        <GuiText v-if="paidFromAccount">Оплачено с ЛС: {{ paidFromAccount }} руб.</GuiText>
                        <br>
                        <GuiText>Сумма к оплате: {{ totalPrice }} руб.</GuiText>
                    </template>
                    <template v-else-if="invoiceType === 2">
                        <br>
<!--                        <GuiText>Всего занятий за период: {{ selectedContract['trainings_count'] }}</GuiText>-->
                        <GuiText>Всего занятий за период: {{ trainingsCountForSelectedPeriod }}</GuiText>
                        <br>
                        <FormNumber :form="update_form" :name="'trainings_attended'" :max="selectedContract['trainings_count']" :min="0"/>
                        <br>
                        <FormNumber :form="update_form" :name="'one_time_discount'" :min="0" :max="100"/>
                        <br>
                        <GuiText>
                            Стоимость за {{ trainingsAttended }} занятия по базовой ставке: {{ recalcPriceTotalAmount }} руб.
                            <a href="" @click.prevent="update_form.values['recalc_method'] = 1">
                                {{ recalcMethod === 1 ? 'Применено' : 'Применить' }}
                            </a>
                        </GuiText>
                        <br>
                        <GuiText>
                            Стоимость за {{ trainingsAttended }} занятия с учетом перерасчета: {{ basePriceTotalAmount }} руб.
                            <a href="" @click.prevent="update_form.values['recalc_method'] = 2">
                                {{ recalcMethod === 2 ? 'Применено' : 'Применить' }}
                            </a>
                        </GuiText>
                        <br v-if="paidFromAccount">
                        <GuiText v-if="paidFromAccount">Оплачено с ЛС: {{ paidFromAccount }} руб.</GuiText>
                        <br>
                        <GuiText>Сумма к оплате: {{ totalPrice }} руб.</GuiText>
                    </template>
                </div>

                <FormCheckBox hide-title without-title :form="update_form" :name="'pay_with_account'"/>
            </GuiContainer>
        </FormPopUp>

        <PopUp
            ref="comment_show"
        >
            <div>
                <div style="padding-bottom: 15px" v-if="commentsToShow['comment']">
                    <GuiHeading>Комментарий при создании:</GuiHeading>
                    <GuiText>{{ commentsToShow['comment'] }}</GuiText>
                </div>
                <div v-if="commentsToShow['delete_comment']">
                    <GuiHeading>Комментарий при аннулировании:</GuiHeading>
                    <GuiText>{{ commentsToShow['delete_comment'] }}</GuiText>
                </div>
            </div>
        </PopUp>

        <FormPopUp
            ref="popup_delete"
            :title="'Аннулировать счёт?'"
            :save-button-caption="'Аннулировать'"
            :form="delete_form"
        >
            <FormText :form="delete_form" :name="'delete_comment'"/>
        </FormPopUp>
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
import FieldWrapper from "@/Components/Fields/Helpers/FieldWrapper";

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
        PopUp,
        FieldWrapper,
    },

    mixins: [DeleteEntry, ProcessEntry],

    watch: {
        invoiceType(newVal) {
            if (this.selectedContract) {
                this.update_form.values['amount_to_pay'] = this.selectedContract['total_price']
            }

            try {
                this.updateSelectedDates(newVal)
            } catch (e) {
                this.$toast.error(e)
            }
        },
        selectedContract(newVal, oldVal) {
            if (newVal) {
                if (this.invoiceType === 1) {  //  Базовый счет
                    this.update_form.values['amount_to_pay'] = newVal['total_price']
                } else if (this.invoiceType === 2) {  //  Перерасчет
                    this.update_form.values['amount_to_pay'] = (this.recalcMethod === 1 ? this.basePriceTotalAmount : this.recalcPriceTotalAmount)
                }
            }
            try {
                this.updateSelectedDates(this.invoiceType)
            } catch (e) {
                this.$toast.error(e)
            }
        },
        recalcMethod(newVal) {
            if (this.selectedContract) {
                this.update_form.values['amount_to_pay'] = (this.recalcMethod === 1 ? this.basePriceTotalAmount : this.recalcPriceTotalAmount)
            }
        },
        trainingsAttended(newVal) {
            if (this.selectedContract) {
                this.update_form.values['amount_to_pay'] = (this.recalcMethod === 1 ? this.basePriceTotalAmount : this.recalcPriceTotalAmount)
            }
        },
        oneTimeDiscount(newVal) {
            if (this.selectedContract) {
                this.update_form.values['amount_to_pay'] = (this.recalcMethod === 1 ? this.basePriceTotalAmount : this.recalcPriceTotalAmount)
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
        trainingsAttended() {
            return this.update_form.values['trainings_attended'] !== undefined && this.update_form.values['trainings_attended'] !== null ?
                this.update_form.values['trainings_attended'] :
                null
        },
        oneTimeDiscount() {
            return this.update_form.values['one_time_discount'] !== undefined && this.update_form.values['one_time_discount'] !== null ?
                this.update_form.values['one_time_discount'] :
                null
        },
        recalcPriceTotalAmount() {
            return this.recalculateAmount(this.selectedContract['training_price'])
        },
        basePriceTotalAmount() {
            return this.recalculateAmount(this.selectedContract['training_price_recalc'])
        },
        totalPrice() {
            let total

            if (this.update_form.values['amount_to_pay']) {
                total = this.update_form.values['amount_to_pay']
                total -= this.paidFromAccount
            } else {
                total = this.update_form.originals['amount_to_pay']
            }

            return total
        },
        paidFromAccount() {
            return this.update_form.originals['paid_from_account']
        },
        recalcMethod() {
            return this.update_form.values['recalc_method']
        },
        trainingsCountForSelectedPeriod() {
            let fromDate = this.update_form.values['date_from']
            let toDate = this.update_form.values['date_to']

            //  выходим, если не выбраны даты или контракт
            if (!fromDate || !toDate || !this.selectedContract) {
                return
            }
            //  создаем Date объекты
            fromDate = new Date(fromDate)
            toDate = new Date(toDate)
            //  считаем количество дней недели за период
            let weekDaysCount = { 0: 0, 1: 0, 2: 0, 3: 0, 4: 0, 5: 0, 6: 0, }

            while (fromDate <= toDate) {
                //  счет начинается с воскресенья
                let currentDay = fromDate.getDay() - 1
                if (currentDay === -1) currentDay = 6

                weekDaysCount[currentDay] += 1
                fromDate.setDate(fromDate.getDate() + 1)
            }
            //  считаем количество тренировок за период
            let count = 0
            console.log(this.selectedContract['schedule'])
            for (let i = 0; i < 7; i++) {
                console.log(weekDaysCount[i], this.selectedContract['schedule'][i])
                count += weekDaysCount[i] * this.selectedContract['schedule'][i]
            }

            //  обновляем значение в поле "Занятий посещено" если оно не определено
            if (!this.trainingsAttended) {
                this.update_form.values['trainings_attended'] = count
            }

            return count
        },
    },

    data: () => ({
        list: list('/api/invoices/list'),
        update_form: form('/api/invoices/get', '/api/invoices/save'),
        delete_form: form(null, '/api/invoices/remove'),
        invoiceId: null,
        commentsToShow: {},
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

            this.update_form.load({ client_id: this.clientId, invoice_id: this.invoiceId })
                .then(() => {
                    this.$refs.popup_update.show({ client_id: this.clientId })
                        .then(() => {
                            this.list.reload()
                        })
                })
        },
        removeInvoice(invoice) {
            this.delete_form.set('delete_comment', null, 'required', 'Комментарий', true)
            this.delete_form.set('id', invoice['id'], null, null, true)
            this.delete_form.params
            this.delete_form.load()
            this.$refs.popup_delete.show().then(() => {
                this.list.reload()
            })
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
            this.commentsToShow = {
                comment: invoice['comment'],
                delete_comment: invoice['delete_comment']
            }
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
        },
        updateSelectedDates(invoiceType) {
            //  базовый счет
            if (invoiceType === 1) {
                //  Если контракт заключен ранее первого дня последнего месяца базовый счет создать нельзя
                if (this.selectedContract['start_at'] > this.update_form.payload['first_day_of_last_month']) {
                    throw new Error('Нельзя создать базовый счет для этого контракта. Контракт заключен позднее первого дня прошедшего месяца.')
                }

                //  ставим фиксированные даты - с даты начала договора или первого дня прошедшего месяца по последний день прошедшего месяца
                this.update_form.values['date_from'] = this.update_form.payload['first_day_of_last_month']
                this.update_form.values['date_to'] = this.update_form.payload['last_day_of_last_month']
            }
        },
        recalculateAmount(trainingPrice) {
            if (this.trainingsAttended === null || this.oneTimeDiscount === null) {
                return null
            }

            return Math.ceil(trainingPrice * this.trainingsAttended * (1 - this.oneTimeDiscount / 100))
        },
    },
}
</script>

<style scoped>

</style>