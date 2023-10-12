<template>
    <div v-if="can('subscriptions.create')" style="text-align: right; padding-bottom: 20px">
        <GuiActionsMenu>
            <span class="link" @click="editComment(null)">Добавить комментарий</span>
        </GuiActionsMenu>
    </div>

    <LoadingProgress :loading="ready && (list.is_loading || comment_form.is_loading || comment_form.is_saving)">
        <ListTable v-if="list.list && list.list.length > 0" :titles="list.titles" :has-action="can('clients.edit')">
            <ListTableRow v-for="comment in list.list">
                <ListTableCell>
                    {{ comment['created_at'] }}
                </ListTableCell>
                <ListTableCell>
                    {{ comment['text'] }}
                </ListTableCell>
                <ListTableCell>
                    {{ comment['type'] }}
                </ListTableCell>
                <ListTableCell>
                    {{ comment['action_type'] }}
                </ListTableCell>
                <ListTableCell>
                    {{ comment['position'] }}
                </ListTableCell>
                <ListTableCell v-if="comment['can_edit']"> <!-- внутренний комментарий -->
                    <GuiActionsMenu :title="null">
                        <span class="link" @click="editComment(comment['id'])">Редактировать</span>
                        <span class="link" @click="deleteComment(comment['id'])">Удалить</span>
                    </GuiActionsMenu>
                </ListTableCell>
            </ListTableRow>
        </ListTable>

        <GuiMessage border v-else-if="list.is_loaded">Ничего не найдено</GuiMessage>

        <Pagination :pagination="list.pagination" @pagination="(page, per_page) => list.load(page, per_page)"/>

        <FormPopUp :form="comment_form" ref="comment_edit" :save-button-caption="'Сохранить'">
            <FormText :form="comment_form" :name="'text'"/>
        </FormPopUp>
    </LoadingProgress>
</template>

<script>
import list from "@/Core/List";
import LoadingProgress from "@/Components/LoadingProgress";
import ListTable from "@/Components/ListTable/ListTable";
import ListTableRow from "@/Components/ListTable/ListTableRow";
import ListTableCell from "@/Components/ListTable/ListTableCell";
import GuiMessage from "@/Components/GUI/GuiMessage";
import Pagination from "@/Components/Pagination";
import Permissions from "../../../../Mixins/Permissions";
import GuiActionsMenu from "../../../../Components/GUI/GuiActionsMenu";
import form from "../../../../Core/Form";
import FormPopUp from "../../../../Components/FormPopUp";
import FormText from "../../../../Components/Form/FormText";
import FormDate from "../../../../Components/Form/FormDate";
import FormDictionary from "../../../../Components/Form/FormDictionary";
import ProcessEntry from "../../../../Mixins/ProcessEntry";

export default {
    props: {
        clientId: {type: Number, required: true},
        ready: {type: Boolean, default: true},
    },

    components: {
        FormDate,
        FormText,
        FormPopUp,
        FormDictionary,
        GuiActionsMenu,
        Pagination,
        GuiMessage,
        ListTableCell,
        ListTableRow,
        ListTable,
        LoadingProgress,
    },

    mixins: [Permissions, ProcessEntry],

    data: () => ({
        list: list('/api/clients/comments'),
        comment_form: form('/api/clients/comments/get', '/api/clients/comments/update'),
    }),

    created() {
        this.list.options = { client_id: this.clientId };
        this.list.initial();
        this.comment_form.toaster = this.$toast;
    },

    methods: {
        editComment(commentId) {
            this.comment_form.load({ client_id: this.clientId, comment_id: commentId })
                .then(() => {
                    this.$refs.comment_edit.show({client_id: this.clientId, comment_id: commentId })
                        .then(() => {
                            this.list.reload();
                        })
                })
        },
        deleteComment(commentId) {
            this.processEntry('Удалить комментарий?', 'Удалить', '/api/clients/comments/destroy', {
                comment_id: commentId,
            }).then(() => {
                this.list.reload();
            });
        },
    }
}
</script>
