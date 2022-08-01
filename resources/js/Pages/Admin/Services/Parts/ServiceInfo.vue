<template>
    <div>
        <GuiContainer w-50 mt-30 pr-10 inline>
            <GuiValue :title="'Название'">{{ data['title'] }}</GuiValue>
            <GuiValue :title="'Объект'">{{ data['training_base'] }}</GuiValue>
            <GuiValue :title="'Вид спорта'">{{ data['sport_kind'] }}</GuiValue>
            <GuiValue :title="'Статус'">
                <GuiActivityIndicator :active="data['active']"/>
                {{ data['status'] }}
            </GuiValue>
            <GuiValueArea :title="'График занятий'" :text-content="data['schedule']"/>
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
