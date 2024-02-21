<template>
    <div>
        <GuiContainer mt-30>
            <GuiValue :title="'Создан'">{{ data['created_date'] }}, {{ data['created_time'] }}</GuiValue>
            <GuiValue :title="'Статус'">{{ data['status'] }}</GuiValue>
            <GuiValue :title="'Район'">{{ data['region'] }}</GuiValue>
            <GuiValue v-if="data['need_help']" :title="'Нужна помощь с выбором'"><span class="text-red">да</span></GuiValue>
            <template v-else>
                <GuiValue :title="'Услуга'">{{ data['service'] }}</GuiValue>
                <GuiValue :title="'Объект'">{{ data['training_base'] }}</GuiValue>
                <GuiValue :title="'Адрес'">{{ data['training_base_address'] }}</GuiValue>
                <GuiValue :title="'Вид спорта'">{{ data['sport_kind'] }}</GuiValue>
            </template>
            <GuiValue :title="'Клиент'">
                <RouterLink v-if="data['client']" class="link" :to="{name: 'clients-view', params: {id: data['client_id']}}">{{ data['client'] }}</RouterLink>
                <template v-else>—</template>
            </GuiValue>
        </GuiContainer>

        <GuiContainer w-50 mt-30 pr-20 inline>
            <GuiValue :title="'Фамилия'">{{ data['lastname'] }}</GuiValue>
            <GuiValue :title="'Имя'">{{ data['firstname'] }}</GuiValue>
            <GuiValue :title="'Отчество'">{{ data['patronymic'] }}</GuiValue>
            <GuiValue :title="'Телефон'">{{ data['phone'] }}</GuiValue>
            <GuiValue :title="'Email'">{{ data['email'] }}</GuiValue>
            <template v-if="data['is_group']">
                <GuiValue :title="'Название огранизации'">{{ data['organization_name'] }}</GuiValue>
            </template>
            <template v-else>
                <GuiValue :title="'Фамилия занимающегося'">{{ data['ward_lastname'] }}</GuiValue>
                <GuiValue :title="'Имя занимающегося'">{{ data['ward_firstname'] }}</GuiValue>
                <GuiValue :title="'Отчество занимающегося'">{{ data['ward_patronymic'] }}</GuiValue>
                <GuiValue :title="'Дата рождения занимающегося'">{{ data['ward_birth_date_info'] }} {{ data['ward_age'] ? `(${data['ward_age']})` : '' }}</GuiValue>
            </template>
        </GuiContainer>
        <GuiContainer v-if="data['is_group']" w-50 mt-30 pr-20 inline>
            <GuiValue :title="'Количество детей общее'">{{ data['ward_count'] }}</GuiValue>
            <GuiValue>Девочки / Мальчики</GuiValue>
            <GuiValue :title="'до 10 лет'">{{ data['girls_1_count'] }} / {{ data['boys_1_count'] }}</GuiValue>
            <GuiValue :title="'10-17 лет'">{{ data['girls_2_count'] }} / {{ data['boys_2_count'] }}</GuiValue>
            <GuiValue :title="'старше 18 лет'">{{ data['girls_3_count'] }} / {{ data['boys_3_count'] }}</GuiValue>
            <GuiValue :title="'Количество тренеров'">{{ data['attendant_count'] }}</GuiValue>
            <GuiValue :title="'Количество сопровождающих'">{{ data['attendant_count'] }}</GuiValue>
            <GuiValueArea :title="'Комментарий клиента (пожелания из поля комментарий в заявке)'" :text-content="data['client_comments']"/>
        </GuiContainer>
        <GuiContainer v-else w-50 mt-30 pr-20 inline>
            <GuiValue :title="'Индивидуальные особенности воспитанника (физические, психологические)'"><span
                :class="{'text-red': data['ward_spe']}">{{ data['ward_spe'] ? 'да' : 'нет' }}</span></GuiValue>
            <GuiValue :title="'Состоит на учете у медицинских специалистов'"><span :class="{'text-red': data['ward_uch']}">{{ data['ward_uch'] ? 'да' : 'нет' }}</span></GuiValue>
            <GuiValue :title="'Наличие инвалидности'"><span :class="{'text-red': data['ward_inv']}">{{ data['ward_inv'] ? 'да' : 'нет' }}</span></GuiValue>
            <GuiValueArea :title="'Комментарий клиента'" :text-content="data['client_comments']"/>
        </GuiContainer>
        <GuiContainer mt-30>
            <GuiValueArea :title="'Комментарий'" :text-content="data['comments']"/>
            <span class="link text-sm" @click="editComment">Редактировать комментарий</span>
        </GuiContainer>

        <FormPopUp :form="comment_form" :title="'Комментарий'" :save-button-caption="'Сохранить'" ref="comment_form">
            <GuiContainer w-500px>
                <FormText :form="comment_form" :name="'comments'" :hide-title="true"/>
            </GuiContainer>
        </FormPopUp>
    </div>
</template>

<script>
import GuiContainer from "@/Components/GUI/GuiContainer";
import GuiValue from "@/Components/GUI/GuiValue";
import GuiValueArea from "@/Components/GUI/GuiValueArea";
import FormPopUp from "../../../../Components/FormPopUp";
import FormText from "../../../../Components/Form/FormText";
import form from "../../../../Core/Form";

export default {
    props: {
        data: {type: Object, required: true},
        leadId: {type: Number, required: true},
    },

    components: {
        FormText,
        FormPopUp,
        GuiValueArea,
        GuiValue,
        GuiContainer
    },

    data: () => ({
        comment_form: form(null, '/api/leads/comment'),
    }),

    created() {
        this.comment_form.toaster = this.$toast;
    },

    methods: {
        editComment() {
            this.comment_form.reset();
            this.comment_form.set('comments', this.data['comments'], null, 'Комментарий', true);
            this.comment_form.load();
            this.$refs.comment_form.show({id: this.leadId})
                .then(() => {
                    this.$emit('update');
                });
        }
    }
}
</script>
