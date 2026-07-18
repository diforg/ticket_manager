<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    href: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Link
        :href="href"
        class="block w-full px-4 py-2 text-start text-sm leading-5 text-zinc-300 transition duration-150 ease-in-out hover:text-emerald-300 hover:bg-zinc-700 focus:bg-zinc-700 focus:outline-none"
    >
        <slot />
    </Link>
</template>
