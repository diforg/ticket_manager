<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="tm-page relative overflow-hidden">
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(139,92,246,0.18),_transparent_40%),radial-gradient(circle_at_bottom_left,_rgba(233,213,255,0.7),_transparent_35%)]"></div>

        <div class="relative mx-auto flex min-h-screen w-full max-w-6xl flex-col justify-center px-6 py-10 sm:px-8">
            <div class="flex justify-center">
                <Link
                    href="/"
                    class="inline-flex items-center gap-2 rounded-full border border-[#c4b5fd] bg-[#f5f3ff] px-5 py-2 text-xs font-bold uppercase tracking-[0.24em] text-[#6b21a8] transition duration-200 hover:bg-[#ede9fe]"
                >
                    Ticket Manager
                </Link>
            </div>

            <div class="relative mx-auto mt-6 w-full max-w-md">
                <div class="absolute -inset-4 rounded-3xl bg-[#c4b5fd]/35 blur-3xl"></div>

                <div
                    class="relative w-full overflow-hidden rounded-3xl border border-[#ddd6fe] bg-white px-6 py-7 shadow-[0_24px_70px_rgba(91,33,182,0.12)] sm:px-8"
                >
                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>
