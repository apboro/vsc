<template>
    <LoadingProgress :loading="ready && (list.is_loading || processing)">
        <div style="text-align: right; margin-bottom: 10px;">
            <GuiActionsMenu v-if="can('subscriptions.create.document') && subscriptionRepeatable">
                <span class="link" @click="sendLink">Отправить ссылку на заполнение договора</span>
                </GuiActionsMenu>
        </div>
        <ListTable v-if="list.list && list.list.length > 0" :titles="list.titles" :has-action="true">
            <ListTableRow v-for="document in list.list">
                <ListTableCell>
                    {{ document['id'] }}
                </ListTableCell>
                <ListTableCell>
                    {{ document['title'] }}
                    (
                    <a class="link" :href="'/api/subscriptions/documents/view/'+document['id']" target="_blank">просмотр</a>
                    /
                    <span class="link" @click="save(document)">скачать</span>
                    )
                </ListTableCell>
                <ListTableCell>
                    {{ document['status'] }}
                </ListTableCell>
                <ListTableCell>
                    <span v-if="document['discount']">{{ document['discount_amount'] }}%, {{ document['discount'] }}</span>
                    <span v-else>нет</span>
                </ListTableCell>
                <ListTableCell>
                    {{ document['start_at'] ? document['start_at'] : '—' }}
                </ListTableCell>
                <ListTableCell>
                    {{ document['end_at'] ? document['end_at'] : '—' }}
                </ListTableCell>
                <ListTableCell>
                    <GuiActionsMenu :title="null" v-if="document['is_acceptable'] || document['is_repeatable'] || document['is_closeable']">
                        <span class="link" v-if="document['is_editable']" @click="edit(document)">Изменить данные</span>
                        <span class="link" v-if="document['is_client_data_updatable']" @click="updateClientData(document)">Обновить данные клиента</span>
                        <span class="link" v-if="document['is_acceptable']" @click="accept(document)">Подтвердить данные</span>
                        <span class="link" v-if="document['is_repeatable']" @click="resend(document)">Отправить договор повторно</span>
                        <span class="link" v-if="document['is_closeable']" @click="close(document)">Закрыть договор</span>
                    </GuiActionsMenu>
                </ListTableCell>
            </ListTableRow>
        </ListTable>

        <GuiMessage border v-else-if="list.is_loaded">Ничего не найдено</GuiMessage>

        <Pagination :pagination="list.pagination" @pagination="(page, per_page) => list.load(page, per_page)"/>

        <FormPopUp :form="form" :title="''" :save-button-caption="create ? 'Сформировать договор и отправить клиенту' : 'Сохранить'" :scrollable="true" ref="form">
            <GuiContainer w-600px>
                <GuiHeading mb-15>Данные клиента</GuiHeading>
                <GuiContainer mb-15>
                    <FormString :form="form" :name="'lastname'"/>
                    <FormString :form="form" :name="'firstname'"/>
                    <FormString :form="form" :name="'patronymic'"/>
                    <FormPhone :form="form" :name="'phone'"/>
                    <FormString :form="form" :name="'email'"/>
                </GuiContainer>
                <GuiContainer mb-15>
                    <FormString :form="form" :name="'passport_serial'"/>
                    <FormString :form="form" :name="'passport_number'"/>
                    <FormString :form="form" :name="'passport_place'"/>
                    <FormDate :form="form" :name="'passport_date'"/>
                    <FormString :form="form" :name="'passport_code'"/>
                    <FormString :form="form" :name="'registration_address'"/>
                </GuiContainer>
                <GuiHeading mb-15>Данные занимающегося</GuiHeading>
                <GuiContainer>
                    <FormString :form="form" :name="'ward_lastname'"/>
                    <FormString :form="form" :name="'ward_firstname'"/>
                    <FormString :form="form" :name="'ward_patronymic'"/>
                    <FormDate :form="form" :name="'ward_birth_date'"/>
                    <FormString :form="form" :name="'ward_document'"/>
                    <FormDate :form="form" :name="'ward_document_date'"/>
                    <FormDictionary :form="form" :name="'discount_id'" :dictionary="'discounts'" :top="true" :placeholder="'Без льготы'" :has-null="true"/>
                </GuiContainer>
            </GuiContainer>
        </FormPopUp>
    </LoadingProgress>
</template>

