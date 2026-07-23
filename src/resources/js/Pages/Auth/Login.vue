<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Entrar" />

        <div class="mb-6 text-center">
            <h1 class="text-3xl font-extrabold tracking-tight text-[#111827]">Entrar</h1>
            <p class="mt-2 text-sm text-[#9ca3af]">
                Acesse sua conta para acompanhar os tickets em andamento.
            </p>
        </div>

        <div
            v-if="status"
            class="mb-4 rounded-2xl border border-[#c4b5fd] bg-[#f5f3ff] px-4 py-3 text-sm font-medium text-[#6b21a8]"
        >
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <label for="email" class="tm-label">
                    E-mail
                </label>

                <input
                    id="email"
                    type="email"
                    class="tm-input mt-1 block"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="seuemail@empresa.com"
                />

                <p v-if="form.errors.email" class="mt-2 text-sm text-rose-300">
                    {{ form.errors.email }}
                </p>
            </div>

            <div>
                <label
                    for="password"
                    class="tm-label"
                >
                    Senha
                </label>

                <input
                    id="password"
                    type="password"
                    class="tm-input mt-1 block"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                    placeholder="Sua senha"
                />

                <p v-if="form.errors.password" class="mt-2 text-sm text-rose-300">
                    {{ form.errors.password }}
                </p>
            </div>

            <div class="flex items-center justify-between gap-4">
                <label class="inline-flex items-center gap-2 text-sm text-[#374151]">
                    <input
                        name="remember"
                        type="checkbox"
                        v-model="form.remember"
                        class="h-4 w-4 rounded-md border border-[#c4b5fd] bg-[#f5f3ff] text-[#6b21a8] focus:ring-2 focus:ring-[#7c3aed]/35"
                    />
                    Lembrar-me
                </label>

                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="tm-link text-sm"
                >
                    Esqueci minha senha
                </Link>
            </div>

            <button
                type="submit"
                class="tm-btn-primary w-full"
                :class="{ 'opacity-80': form.processing }"
                :disabled="form.processing"
            >
                Entrar
            </button>

            <div class="pt-1 text-center text-sm text-[#9ca3af]">
                Não tem uma conta?
                <Link
                    :href="route('register')"
                    class="tm-link font-semibold"
                >
                    Cadastre-se
                </Link>
            </div>

            <div class="text-center text-sm text-[#9ca3af]">
                <Link
                    href="/"
                    class="tm-link"
                >
                    Voltar para a página inicial
                </Link>
            </div>
        </form>
    </GuestLayout>
</template>
