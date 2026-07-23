<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});

const classes = computed(() =>
    props.active
    ? 'inline-flex items-center rounded-full px-4 py-2 text-sm font-semibold leading-5 text-white bg-[#6b21a8] transition duration-150 ease-in-out'
    : 'inline-flex items-center rounded-full px-4 py-2 text-sm font-semibold leading-5 text-[#6b21a8] hover:bg-[#f5f3ff] focus:outline-none transition duration-150 ease-in-out',
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
