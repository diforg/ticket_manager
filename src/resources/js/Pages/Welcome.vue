<script setup>
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Ticket Manager" />

    <div class="tm-page relative overflow-hidden">
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(139,92,246,0.24),_transparent_38%),radial-gradient(circle_at_bottom_left,_rgba(233,213,255,0.7),_transparent_42%)]"></div>

        <div class="relative mx-auto flex min-h-screen w-full max-w-6xl items-center px-6 py-12 sm:px-8 lg:px-10">
            <div class="grid w-full items-center gap-10 lg:grid-cols-[1.1fr_0.9fr] lg:gap-16">
                <section class="max-w-2xl">
                    <div class="inline-flex items-center gap-2 rounded-full border border-[#c4b5fd] bg-[#f5f3ff] px-4 py-2 text-xs font-bold tracking-[0.24em] text-[#6b21a8]">
                        TICKET MANAGEMENT
                    </div>

                    <h1 class="mt-6 text-4xl font-extrabold tracking-tight text-[#111827] sm:text-5xl lg:text-6xl">
                        Ticket Manager
                    </h1>

                    <p class="mt-5 max-w-xl text-base leading-7 text-[#6b7280] sm:text-lg">
                        Gerencie chamados de suporte com agilidade e transparência, mantendo clientes e atendentes sempre alinhados em um único fluxo.
                    </p>

                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                        <Link
                            :href="route('login')"
                            class="tm-btn-primary px-7 py-3.5"
                        >
                            Entrar
                        </Link>

                        <Link
                            :href="route('register')"
                            class="tm-btn-secondary px-7 py-3.5"
                        >
                            Cadastrar
                        </Link>
                    </div>
                </section>

                <aside class="relative">
                    <div class="absolute -inset-6 rounded-[2rem] bg-[#c4b5fd]/40 blur-3xl"></div>
                    <div class="relative overflow-hidden rounded-[2rem] border border-[#ddd6fe] bg-white p-6 shadow-[0_30px_80px_rgba(91,33,182,0.16)] sm:p-8">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm uppercase tracking-[0.28em] text-[#7c3aed]">
                                    Suporte centralizado
                                </p>
                                <p class="mt-2 text-xl font-bold text-[#111827]">
                                    Fluxo mais claro para cada perfil
                                </p>
                            </div>
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl border border-[#c4b5fd] bg-[#f5f3ff] text-[#6b21a8]">
                                TM
                            </div>
                        </div>

                        <div class="mt-8 space-y-4">
                            <div class="rounded-2xl border border-[#e9d5ff] bg-[#faf5ff] p-4 transition duration-200 hover:border-[#c4b5fd]">
                                <p class="text-sm font-semibold text-[#6b21a8]">Visibilidade</p>
                                <p class="mt-1 text-base text-[#374151]">Acompanhe solicitações sem ruído e com prioridades mais claras.</p>
                            </div>

                            <div class="rounded-2xl border border-[#e9d5ff] bg-[#faf5ff] p-4 transition duration-200 hover:border-[#c4b5fd]">
                                <p class="text-sm font-semibold text-[#6b21a8]">Colaboração</p>
                                <p class="mt-1 text-base text-[#374151]">Clientes e atendentes trabalham sobre o mesmo histórico.</p>
                            </div>

                            <div class="rounded-2xl border border-[#e9d5ff] bg-[#faf5ff] p-4 transition duration-200 hover:border-[#c4b5fd]">
                                <p class="text-sm font-semibold text-[#6b21a8]">Agilidade</p>
                                <p class="mt-1 text-base text-[#374151]">Menos troca de contexto, mais foco na resolução do chamado.</p>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</template>
