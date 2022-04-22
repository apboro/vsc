<template>
    <div>
        <GuiContainer w-50 mt-30>
            <GuiValue :title="'ФИО'">{{ data['full_name'] }}</GuiValue>
            <GuiValue :title="'Дата добавления'">{{ data['created_at'] }}</GuiValue>
            <GuiValue :title="'Должность'">{{ data['position_title'] }}</GuiValue>
            <GuiValue :title="'Статус трудоустройства'">
                <GuiActivityIndicator :active="data.active"/>{{ data['status'] }}
            </GuiValue>
        </GuiContainer>

        <GuiContainer w-50 mt-30 inline>
            <GuiValue :title="'Пол'">{{ data['gender'] }}</GuiValue>
            <GuiValue :title="'Дата рождения'">{{ data['birth_date'] }}</GuiValue>
            <GuiValue :title="'Email'">
                <a class="link" v-if="data.email" target="_blank" :href="'mailto:'+data.email">{{ data['email'] }}</a>
            </GuiValue>
            <GuiValue :title="'Рабочий телефон'">{{ data['work_phone'] }}
                <span v-if="data['work_phone_additional']"> доб. {{ data['work_phone_additional'] }}</span>
            </GuiValue>
            <GuiValue :title="'Мобильный телефон'">{{ data['mobile_phone'] }}</GuiValue>
        </GuiContainer>

        <GuiContainer w-100 mt-30>
            <GuiValueArea :title="'Заметки'" v-text="data['notes']"/>
        </GuiContainer>
    </div>
</template>

<script>
import GuiContainer from "@/Components/GUI/GuiContainer";
import GuiValue from "@/Components/GUI/GuiValue";
import GuiValueArea from "@/Components/GUI/GuiValueArea";
import GuiActivityIndicator from "@/Components/GUI/GuiActivityIndicator";

export default {
    props: {
        data: {type: Object, required: true},
    },

    components: {
        GuiActivityIndicator,
        GuiValueArea,
        GuiValue,
        GuiContainer
    },
}
</script>
