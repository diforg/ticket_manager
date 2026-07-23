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
            <h1 class="text-3xl font-extrabold tracking-tight text-[#111827]">Criar conta</h1>
            <p class="mt-2 text-sm text-[#9ca3af]">
                Cadastre-se para abrir e acompanhar seus tickets de suporte.
            </p>
        </div>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <label for="name" class="tm-label">
                    Nome
                </label>

                <input
                    id="name"
                    type="text"
                    class="tm-input mt-1 block"
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
                <label for="email" class="tm-label">
                    E-mail
                </label>

                <input
                    id="email"
                    type="email"
                    class="tm-input mt-1 block"
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
                <legend class="tm-label">Perfil</legend>

                <div class="mt-2 grid grid-cols-2 gap-2 rounded-2xl border border-[#ddd6fe] bg-[#faf5ff] p-1">
                    <label
                        class="flex cursor-pointer items-center justify-center rounded-xl px-3 py-2 text-sm font-semibold transition duration-200"
                        :class="form.role === 'client' ? 'bg-[#6b21a8] text-white' : 'text-[#6b21a8] hover:bg-[#ede9fe]'"
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
                        class="flex cursor-pointer items-center justify-center rounded-xl px-3 py-2 text-sm font-semibold transition duration-200"
                        :class="form.role === 'attendant' ? 'bg-[#6b21a8] text-white' : 'text-[#6b21a8] hover:bg-[#ede9fe]'"
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
                    class="tm-label"
                >
                    Confirmar senha
                </label>

                <input
                    id="password_confirmation"
                    type="password"
                    class="tm-input mt-1 block"
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
                class="tm-btn-primary w-full"
                :class="{ 'opacity-80': form.processing }"
                :disabled="form.processing"
            >
                Cadastrar
            </button>

            <div class="pt-1 text-center text-sm text-[#9ca3af]">
                Já tem uma conta?
                <Link
                    :href="route('login')"
                    class="tm-link font-semibold"
                >
                    Entrar
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
