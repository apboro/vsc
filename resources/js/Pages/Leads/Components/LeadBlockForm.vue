<template>
    <div class="container-form">
        <div v-if="leftBall || rightBall" :class="leftBall ? 'container-form__left_ball' : 'container-form__right_ball'">
            <img src="./../assets/aqua.png" width="80" alt="">
        </div>
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
@import "../../../../css/fonts";

.container-form {
    background-color: #fff;
    border-radius: 25px;
    padding: 3%;
    display: block;
    position: relative;

    &__no-image {
        display: flex;
        align-items: center;
        padding: 2%;
        position: relative;
    }

    &__image {
        display: flex;
        align-items: center;
        position: relative;
    }

    &__label {
        font-size: 26px;
        font-family: $heading_now_font;
        letter-spacing: 0.02em;
        line-height: 0.5;

    }

    &__left_ball {
        display: block;
        position: absolute;
        top: -45px;
        left: -33px;
    }

    &__right_ball {
        display: block;
        position: absolute;
        top: -45px;
        right: -33px;
    }
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
