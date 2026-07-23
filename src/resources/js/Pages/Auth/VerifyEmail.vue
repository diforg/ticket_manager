<script setup>
import { computed } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(
    () => props.status === 'verification-link-sent',
);
</script>

<template>
    <GuestLayout>
        <Head title="Verificacao de E-mail" />

        <div class="mb-4 text-sm text-[#6b7280]">
            Obrigado por se cadastrar. Antes de comecar, confirme seu e-mail clicando no link que acabamos de enviar.
        </div>

        <div
            class="mb-4 rounded-2xl border border-[#c4b5fd] bg-[#f5f3ff] px-4 py-3 text-sm font-medium text-[#6b21a8]"
            v-if="verificationLinkSent"
        >
            A new verification link has been sent to the email address you
            provided during registration.
        </div>

        <form @submit.prevent="submit">
            <div class="mt-4 flex items-center justify-between">
                <PrimaryButton
                    :class="{ 'opacity-60': form.processing }"
                    :disabled="form.processing"
                >
                    Reenviar e-mail de verificacao
                </PrimaryButton>

                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="tm-link text-sm"
                    >Sair</Link
                >
            </div>
        </form>
    </GuestLayout>
</template>
