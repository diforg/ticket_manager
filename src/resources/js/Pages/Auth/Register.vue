<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    role: 'client',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Cadastrar" />

        <div class="mb-6 text-center">
            <h1 class="text-2xl font-semibold tracking-tight text-white">Criar conta</h1>
            <p class="mt-2 text-sm text-zinc-400">
                Cadastre-se para abrir e acompanhar seus tickets de suporte.
            </p>
        </div>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-zinc-200">
                    Nome
                </label>

                <input
                    id="name"
                    type="text"
                    class="mt-1 block w-full rounded-xl border border-white/10 bg-black/35 px-4 py-3 text-sm text-zinc-100 placeholder-zinc-500 shadow-inner shadow-black/30 transition duration-200 focus:border-emerald-400/70 focus:outline-none focus:ring-2 focus:ring-emerald-400/35"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                    placeholder="Seu nome completo"
                />

                <p v-if="form.errors.name" class="mt-2 text-sm text-rose-300">
                    {{ form.errors.name }}
                </p>
            </div>

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
                    autocomplete="username"
                    placeholder="seuemail@empresa.com"
                />

                <p v-if="form.errors.email" class="mt-2 text-sm text-rose-300">
                    {{ form.errors.email }}
                </p>
            </div>

            <fieldset>
                <legend class="block text-sm font-medium text-zinc-200">Perfil</legend>

                <div class="mt-2 grid grid-cols-2 gap-2 rounded-xl border border-white/10 bg-black/35 p-1">
                    <label
                        class="flex cursor-pointer items-center justify-center rounded-lg px-3 py-2 text-sm transition duration-200"
                        :class="form.role === 'client' ? 'bg-emerald-400/20 text-emerald-200 ring-1 ring-emerald-400/40' : 'text-zinc-300 hover:bg-white/5'"
                    >
                        <input
                            v-model="form.role"
                            type="radio"
                            value="client"
                            name="role"
                            class="sr-only"
                        />
                        Cliente
                    </label>

                    <label
                        class="flex cursor-pointer items-center justify-center rounded-lg px-3 py-2 text-sm transition duration-200"
                        :class="form.role === 'attendant' ? 'bg-emerald-400/20 text-emerald-200 ring-1 ring-emerald-400/40' : 'text-zinc-300 hover:bg-white/5'"
                    >
                        <input
                            v-model="form.role"
                            type="radio"
                            value="attendant"
                            name="role"
                            class="sr-only"
                        />
                        Atendente
                    </label>
                </div>

                <p v-if="form.errors.role" class="mt-2 text-sm text-rose-300">
                    {{ form.errors.role }}
                </p>
            </fieldset>

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
                    autocomplete="new-password"
                    placeholder="Crie uma senha"
                />

                <p v-if="form.errors.password" class="mt-2 text-sm text-rose-300">
                    {{ form.errors.password }}
                </p>
            </div>

            <div>
                <label
                    for="password_confirmation"
                    class="block text-sm font-medium text-zinc-200"
                >
                    Confirmar senha
                </label>

                <input
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full rounded-xl border border-white/10 bg-black/35 px-4 py-3 text-sm text-zinc-100 placeholder-zinc-500 shadow-inner shadow-black/30 transition duration-200 focus:border-emerald-400/70 focus:outline-none focus:ring-2 focus:ring-emerald-400/35"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="Repita sua senha"
                />

                <p v-if="form.errors.password_confirmation" class="mt-2 text-sm text-rose-300">
                    {{ form.errors.password_confirmation }}
                </p>
            </div>

            <button
                type="submit"
                class="inline-flex w-full items-center justify-center rounded-2xl bg-emerald-400 px-6 py-3 text-sm font-semibold text-black shadow-[0_0_30px_rgba(16,185,129,0.25)] transition duration-200 hover:bg-emerald-300 hover:shadow-[0_0_42px_rgba(16,185,129,0.35)] focus:outline-none focus:ring-2 focus:ring-emerald-300 focus:ring-offset-2 focus:ring-offset-[#0b0b0b] disabled:cursor-not-allowed disabled:opacity-60"
                :class="{ 'opacity-80': form.processing }"
                :disabled="form.processing"
            >
                Cadastrar
            </button>

            <div class="pt-1 text-center text-sm text-zinc-400">
                Já tem uma conta?
                <Link
                    :href="route('login')"
                    class="font-medium text-zinc-200 transition duration-200 hover:text-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-300/50 focus:ring-offset-2 focus:ring-offset-[#0b0b0b]"
                >
                    Entrar
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
