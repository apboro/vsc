<template>
    <div class="container-form"
         :class="(leftBall ? 'container-form__left_ball' : '') || (rightBall ? 'container-form__right_ball' : '')">
        <div v-if="label !== null" class="container-form__label">
            {{ label }}
            <div class="divider"></div>
        </div>
        <slot></slot>
    </div>
</template>

<script>
export default {
    props: {
        rightBall: {type: Boolean, default: false},
        leftBall: {type: Boolean, default: false},
        label: {type: String, default: null}
    }
}
</script>

<style lang="scss" scoped>
@use "sass:math";

.container-form {
    background-color: #fff;
    border-radius: 25px;
    padding: 2% 3%;

    &__image {
        display: flex;
        align-items: center;
        position: relative;
    }

    &__label {
        font-size: 23px;
        font-weight: 600;
        font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Helvetica Neue, Arial, Noto Sans, sans-serif, Apple Color Emoji, Segoe UI Emoji, Segoe UI Symbol, Noto Color Emoji;
    }

    &__left {}

    &__right {}
}

.divider {
    height: 1px;
    background-color: #d1d0d0;
    width: 75%;
    margin: 10px 0 15px;
}


@media screen and (max-width: 769px) {
    .container-form {
        padding: 5% 3%;

        &__label {
            font-size: 18px;
        }
    }
}

</style>
