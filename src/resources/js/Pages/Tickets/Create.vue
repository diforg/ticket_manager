<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Textarea from '@/Components/Textarea.vue';
import { useForm, Head } from '@inertiajs/vue3';

const form = useForm({
    title: '',
    description: '',
});

const submit = () => {
    form.post(route('tickets.store'));
};

const cancel = () => {
    window.history.back();
};
</script>

<template>
    <Head title="Novo Ticket" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-zinc-100">
                Novo Ticket
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
                <div class="bg-zinc-900 border border-zinc-700 p-4 sm:rounded-lg sm:p-8">
                    <form @submit.prevent="submit" class="space-y-6">
                        <div>
                            <InputLabel for="title" value="Título" />

                            <TextInput
                                id="title"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.title"
                                required
                                autofocus
                                placeholder="Descreva brevemente o assunto do ticket"
                            />

                            <InputError class="mt-2" :message="form.errors.title" />
                        </div>

                        <div>
                            <InputLabel for="description" value="Descrição" />

                            <Textarea
                                id="description"
                                class="mt-1 block w-full"
                                v-model="form.description"
                                required
                                placeholder="Detalhe o problema ou solicitação"
                            />

                            <InputError class="mt-2" :message="form.errors.description" />
                        </div>

                        <div class="flex items-center gap-4">
                            <PrimaryButton :disabled="form.processing">
                                Criar Ticket
                            </PrimaryButton>

                            <SecondaryButton type="button" @click="cancel">
                                Cancelar
                            </SecondaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
