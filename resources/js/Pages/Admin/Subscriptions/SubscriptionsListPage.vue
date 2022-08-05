<template>
    <LayoutPage :title="$route.meta['title']">
        <!--
        <template #actions v-if="can('services.edit')">
            <GuiActionsMenu>
                <router-link class="link" :to="{ name: 'services-edit', params: { id: 0 }}">Добавить услугу</router-link>
            </GuiActionsMenu>
        </template>
        -->

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
