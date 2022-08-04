<template>
    <div>
        <GuiContainer w-50 mt-30 pr-10 inline>
            <GuiValue :title="'Создан'">{{ data['created_date'] }}, {{ data['created_time'] }}</GuiValue>
            <GuiValue :title="'Фамилия'">{{ data['lastname'] }}</GuiValue>
            <GuiValue :title="'Имя'">{{ data['firstname'] }}</GuiValue>
            <GuiValue :title="'Отчество'">{{ data['patronymic'] }}</GuiValue>
            <GuiValue :title="'Телефон'">{{ data['phone'] }}</GuiValue>
            <GuiValue :title="'Email'">{{ data['email'] }}</GuiValue>
            <GuiValue :title="'Статус'">{{ data['status'] }}</GuiValue>
            <GuiValue :title="'Услуга'">{{ data['service'] }}</GuiValue>
            <GuiValue :title="'Объект'">{{ data['training_base'] }}</GuiValue>
            <GuiValue :title="'Адрес'">{{ data['training_base_address'] }}</GuiValue>
            <GuiValue :title="'Вид спорта'">{{ data['sport_kind'] }}</GuiValue>
            <GuiValue :title="'Клиент'">
                <RouterLink v-if="data['client']" class="link" :to="{name: 'clients-view', params: {id: data['client_id']}}">{{ data['client'] }}</RouterLink>
                <template v-else>—</template>
            </GuiValue>
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
