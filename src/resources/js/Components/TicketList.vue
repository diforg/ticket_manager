<script setup>
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
    <div class="overflow-hidden rounded-lg border border-zinc-700 bg-zinc-900 shadow-sm">
        <table class="min-w-full divide-y divide-zinc-700">
            <thead class="bg-zinc-800">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-emerald-300">
                        Titulo
                    </th>
                    <th
                        v-if="showRequester"
                        class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-emerald-300"
                    >
                        Cliente
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-emerald-300">
                        Status
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-emerald-300">
                        Criado em
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-emerald-300">
                        Atualizado em
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-700 bg-zinc-900">
                <tr v-if="!tickets.length">
                    <td
                        :colspan="showRequester ? 5 : 4"
                        class="px-4 py-6 text-center text-sm text-zinc-400"
                    >
                        Nenhum ticket encontrado.
                    </td>
                </tr>
                <tr v-for="ticket in tickets" :key="ticket.id" class="border-l-4 border-l-transparent transition duration-150 ease-in-out hover:border-l-emerald-400 hover:bg-zinc-800/50">
                    <td class="px-4 py-3 text-sm font-medium text-zinc-100">
                        {{ ticket.title }}
                    </td>
                    <td v-if="showRequester" class="px-4 py-3 text-sm text-zinc-300">
                        {{ ticket.requester ?? '-' }}
                    </td>
                    <td class="px-4 py-3 text-sm text-zinc-300">
                        <span class="inline-flex items-center rounded-md border border-zinc-700 bg-zinc-800 px-2 py-1 text-xs font-medium text-zinc-100">
                            {{ formatStatus(ticket.status) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm text-zinc-300">
                        {{ formatDate(ticket.created_at) }}
                    </td>
                    <td class="px-4 py-3 text-sm text-zinc-300">
                        {{ formatDate(ticket.updated_at) }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
