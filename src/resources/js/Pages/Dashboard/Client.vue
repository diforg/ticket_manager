<script setup>
import TicketList from '@/Components/TicketList.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    tickets: {
        type: Array,
        default: () => [],
    },
});
</script>

<template>
    <Head title="Dashboard Cliente" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Dashboard do Cliente
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-4 sm:px-6 lg:px-8">
                <p class="text-sm text-gray-600">
                    Listagem dos seus tickets de suporte.
                </p>

                <TicketList :tickets="tickets" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
