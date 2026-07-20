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
            <h1 class="text-2xl font-semibold tracking-tight text-white">Entrar</h1>
            <p class="mt-2 text-sm text-zinc-400">
                Acesse sua conta para acompanhar os tickets em andamento.
            </p>
        </div>

        <div
            v-if="status"
            class="mb-4 rounded-xl border border-emerald-400/35 bg-emerald-400/10 px-4 py-3 text-sm font-medium text-emerald-200"
        >
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <label for="email" class="block text-sm font-medium text-zinc-200">
                    E-mail
                </label>

                <input
                    id="email"
                    type="email"
                    class="mt-1 block w-full rounded-xl border border-white/10 bg-black/35 px-4 py-3 text-sm text-zinc-100 placeholder-zinc-500 shadow-inner shadow-black/30 transition duration-200 focus:border-emerald-400/70 focus:outline-none focus:ring-2 focus:ring-emerald-400/35"
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
                    class="block text-sm font-medium text-zinc-200"
                >
                    Senha
                </label>

                <input
                    id="password"
                    type="password"
                    class="mt-1 block w-full rounded-xl border border-white/10 bg-black/35 px-4 py-3 text-sm text-zinc-100 placeholder-zinc-500 shadow-inner shadow-black/30 transition duration-200 focus:border-emerald-400/70 focus:outline-none focus:ring-2 focus:ring-emerald-400/35"
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
                <label class="inline-flex items-center gap-2 text-sm text-zinc-300">
                    <input
                        name="remember"
                        type="checkbox"
                        v-model="form.remember"
                        class="h-4 w-4 rounded border-white/20 bg-black/35 text-emerald-400 focus:ring-2 focus:ring-emerald-400/45"
                    />
                    Lembrar-me
                </label>

                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="text-sm text-zinc-300 transition duration-200 hover:text-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-300/50 focus:ring-offset-2 focus:ring-offset-[#0b0b0b]"
                >
                    Esqueci minha senha
                </Link>
            </div>

            <button
                type="submit"
                class="inline-flex w-full items-center justify-center rounded-2xl bg-emerald-400 px-6 py-3 text-sm font-semibold text-black shadow-[0_0_30px_rgba(16,185,129,0.25)] transition duration-200 hover:bg-emerald-300 hover:shadow-[0_0_42px_rgba(16,185,129,0.35)] focus:outline-none focus:ring-2 focus:ring-emerald-300 focus:ring-offset-2 focus:ring-offset-[#0b0b0b] disabled:cursor-not-allowed disabled:opacity-60"
                :class="{ 'opacity-80': form.processing }"
                :disabled="form.processing"
            >
                Entrar
            </button>

            <div class="pt-1 text-center text-sm text-zinc-400">
                Não tem uma conta?
                <Link
                    :href="route('register')"
                    class="font-medium text-zinc-200 transition duration-200 hover:text-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-300/50 focus:ring-offset-2 focus:ring-offset-[#0b0b0b]"
                >
                    Cadastre-se
                </Link>
            </div>

            <div class="text-center text-sm text-zinc-500">
                <Link
                    href="/"
                    class="transition duration-200 hover:text-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-300/50 focus:ring-offset-2 focus:ring-offset-[#0b0b0b]"
                >
                    Voltar para a página inicial
                </Link>
            </div>
        </form>
    </GuestLayout>
</template>
