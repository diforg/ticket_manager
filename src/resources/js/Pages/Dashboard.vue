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
                class="text-xl font-extrabold leading-tight text-[#111827]"
            >
                {{ dashboardLabel }}
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden rounded-3xl border border-[#ddd6fe] bg-white shadow-sm"
                >
                    <div class="p-6 text-[#1f2937]">
                        {{ dashboardDescription }}
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