<script>
import list from "@/Core/List";
import GuiActionsMenu from "@/Components/GUI/GuiActionsMenu";
import ListTable from "@/Components/ListTable/ListTable";
import ListTableRow from "@/Components/ListTable/ListTableRow";
import ListTableCell from "@/Components/ListTable/ListTableCell";
import GuiMessage from "@/Components/GUI/GuiMessage";
import Pagination from "@/Components/Pagination";
import LoadingProgress from "@/Components/LoadingProgress";
import FormPopUp from "@/Components/FormPopUp";
import form from "@/Core/Form";
import GuiContainer from "@/Components/GUI/GuiContainer";
import GuiHeading from "@/Components/GUI/GuiHeading";
import FormString from "@/Components/Form/FormString";
import FormPhone from "@/Components/Form/FormPhone";
import FormDate from "@/Components/Form/FormDate";
import {saveAs} from "file-saver";
import FormDictionary from "../../../../Components/Form/FormDictionary";
import ProcessEntry from "../../../../Mixins/ProcessEntry";
import Permissions from "../../../../Mixins/Permissions";

export default {
    props: {
        subscriptionId: {type: Number, default: null},
        subscriptionRepeatable: {type: Boolean, default: false},
        ready: {type: Boolean, default: true},
    },

    emits: ['update'],

    mixins: [ProcessEntry, Permissions],

    components: {
        FormDictionary,
        FormDate,
        FormPhone,
        FormString,
        GuiHeading,
        GuiContainer,
        FormPopUp,
        LoadingProgress,
        GuiActionsMenu,
        ListTable,
        ListTableRow,
        ListTableCell,
        GuiMessage,
        Pagination,
    },

    data: () => ({
        list: list('/api/subscriptions/documents'),
        form: form('/api/subscriptions/documents/get', '/api/subscriptions/documents/update'),
        create: false,
    }),

    created() {
        this.list.options = {id: this.subscriptionId};
        this.list.initial();
        this.form.toaster = this.$toast;
    },

    methods: {
        reload() {
            this.list.reload();
        },

        accept(document) {
            this.create = true;
            this.form.load({id: document['id'], subscription_id: this.subscriptionId});
            this.$refs.form.show({id: document['id'], subscription_id: this.subscriptionId, create: true})
                .then(() => {
                    this.list.reload();
                    this.$emit('update');
                });
        },

        edit(document) {
            this.create = false;
            this.form.load({id: document['id'], subscription_id: this.subscriptionId});
            this.$refs.form.show({id: document['id'], subscription_id: this.subscriptionId, create: false})
                .then(() => {
                    this.list.reload();
                    this.$emit('update');
                });
        },

        resend(document) {
            this.processEntry('Отправить повторно <b>' + document['title'] + '</b> на почту клиента?', 'Отправить повторно', '/api/subscriptions/documents/resend', {
                id: document['id'],
                subscription_id: this.subscriptionId
            }).then(() => {
                this.$emit('update');
            });
        },

        close(document) {
            this.processEntry('Закрыть <b>' + document['title'] + '</b>?', 'Закрыть договор', '/api/subscriptions/documents/close', {
                id: document['id'],
                subscription_id: this.subscriptionId
            })
                .then(() => {
                    this.list.reload();
                    this.$emit('update');
                });
        },

        save(document) {
            axios.post('/api/subscriptions/documents/download', {id: document['id']})
                .then(response => {
                    let contract = atob(response.data.data['contract']);
                    let byteNumbers = new Array(contract.length);
                    for (let i = 0; i < contract.length; i++) {
                        byteNumbers[i] = contract.charCodeAt(i);
                    }
                    let byteArray = new Uint8Array(byteNumbers);
                    let blob = new Blob([byteArray], {type: "application/pdf;charset=utf-8"});

                    saveAs(blob, response.data.data['file_name'], {autoBom: true});
                })
                .catch(error => {
                    this.$toast.error(error.response.data['message']);
                });
        },

        sendLink() {
            this.processEntry('Отправить ссылку на заполнение договора повторно на почту клиента?', 'Отправить повторно',
                '/api/subscriptions/documents/send_link', {subscription_id: this.subscriptionId})
                .then(() => {
                    this.$emit('update');
                });
        },

        updateClientData(document) {
            this.processEntry('Обновить данные клиента?', 'Обновить',
                '/api/subscriptions/documents/update_client_data', {client_id: document['client_id']})
                .then(() => {
                    this.$emit('update');
                });
        },
    }
}
</script>
