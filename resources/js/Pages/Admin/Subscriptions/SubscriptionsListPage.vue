<template>
    <LayoutPage :title="$route.meta['title']">
        <SubscriptionsList/>
    </LayoutPage>
</template>

<script>
import LayoutPage from "@/Components/Layout/LayoutPage";
import SubscriptionsList from "@/Pages/Admin/Subscriptions/Parts/SubscriptionsList";

export default {
    components: {
        SubscriptionsList,
        LayoutPage,
    },
}
</script>
