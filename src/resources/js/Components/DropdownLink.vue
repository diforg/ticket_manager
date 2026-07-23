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
        class="block w-full rounded-xl px-4 py-2 text-start text-sm leading-5 text-[#6b21a8] transition duration-150 ease-in-out hover:bg-[#f5f3ff] focus:bg-[#f5f3ff] focus:outline-none"
    >
        <slot />
    </Link>
</template>
