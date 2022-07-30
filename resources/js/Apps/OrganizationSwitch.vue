<template>
    <div class="organization-switch" v-if="hasRole('super')">
        <div class="organization-switch__caption">
            Организация
        </div>
        <div class="organization-switch__select">
            <InputDropDown v-model="current" :options="organizations" :identifier="'id'" :show="'title'" :small="true" :original="current" @change="changeOrganization"/>
        </div>
    </div>
</template>

<script>
import Permissions from "@/Mixins/Permissions";
import InputDropDown from "@/Components/Inputs/InputDropDown";

export default {
    components: {InputDropDown},
    mixins: [Permissions],

    data: () => ({
        organizations: [],
        current: null,
    }),

    created() {
        if (this.hasRole('super')) {
            axios.post('/api/organizations/list', {})
                .then(response => {
                    this.organizations = response.data.data['organizations'];
                    this.current = response.data.data['current'];
                })
                .catch(error => {
                    this.$toast.error(error.response.data['message']);
                })
        }
    },

    methods: {
        changeOrganization(id) {
            axios.post('/api/organizations/switch', {id: id})
                .then(() => {
                    window.organization = id;
                    document.cookie = 'vsc_organization=' + id + ';path=/';
                    window.location.reload();
                })
                .catch(error => {
                    this.$toast.error(error.response.data['message']);
                })
        },
    },
}
</script>

<style lang="scss" scoped>
$page_max_width: 1200px !default;
$project_font: -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Helvetica Neue, Arial, Noto Sans, sans-serif, Apple Color Emoji, Segoe UI Emoji, Segoe UI Symbol, Noto Color Emoji !default;
$base_text_gray_color: #3f3f3f !default;

.organization-switch {
    max-width: $page_max_width;
    width: calc(100% - 20px);
    margin: 10px auto;
    display: flex;
    justify-content: flex-end;

    &__caption {
        font-family: $project_font;
        color: $base_text_gray_color;
        display: flex;
        align-items: center;
        margin-right: 8px;
        font-size: 14px;
    }

    &__select {
        width: 200px;
    }
}
</style>
