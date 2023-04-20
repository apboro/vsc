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

            <template v-if="data['is_regular']">
                <GuiValueArea :title="'Расписание занятий'" :text-content="data['schedule']"/>
                <GuiContainer mt-20>
                    <GuiValue :title="'Максимальное количество мест в группе'">{{ data['group_limit'] }}</GuiValue>
                    <GuiValue :title="'Количество занятий в неделю'">{{ data['trainings_per_week'] }}</GuiValue>
                    <GuiValue :title="'Количество занятий в месяц'">{{ data['trainings_per_month'] }}</GuiValue>
                    <GuiValue :title="'Длительность занятия, минут'">{{ data['training_duration'] }}</GuiValue>
                </GuiContainer>
            </template>
            <template v-else>
                <GuiValue :title="'Стоимость услуги, руб'">{{ data['price'] }}</GuiValue>
                <GuiValue :title="'Дата внесения средств'">{{ data['date_deposit_funds'] }}</GuiValue>
                <GuiValue :title="'Авансовый платеж, руб'">{{ data['advance_payment'] }}</GuiValue>
                <GuiValue :title="'Дата внесения аванса'">{{ data['date_advance_payment'] }}</GuiValue>
                <GuiValue :title="'Сумма возврата при расторжении договора по инициативе клиента, в день, руб'">{{ data['refund_amount'] }}</GuiValue>
                <GuiValue :title="'Стоимость за 1 день, руб'">{{ data['daily_price'] }}</GuiValue>
                <GuiValue :title="'Стоимость услуги за вычетом аванса, руб'">{{ data['price_deduction_advance'] }}</GuiValue>
            </template>
        </GuiContainer>

        <GuiContainer v-if="data['is_regular']" w-50 mt-30 inline>
            <GuiValue :title="'Стоимость в месяц, руб'">{{ data['monthly_price'] }}</GuiValue>
            <GuiValue :title="'Себестоимость за 1 занятие, руб'">{{ data['training_price'] }}</GuiValue>
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
