<template>
    <GuiContainer>

        <LayoutRoutedTabs :tabs="tabs" @change="tab = $event"/>

        <ClientAccount v-if="tab === 'account'" :client-id="clientId"/>
        <GuiMessage v-if="tab === 'invoices'">В разработке</GuiMessage>

    </GuiContainer>
</template>

<script>
import LayoutPage from "@/Components/Layout/LayoutPage";
import LayoutRoutedTabs from "@/Components/Layout/LayoutRoutedTabs";
import ClientAccount from "@/Pages/Admin/Clients/Parts/ClientAccount";
import GuiContainer from "@/Components/GUI/GuiContainer";
import GuiMessage from "../../../../Components/GUI/GuiMessage.vue";

export default {
    components: {
        GuiMessage,
        GuiContainer,
        LayoutPage,
        LayoutRoutedTabs,

        ClientAccount,
    },

    props: {
        clientId: { type: Number, required: true },
    },

    data: () => ({
        tab: null,
    }),

    computed: {
        tabs() {
            return {
                account: 'Лицевой счет',
                invoices: 'Счета и оплаты',
            }
        },
    }
}
</script>

<style scoped>

</style>
