<template>
    <GuiContainer>

        <LayoutRoutedTabs :tabs="tabs" @change="tab = $event"/>

        <ClientAccount  v-if="tab === 'account'"  :client-id="clientId"/>
        <ClientInvoices v-if="tab === 'invoices'" :client-id="clientId"/>

    </GuiContainer>
</template>

<script>
import LayoutPage from "@/Components/Layout/LayoutPage";
import LayoutRoutedTabs from "@/Components/Layout/LayoutRoutedTabs";
import ClientAccount from "@/Pages/Admin/Clients/Parts/ClientAccount";
import GuiContainer from "@/Components/GUI/GuiContainer";
import ClientInvoices from "@/Pages/Admin/Clients/Parts/ClientInvoices";

export default {
    components: {
        GuiContainer,
        LayoutPage,
        LayoutRoutedTabs,

        ClientAccount,
        ClientInvoices,
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
