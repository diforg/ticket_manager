<script setup>
import TicketList from '@/Components/TicketList.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    tickets: {
        type: Array,
        default: () => [],
    },
    filters: {
        type: Object,
        default: () => ({
            status: null,
        }),
    },
});

const selectedStatus = ref(props.filters.status ?? '');

const applyFilter = () => {
    const query = {};

    if (selectedStatus.value) {
        query.status = selectedStatus.value;
    }

    router.get(route('attendant.dashboard'), query, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
        only: ['tickets', 'filters'],
    });
};
</script>

<template>
    <Head title="Dashboard Atendente" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-zinc-100">
                Dashboard do Atendente
            </h2>
        </template>

        <div class="py-12 bg-[#080808]">
            <div class="mx-auto max-w-7xl space-y-4 sm:px-6 lg:px-8">
                <div class="flex flex-col gap-2 sm:w-64">
                    <label for="status-filter" class="text-sm font-medium text-zinc-300">
                        Filtrar por status
                    </label>
                    <select
                        id="status-filter"
                        v-model="selectedStatus"
                        @change="applyFilter"
                        class="rounded-md border-zinc-700 bg-zinc-800 text-zinc-100 text-sm shadow-sm focus:border-emerald-400 focus:ring-emerald-400"
                    >
                        <option value="" class="bg-zinc-800 text-zinc-100">Todos</option>
                        <option value="open" class="bg-zinc-800 text-zinc-100">Aberto</option>
                        <option value="in_progress" class="bg-zinc-800 text-zinc-100">Em andamento</option>
                        <option value="resolved" class="bg-zinc-800 text-zinc-100">Resolvido</option>
                    </select>
                </div>

                <TicketList :tickets="tickets" :show-requester="true" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
