<template>
    <div class="hint" :class="classFromAttributes">
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

<style lang="scss">
@import "../../../../../css/fonts";

$base_text_gray_color: #3f3f3f !default;
$base_text_black_color: #000000 !default;

.hint {
    font-family: $norms_regular_font;
    font-size: 14px;
    font-weight: 600;
    letter-spacing: 0.03em;
    color: $base_text_black_color;
}
</style>
