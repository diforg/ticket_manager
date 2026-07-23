<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    tickets: {
        type: Array,
        default: () => [],
    },
    showRequester: {
        type: Boolean,
        default: false,
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
    <div class="overflow-hidden rounded-3xl border border-[#ddd6fe] bg-white shadow-sm">
        <table class="min-w-full divide-y divide-[#ede9fe]">
            <thead class="bg-[#faf5ff]">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-[#6b21a8]">
                        Titulo
                    </th>
                    <th
                        v-if="showRequester"
                        class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-[#6b21a8]"
                    >
                        Cliente
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-[#6b21a8]">
                        Status
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-[#6b21a8]">
                        Criado em
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-[#6b21a8]">
                        Atualizado em
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#ede9fe] bg-white">
                <tr v-if="!tickets.length">
                    <td
                        :colspan="showRequester ? 5 : 4"
                        class="px-4 py-6 text-center text-sm text-[#9ca3af]"
                    >
                        Nenhum ticket encontrado.
                    </td>
                </tr>
                <tr v-for="ticket in tickets" :key="ticket.id" class="border-l-4 border-l-transparent transition duration-150 ease-in-out hover:border-l-[#7c3aed] hover:bg-[#faf5ff]">
                    <td class="px-4 py-3 text-sm font-semibold text-[#1f2937]">
                        <Link
                            :href="route('tickets.show', ticket.id)"
                            class="tm-link inline-block"
                        >
                            {{ ticket.title }}
                        </Link>
                    </td>
                    <td v-if="showRequester" class="px-4 py-3 text-sm text-[#374151]">
                        {{ ticket.requester ?? '-' }}
                    </td>
                    <td class="px-4 py-3 text-sm text-[#374151]">
                        <span class="inline-flex items-center rounded-full border border-[#c4b5fd] bg-[#f5f3ff] px-3 py-1 text-xs font-semibold text-[#6b21a8]">
                            {{ formatStatus(ticket.status) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm text-[#374151]">
                        {{ formatDate(ticket.created_at) }}
                    </td>
                    <td class="px-4 py-3 text-sm text-[#374151]">
                        {{ formatDate(ticket.updated_at) }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
