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
    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                        Titulo
                    </th>
                    <th
                        v-if="showRequester"
                        class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600"
                    >
                        Cliente
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                        Status
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                        Criado em
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                        Atualizado em
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                <tr v-if="!tickets.length">
                    <td
                        :colspan="showRequester ? 5 : 4"
                        class="px-4 py-6 text-center text-sm text-gray-500"
                    >
                        Nenhum ticket encontrado.
                    </td>
                </tr>
                <tr v-for="ticket in tickets" :key="ticket.id" class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm font-medium text-gray-900">
                        {{ ticket.title }}
                    </td>
                    <td v-if="showRequester" class="px-4 py-3 text-sm text-gray-700">
                        {{ ticket.requester ?? '-' }}
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-700">
                        {{ formatStatus(ticket.status) }}
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-700">
                        {{ formatDate(ticket.created_at) }}
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-700">
                        {{ formatDate(ticket.updated_at) }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
