<template>
    <button class="button__lead" :class="classProxy" @click.stop.prevent="click">
        <slot></slot>
    </button>
</template>

<script>
export default {
    props: {
        identifier: {type: String, default: null},
        disabled: {type: Boolean, default: false},
    },

    emits: ['clicked'],

    computed: {
        classProxy() {
            return 'button__lead' + (this.disabled ? ' button__lead__disabled' : '');
        }
    },

    methods: {
        click() {
            if (!this.disabled) {
                this.$emit('clicked', this.identifier);
            }
        }
    }
}
</script>

<style lang="scss">
@use "sass:math";
@import "../../../../../css/fonts";

$project_font: -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Helvetica Neue, Arial, Noto Sans, sans-serif, Apple Color Emoji, Segoe UI Emoji, Segoe UI Symbol, Noto Color Emoji !default;
$animation_time: 150ms !default;
$animation: cubic-bezier(0.24, 0.19, 0.28, 1.29) !default;
$shadow_1: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24) !default;
$shadow_hover: 0 2px 4px rgba(0, 0, 0, 0.25), 0 2px 4px rgba(0, 0, 0, 0.22) !default;
$base_size_unit: 40px !default;
$base_text_gray_color: #3f3f3f !default;
$base_gray_color: #8f8f8f !default;
$base_white_color: #ffffff !default;
$base_black_color: #000000 !default;
$base_button_color: #fdc93c !default;
$base_primary_color: #0D74D7 !default;
$base_primary_hover_color: lighten(#0D74D7, 10%) !default;
$base_red_color: #EA1B00 !default;
$base_red_hover_color: lighten(#EA1B00, 5%) !default;
$base_green_color: #00AF2E !default;
$base_green_hover_color: lighten(#00AF2E, 5%) !default;
$base_orange_color: #EA8B00 !default;
$base_orange_hover_color: lighten(#EA8B00, 5%) !default;
$base_disabled_color: #b4902e !default;

.button__lead {
    display: inline-block;
    text-decoration: none;
    height: $base_size_unit;
    line-height: $base_size_unit;
    text-align: center;
    cursor: pointer;
    border-radius: 50px !important;
    text-transform: uppercase;
    font-weight: 700;
    box-sizing: border-box;
    padding: 0 math.div($base_size_unit, 2);
    letter-spacing: 0.03rem;
    color: $base_black_color;
    border: transparent !important;
    background-color: transparent !important;
    transition: background-color $animation $animation_time, border-color $animation $animation_time, box-shadow $animation $animation_time;
    font-family: $travels_black_font;
    font-size: 14px;
    box-shadow: transparent;
    white-space: nowrap;

    &:not(:last-child) {
        margin-right: 20px;
    }

    //&:hover {
    //    box-shadow: $shadow_hover;
    //    background-color: $base_white_color;
    //    border-color: #c1c1c1;
    //}

    &:not(&__disabled):active {
        box-shadow: none;
    }

    &__disabled {
        cursor: not-allowed;
        background-color: transparent !important;
        border-color: transparent !important;
        box-shadow: none !important;
        color: $base_black_color;

        &:hover {
        }
    }
}

.center {
    margin: 0 auto !important;
    display: flex;
    flex-direction: row;
    box-sizing: border-box;
}
</style>
