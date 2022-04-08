<template>
    <LayoutPage :title="'Доступ запрещён'">
        <GuiMessage text-red>Доступ к запрошенному разделу запрещён, у Вас недостаточно прав.</GuiMessage>
    </LayoutPage>
</template>

<script>
import LayoutPage from "@/Components/Layout/LayoutPage";
import GuiMessage from "@/Components/GUI/GuiMessage";

export default {
    components: {
        GuiMessage,
        LayoutPage,
    }
}
</script>
