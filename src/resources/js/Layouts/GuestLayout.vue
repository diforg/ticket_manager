<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="relative min-h-screen overflow-hidden bg-[#080808] text-zinc-100">
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(16,185,129,0.18),_transparent_45%),linear-gradient(180deg,#0a0a0a_0%,#111111_100%)]"></div>
        <div class="pointer-events-none absolute inset-0 opacity-40 [background-image:linear-gradient(rgba(255,255,255,0.03)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.03)_1px,transparent_1px)] [background-size:56px_56px]"></div>

        <div class="relative mx-auto flex min-h-screen w-full max-w-6xl flex-col justify-center px-6 py-10 sm:px-8">
            <div class="flex justify-center">
                <Link
                    href="/"
                    class="inline-flex items-center gap-2 rounded-full border border-emerald-400/25 bg-emerald-400/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-emerald-300 transition duration-200 hover:border-emerald-300/40 hover:bg-emerald-400/15"
                >
                    Ticket Manager
                </Link>
            </div>

            <div class="relative mx-auto mt-6 w-full max-w-md">
                <div class="absolute -inset-4 rounded-3xl bg-emerald-400/10 blur-3xl"></div>

                <div
                    class="relative w-full overflow-hidden rounded-3xl border border-white/10 bg-black/30 px-6 py-7 shadow-[0_24px_70px_rgba(0,0,0,0.55)] backdrop-blur-xl sm:px-8"
                >
                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>
