<template>
    <div class="application__menu-item" v-if="accepted"
         :class="['application__menu-item-'+level, hovered ? 'application__menu-item-hovered' : '']"
         @mouseenter="show"
         @mouseleave="hide"
    >

        <router-link v-if="route" class="application__menu-item-link" :to="{name:route}">
            <span @click="$emit('hide')">{{ title }}<icon-dropdown class="application__menu-item-link-drop" v-if="children"/></span>
        </router-link>

        <span v-else class="application__menu-item-no-link">
            <span @click="$emit('hide')">{{ title }}<icon-dropdown class="application__menu-item-link-drop" v-if="children"/></span>
        </span>

        <div v-if="children" class="application__menu-submenu" :class="'application__menu-submenu-'+level">
            <layout-menu-item v-for="(item, key) in children"
                              :key="key"
                              :item="item"
                              :level="level+1"
                              @hide="hide"
            />
        </div>

    </div>
</template>

<script>
import IconDropdown from "../Icons/IconDropdown";
import Permissions from "@/Mixins/Permissions";

export default {
    name: "LayoutMenuItem",

    mixins: [Permissions],

    components: {IconDropdown},

    props: {
        item: Object,
        level: {type: Number, default: 0},
    },

    data: () => ({
        hovered: false,
    }),

    computed: {
        title() {
            return typeof this.item.title !== 'undefined' ? this.item.title : 'not set';
        },
        route() {
            return typeof this.item.route !== 'undefined' ? this.item.route : null;
        },
        children() {
            return typeof this.item.items !== 'undefined' && this.item.items.length > 0 ? this.item.items : null;
        },
        accepted() {
            const acceptedPermission = typeof this.item['permission'] === "undefined"
                || this.item['permission'] === null
                || this.item['permission'] === ''
                || this.can(this.item['permission']);
            const acceptedRole = typeof this.item['role'] === "undefined"
                || this.item['role'] === null
                || this.item['role'] === ''
                || this.hasRole(this.item['role']);
            return acceptedPermission && acceptedRole;
        }
    },

    methods: {
        show() {
            if (this.children) {
                this.hovered = true;
            }
        },
        hide() {
            this.hovered = false;
        },
    },
}
</script>
