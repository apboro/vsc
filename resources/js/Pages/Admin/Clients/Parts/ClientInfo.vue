<template>
    <div>
        <GuiContainer w-50 mt-30 pr-10 inline>
            <GuiValue :title="'Фамилия'">{{ data['lastname'] }}</GuiValue>
            <GuiValue :title="'Имя'">{{ data['firstname'] }}</GuiValue>
            <GuiValue :title="'Отчество'">{{ data['patronymic'] }}</GuiValue>
            <GuiValue :title="'Телефон'">{{ data['phone'] }}</GuiValue>
            <GuiValue :title="'Email'">{{ data['email'] }}</GuiValue>
            <GuiValue :title="'Статус'">{{ data['status'] }}</GuiValue>
        </GuiContainer>
    </div>
</template>

<script>
import GuiContainer from "@/Components/GUI/GuiContainer";
import GuiValue from "@/Components/GUI/GuiValue";

export default {
    props: {
        data: {type: Object, required: true},
    },

    components: {
        GuiValue,
        GuiContainer
    },
}
</script>
