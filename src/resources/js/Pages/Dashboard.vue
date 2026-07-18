<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    dashboardLabel: {
        type: String,
        default: 'Dashboard',
    },
    dashboardDescription: {
        type: String,
        default: 'Você está autenticado e pode acessar as funcionalidades do sistema.',
    },
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                {{ dashboardLabel }}
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900">
                        {{ dashboardDescription }}
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
