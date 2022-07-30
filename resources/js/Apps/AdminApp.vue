<template>
    <div class="application">
        <LayoutHeader :user="user">
            <template v-slot:menu>
                <LayoutMenu :menu="menu"/>
            </template>
            <template v-slot:personal>
                <LayoutUserMenu :user="user">
                    <span class="link" v-if="user.positions" @click="change">Сменить компанию</span>
                    <span class="link" @click="logout">Выход</span>
                </LayoutUserMenu>
            </template>
        </LayoutHeader>

        <OrganizationSwitch/>

        <router-view/>

    </div>
    <div id="toaster" class="toaster"></div>
    <div id="dialogs" class="dialogs"></div>
</template>

<script>
import LayoutHeader from "../Components/Layout/LayoutHeader";
import LayoutMenu from "../Components/Layout/LayoutMenu";
import LayoutUserMenu from "../Components/Layout/LayoutUserMenu";
import PopUp from "@/Components/PopUp";
import OrganizationSwitch from "@/Apps/OrganizationSwitch";

export default {
    props: {
        menu: Array,
        user: Object,
    },

    components: {
        OrganizationSwitch,
        LayoutUserMenu,
        LayoutHeader,
        LayoutMenu,
        PopUp,
    },

    created() {

    },

    methods: {
        logout() {
            axios.post('/logout', {})
                .then(() => {
                    window.location.href = '/';
                });
        },

        change() {
            axios.post('/login/change', {})
                .then(() => {
                    window.location.href = '/';
                })
        }
    },
}
</script>
