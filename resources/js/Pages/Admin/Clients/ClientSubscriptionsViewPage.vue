<template>
    <SubscriptionView :subscription-id="subscriptionId" :client-id="clientId"/>
</template>

<script>
import SubscriptionView from "@/Pages/Admin/Subscriptions/Parts/SubscriptionView";

export default {
    components: {
        SubscriptionView,
    },

    computed: {
        clientId() {
            return Number(this.$route.params.client);
        },
        subscriptionId() {
            return Number(this.$route.params.id);
        },
    },
}
</script>
