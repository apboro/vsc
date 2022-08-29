<template>
    <div>
        <GuiContainer mt-30>
            <GuiValue :title="'Название'">{{ data['title'] }}</GuiValue>
            <GuiValue :title="'Объект'">{{ data['training_base'] }}</GuiValue>
            <GuiValue :title="'Вид спорта'">{{ data['sport_kind'] }}</GuiValue>
            <GuiValue :title="'Статус'">
                <GuiActivityIndicator :active="data['active']"/>
                {{ data['status'] }}
            </GuiValue>
        </GuiContainer>
        <GuiContainer w-50 mt-30 pr-20 inline>
            <GuiValue :title="'Дата начала услуги'">{{ data['start_at'] }}</GuiValue>
            <GuiValue :title="'Дата окончания услуги'">{{ data['end_at'] }}</GuiValue>
            <GuiValueArea :title="'Расписание занятий'" :text-content="data['schedule']"/>
            <GuiContainer mt-20>
                <GuiValue :title="'Максимальное количество мест в группе'">{{ data['group_limit'] }}</GuiValue>
                <GuiValue :title="'Количество занятий в неделю'">{{ data['trainings_per_week'] }}</GuiValue>
                <GuiValue :title="'Количество занятий в месяц'">{{ data['trainings_per_month'] }}</GuiValue>
                <GuiValue :title="'Длительность занятия, минут'">{{ data['training_duration'] }}</GuiValue>
            </GuiContainer>
        </GuiContainer>
        <GuiContainer w-50 mt-30 inline>
            <GuiValue :title="'Стоимость в месяц, руб'">{{ data['monthly_price'] }}</GuiValue>
            <GuiValue :title="'Стоимость за одно занятие, руб'">{{ data['training_price'] }}</GuiValue>
            <GuiValue :title="'Стоимость за 1 занятие при перерасчете, руб'">{{ data['training_return_price'] }}</GuiValue>
            <GuiValue :title="'Реквизиты для договора'">{{ data['requisites'] ? data['requisites'] : '—' }}</GuiValue>
        </GuiContainer>
    </div>
</template>

<script>
import GuiContainer from "@/Components/GUI/GuiContainer";
import GuiValue from "@/Components/GUI/GuiValue";
import GuiActivityIndicator from "@/Components/GUI/GuiActivityIndicator";
import GuiValueArea from "@/Components/GUI/GuiValueArea";

export default {
    props: {
        data: {type: Object, required: true},
    },

    components: {
        GuiValueArea,
        GuiActivityIndicator,
        GuiValue,
        GuiContainer
    },
}
</script>
