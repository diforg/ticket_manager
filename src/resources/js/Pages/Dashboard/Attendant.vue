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
            <h2 class="text-xl font-extrabold leading-tight text-[#111827]">
                Dashboard do Atendente
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-4 sm:px-6 lg:px-8">
                <div class="flex flex-col gap-2 sm:w-64">
                    <label for="status-filter" class="tm-label">
                        Filtrar por status
                    </label>
                    <select
                        id="status-filter"
                        v-model="selectedStatus"
                        @change="applyFilter"
                        class="tm-select text-sm"
                    >
                        <option value="">Todos</option>
                        <option value="open">Aberto</option>
                        <option value="in_progress">Em andamento</option>
                        <option value="resolved">Resolvido</option>
                    </select>
                </div>

                <TicketList :tickets="tickets" :show-requester="true" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
