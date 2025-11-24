<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

interface Props {
    className?: HTMLAttributes['class'];
}

defineProps<Props>();
</script>

<template>
    <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 24 24

        "
        :class="className"
        v-bind="$attrs"
    >
        <rect width="100%" height="100%" fill="transparent" />
        <text
            x="50%"
            y="50%"
            text-anchor="middle"
            dominant-baseline="middle"
            font-size="18"
            font-weight="700"
            fill="currentColor"
        >
            BK
        </text>
    </svg>
</template>
