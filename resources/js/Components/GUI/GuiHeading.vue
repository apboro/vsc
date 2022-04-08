<template>
    <div class="heading" :class="classProxy" @click="expand">
        <slot/>
        <div class="heading__expand" :class="{'heading__expand-expanded': expanded}" v-if="expandable">
            <IconDropdown :class="'heading__expand-button'"/>
        </div>
    </div>
</template>

<script>
import AttributeKeysToClass from "@/Components/Helpers/AttributeKeysToClass";
import IconDropdown from "@/Components/Icons/IconDropdown";

export default {
    props: {
        expandable: {type: Boolean, default: false},
    },

    emits: ['expand'],

    components: {IconDropdown},

    inheritAttrs: false,

    mixins: [AttributeKeysToClass],

    computed: {
        classProxy() {
            let computed = this.classFromAttributes;
            if (this.expandable) {
                computed.push('heading__expandable');
            }
            return computed;
        },
    },

    data: () => ({
        expanded: false,
    }),

    methods: {
        expand() {
            if (!this.expandable) {
                return;
            }
            this.expanded = !this.expanded;
            this.$emit('expand', this.expanded);
        }
    }
}
</script>

<style lang="scss">
@use "sass:math";
@import "../variables";

$project_font: -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Helvetica Neue, Arial, Noto Sans, sans-serif, Apple Color Emoji, Segoe UI Emoji, Segoe UI Symbol, Noto Color Emoji !default;
$base_black_color: #1e1e1e !default;

.heading {
    font-family: $project_font;
    width: 100%;
    font-size: 18px;
    color: $base_black_color;

    &__expandable {
        cursor: pointer;
    }

    &__expand {
        display: inline-flex !important;
        justify-content: center;
        align-items: center;
        width: $base_size_unit;
        height: $base_size_unit;
        cursor: pointer;

        &-button {
            display: block;
            width: math.div($base_size_unit, 4);
            height: math.div($base_size_unit, 4);
            transition: transform $animation $animation_time;
            color: $base_black_color;
        }

        &:hover &-button {
            transform: scale(1.2);
        }

        &-expanded &-button {
            transform: rotate(-180deg);
        }

        &-expanded:hover &-button {
            transform: rotate(-180deg) scale(1.2);
        }
    }
}
</style>
