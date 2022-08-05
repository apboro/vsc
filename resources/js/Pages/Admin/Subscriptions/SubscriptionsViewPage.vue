<template>
    <SubscriptionView :subscription-id="subscriptionId"/>
</template>

<script>
import SubscriptionView from "@/Pages/Admin/Subscriptions/Parts/SubscriptionView";

export default {
    components: {
        SubscriptionView,
    },

    computed: {
        subscriptionId() {
            return Number(this.$route.params.id);
        },
    },
}
</script>
