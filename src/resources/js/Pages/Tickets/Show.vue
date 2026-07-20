<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    ticket: {
        type: Object,
        required: true,
    },
});

const statusLabel = {
    open: 'Aberto',
    in_progress: 'Em andamento',
    resolved: 'Resolvido',
};

const formatStatus = (status) => statusLabel[status] ?? status;

const formatDate = (value) => {
    if (!value) {
        return '-';
    }

    return new Intl.DateTimeFormat('pt-BR', {
        dateStyle: 'short',
        timeStyle: 'short',
    }).format(new Date(value));
};
</script>

<template>
    <Head :title="ticket.title" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-zinc-100">
                {{ ticket.title }}
            </h2>
        </template>

        <div class="py-12 bg-[#080808]">
            <div class="mx-auto max-w-4xl space-y-6 sm:px-6 lg:px-8">
                <div class="rounded-lg border border-zinc-700 bg-zinc-900 p-6 shadow-sm sm:p-8">
                    <div class="space-y-6">
                        <div>
                            <p class="text-sm font-medium uppercase tracking-wider text-emerald-300">
                                Status
                            </p>
                            <p class="mt-1 text-base text-zinc-100">
                                {{ formatStatus(ticket.status) }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm font-medium uppercase tracking-wider text-emerald-300">
                                Descrição
                            </p>
                            <p class="mt-1 whitespace-pre-line text-sm leading-6 text-zinc-300">
                                {{ ticket.description }}
                            </p>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <p class="text-sm font-medium uppercase tracking-wider text-emerald-300">
                                    Criado em
                                </p>
                                <p class="mt-1 text-sm text-zinc-300">
                                    {{ formatDate(ticket.created_at) }}
                                </p>
                            </div>

                            <div>
                                <p class="text-sm font-medium uppercase tracking-wider text-emerald-300">
                                    Atualizado em
                                </p>
                                <p class="mt-1 text-sm text-zinc-300">
                                    {{ formatDate(ticket.updated_at) }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-4">
                            <Link
                                :href="route('dashboard')"
                                class="inline-flex items-center rounded-md border border-zinc-700 bg-zinc-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-zinc-100 transition duration-150 ease-in-out hover:border-emerald-400 hover:text-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:ring-opacity-50"
                            >
                                Voltar ao Dashboard
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
