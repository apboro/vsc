<template>
    <div class="container" :class="classFromAttributes">
        <slot/>
    </div>
</template>

<script>
import AttributeKeysToClass from "@/Components/Helpers/AttributeKeysToClass";

export default {
    inheritAttrs: false,
    mixins: [AttributeKeysToClass],
}
</script>

<style>
.container {
    vertical-align: top;
    box-sizing: border-box;
    padding: 30px 10px;
}

@media screen and (max-width: 769px) {
    .container {
        padding: 30px 0;
    }
}
</style>
