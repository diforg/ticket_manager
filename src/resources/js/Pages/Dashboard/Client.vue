<script setup>
import TicketList from '@/Components/TicketList.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link } from '@inertiajs/vue3';

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
            <h2 class="text-xl font-semibold leading-tight text-zinc-100">
                Dashboard do Cliente
            </h2>
        </template>

        <div class="py-12 bg-[#080808]">
            <div class="mx-auto max-w-7xl space-y-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-zinc-300">
                        Listagem dos seus tickets de suporte.
                    </p>
                    
                    <Link :href="route('tickets.create')" as="button" type="button">
                        <PrimaryButton>
                            + Novo Ticket
                        </PrimaryButton>
                    </Link>
                </div>

                <TicketList :tickets="tickets" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
