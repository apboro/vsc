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

        <GuiContainer w-50 mt-30 pr-10 inline>
            <GuiValue :title="'Фамилия'">{{ data['lastname'] }}</GuiValue>
            <GuiValue :title="'Имя'">{{ data['firstname'] }}</GuiValue>
            <GuiValue :title="'Отчество'">{{ data['patronymic'] }}</GuiValue>
            <GuiValue :title="'Телефон'">{{ data['phone'] }}</GuiValue>
            <GuiValue :title="'Email'">{{ data['email'] }}</GuiValue>
            <GuiValue :title="'Фамилия занимающегося'">{{ data['ward_lastname'] }}</GuiValue>
            <GuiValue :title="'Имя занимающегося'">{{ data['ward_firstname'] }}</GuiValue>
            <GuiValue :title="'Отчество занимающегося'">{{ data['ward_patronymic'] }}</GuiValue>
            <GuiValue :title="'Дата рождения занимающегося'">{{ data['ward_birth_date_info'] }} {{ data['ward_age'] ? `(${data['ward_age']})` : '' }}</GuiValue>
        </GuiContainer>
        <GuiContainer w-50 mt-30 pr-10 inline>
            <GuiValue :title="'Индивидуальные особенности воспитанника (физические, психологические)'"><span
                :class="{'text-red': data['ward_spe']}">{{ data['ward_spe'] ? 'да' : 'нет' }}</span></GuiValue>
            <GuiValue :title="'Состоит на учете у медицинских специалистов'"><span :class="{'text-red': data['ward_uch']}">{{ data['ward_uch'] ? 'да' : 'нет' }}</span></GuiValue>
            <GuiValue :title="'Наличие инвалидности'"><span :class="{'text-red': data['ward_inv']}">{{ data['ward_inv'] ? 'да' : 'нет' }}</span></GuiValue>
        </GuiContainer>
    </div>
</template>

<script>
import GuiContainer from "@/Components/GUI/GuiContainer";
import GuiValue from "@/Components/GUI/GuiValue";
import GuiValueArea from "@/Components/GUI/GuiValueArea";

export default {
    props: {
        data: {type: Object, required: true},
    },

    components: {
        GuiValueArea,
        GuiValue,
        GuiContainer
    },
}
</script>
