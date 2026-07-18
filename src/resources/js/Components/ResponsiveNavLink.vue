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
        ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-emerald-400 text-start text-base font-medium text-emerald-300 bg-zinc-800 focus:outline-none focus:text-emerald-300 focus:bg-zinc-700 focus:border-emerald-300 transition duration-150 ease-in-out'
        : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-zinc-300 hover:text-emerald-300 hover:bg-zinc-800 hover:border-emerald-300 focus:outline-none focus:text-emerald-300 focus:bg-zinc-700 focus:border-emerald-300 transition duration-150 ease-in-out',
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
