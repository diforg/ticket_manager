<script setup>
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Ticket Manager" />

    <div class="relative min-h-screen overflow-hidden bg-[#080808] text-white">
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(16,185,129,0.18),_transparent_45%),linear-gradient(180deg,#0a0a0a_0%,#111111_100%)]"></div>
        <div class="pointer-events-none absolute inset-0 opacity-40 [background-image:linear-gradient(rgba(255,255,255,0.03)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.03)_1px,transparent_1px)] [background-size:56px_56px]"></div>

        <div class="relative mx-auto flex min-h-screen w-full max-w-6xl items-center px-6 py-12 sm:px-8 lg:px-10">
            <div class="grid w-full items-center gap-10 lg:grid-cols-[1.1fr_0.9fr] lg:gap-16">
                <section class="max-w-2xl">
                    <div class="inline-flex items-center gap-2 rounded-full border border-emerald-400/20 bg-emerald-400/10 px-4 py-2 text-xs font-medium tracking-[0.24em] text-emerald-300 shadow-[0_0_30px_rgba(16,185,129,0.12)] backdrop-blur">
                        TICKET MANAGEMENT
                    </div>

                    <h1 class="mt-6 text-4xl font-semibold tracking-tight text-white sm:text-5xl lg:text-6xl">
                        Ticket Manager
                    </h1>

                    <p class="mt-5 max-w-xl text-base leading-7 text-zinc-300 sm:text-lg">
                        Gerencie chamados de suporte com agilidade e transparência, mantendo clientes e atendentes sempre alinhados em um único fluxo.
                    </p>

                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                        <Link
                            :href="route('login')"
                            class="inline-flex items-center justify-center rounded-2xl bg-emerald-400 px-6 py-3.5 text-sm font-semibold text-black shadow-[0_0_30px_rgba(16,185,129,0.22)] transition duration-200 hover:-translate-y-0.5 hover:bg-emerald-300 hover:shadow-[0_0_40px_rgba(16,185,129,0.35)] focus:outline-none focus:ring-2 focus:ring-emerald-300 focus:ring-offset-2 focus:ring-offset-[#080808]"
                        >
                            Entrar
                        </Link>

                        <Link
                            :href="route('register')"
                            class="inline-flex items-center justify-center rounded-2xl border border-emerald-400/60 bg-transparent px-6 py-3.5 text-sm font-semibold text-emerald-200 transition duration-200 hover:-translate-y-0.5 hover:border-emerald-300 hover:bg-emerald-400/10 hover:text-emerald-100 focus:outline-none focus:ring-2 focus:ring-emerald-300 focus:ring-offset-2 focus:ring-offset-[#080808]"
                        >
                            Cadastrar
                        </Link>
                    </div>
                </section>

                <aside class="relative">
                    <div class="absolute -inset-6 rounded-[2rem] bg-emerald-400/10 blur-3xl"></div>
                    <div class="relative overflow-hidden rounded-[2rem] border border-white/10 bg-white/5 p-6 shadow-[0_30px_80px_rgba(0,0,0,0.55)] backdrop-blur-xl sm:p-8">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm uppercase tracking-[0.28em] text-emerald-300/80">
                                    Suporte centralizado
                                </p>
                                <p class="mt-2 text-xl font-semibold text-white">
                                    Fluxo mais claro para cada perfil
                                </p>
                            </div>
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl border border-emerald-400/30 bg-emerald-400/10 text-emerald-300 shadow-[0_0_24px_rgba(16,185,129,0.18)]">
                                TM
                            </div>
                        </div>

                        <div class="mt-8 space-y-4">
                            <div class="rounded-2xl border border-white/10 bg-black/20 p-4 transition duration-200 hover:border-emerald-400/30 hover:bg-black/30">
                                <p class="text-sm text-zinc-400">Visibilidade</p>
                                <p class="mt-1 text-base text-zinc-100">Acompanhe solicitações sem ruído e com prioridades mais claras.</p>
                            </div>

                            <div class="rounded-2xl border border-white/10 bg-black/20 p-4 transition duration-200 hover:border-emerald-400/30 hover:bg-black/30">
                                <p class="text-sm text-zinc-400">Colaboração</p>
                                <p class="mt-1 text-base text-zinc-100">Clientes e atendentes trabalham sobre o mesmo histórico.</p>
                            </div>

                            <div class="rounded-2xl border border-white/10 bg-black/20 p-4 transition duration-200 hover:border-emerald-400/30 hover:bg-black/30">
                                <p class="text-sm text-zinc-400">Agilidade</p>
                                <p class="mt-1 text-base text-zinc-100">Menos troca de contexto, mais foco na resolução do chamado.</p>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</template>
