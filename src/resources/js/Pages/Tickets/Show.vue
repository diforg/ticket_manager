<script setup>
import { computed, ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Modal from '@/Components/Modal.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';

const props = defineProps({
    ticket: {
        type: Object,
        required: true,
    },
});

const page = usePage();
const isAttendant = page.props.auth.user.role === 'attendant';
const isClient = page.props.auth.user.role === 'client';

const statusForm = useForm({
    status: props.ticket.status,
});

const messageForm = useForm({
    body: '',
    attachments: [],
});

const attachmentInput = ref(null);
const selectedAttachments = ref([]);
const attachmentError = ref('');
const previewAttachment = ref(null);

const allowedAttachmentMimeTypes = new Set([
    'image/jpeg',
    'image/png',
    'image/gif',
    'image/webp',
    'application/pdf',
]);

const attachmentServerError = computed(() => {
    const entry = Object.entries(messageForm.errors).find(([key]) => key.startsWith('attachments'));

    return entry?.[1] ?? '';
});

const statusLabel = {
    open: 'Aberto',
    in_progress: 'Em andamento',
    resolved: 'Resolvido',
};

const formatStatus = (status) => statusLabel[status] ?? status;

const submitStatus = () => {
    statusForm.patch(route('tickets.status.update', props.ticket), {
        preserveScroll: true,
    });
};

const submitMessage = () => {
    attachmentError.value = '';

    const invalidAttachment = selectedAttachments.value.find((file) => validateAttachment(file) !== '');

    if (invalidAttachment) {
        attachmentError.value = validateAttachment(invalidAttachment);

        return;
    }

    messageForm.attachments = selectedAttachments.value;

    messageForm.post(route('tickets.messages.store', props.ticket), {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            messageForm.reset('body');
            messageForm.attachments = [];
            selectedAttachments.value = [];
            attachmentError.value = '';
            attachmentInput.value.value = '';
        },
    });
};

const openAttachmentPicker = () => {
    attachmentInput.value?.click();
};

const validateAttachment = (file) => {
    if (!allowedAttachmentMimeTypes.has(file.type)) {
        return 'Envie apenas imagens JPG, JPEG, PNG, GIF, WEBP ou PDF.';
    }

    if (file.size > 10 * 1024 * 1024) {
        return 'Cada anexo deve ter no máximo 10 MB.';
    }

    return '';
};

const handleAttachmentChange = (event) => {
    const files = Array.from(event.target.files ?? []);

    if (files.length === 0) {
        return;
    }

    const acceptedFiles = [];

    files.forEach((file) => {
        const errorMessage = validateAttachment(file);

        if (errorMessage !== '') {
            attachmentError.value = errorMessage;

            return;
        }

        acceptedFiles.push(file);
    });

    selectedAttachments.value = [...selectedAttachments.value, ...acceptedFiles];
    event.target.value = '';
};

const removeAttachment = (index) => {
    selectedAttachments.value = selectedAttachments.value.filter((_, currentIndex) => currentIndex !== index);
};

const formatFileSize = (bytes) => {
    if (bytes < 1024) {
        return `${bytes} B`;
    }

    if (bytes < 1024 * 1024) {
        return `${(bytes / 1024).toFixed(1)} KB`;
    }

    return `${(bytes / (1024 * 1024)).toFixed(1)} MB`;
};

const openMessageAttachment = (attachment) => {
    if (attachment.display_type === 'image') {
        previewAttachment.value = attachment;

        return;
    }

    window.open(attachment.public_url, '_blank', 'noopener,noreferrer');
};

const closeAttachmentPreview = () => {
    previewAttachment.value = null;
};

const formatDate = (value) => {
    if (!value) {
        return '-';
    }

    return new Intl.DateTimeFormat('pt-BR', {
        dateStyle: 'short',
        timeStyle: 'short',
    }).format(new Date(value));
};

const senderLabel = (sender) => {
    if (!sender) {
        return 'Usuário';
    }

    const role = sender.role === 'attendant' ? 'Atendente' : 'Cliente';

    return `${role}: ${sender.name}`;
};
</script>

<template>
    <Head :title="ticket.title" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-zinc-100">
                {{ ticket.title }}
            </h2>
        </template>

        <div class="py-12 bg-[#080808]">
            <div class="mx-auto max-w-4xl space-y-6 sm:px-6 lg:px-8">
                <div class="rounded-lg border border-zinc-700 bg-zinc-900 p-6 shadow-sm sm:p-8">
                    <div class="space-y-6">
                        <div>
                            <p class="text-sm font-medium uppercase tracking-wider text-emerald-300">
                                Status
                            </p>
                            <template v-if="isAttendant">
                                <form @submit.prevent="submitStatus" class="mt-2 space-y-3">
                                    <select
                                        v-model="statusForm.status"
                                        class="block w-full rounded-md border-zinc-700 bg-zinc-800 text-zinc-100 shadow-sm focus:border-emerald-400 focus:ring-emerald-400"
                                    >
                                        <option value="open">Aberto</option>
                                        <option value="in_progress">Em andamento</option>
                                        <option value="resolved">Resolvido</option>
                                    </select>

                                    <InputError :message="statusForm.errors.status" />

                                    <div class="flex items-center gap-4">
                                        <PrimaryButton :disabled="statusForm.processing">
                                            Salvar Status
                                        </PrimaryButton>
                                    </div>

                                </form>
                            </template>
                            <p v-else class="mt-1 text-base text-zinc-100">
                                {{ formatStatus(ticket.status) }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm font-medium uppercase tracking-wider text-emerald-300">
                                Descrição
                            </p>
                            <p class="mt-1 whitespace-pre-line text-sm leading-6 text-zinc-300">
                                {{ ticket.description }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm font-medium uppercase tracking-wider text-emerald-300">
                                Conversa
                            </p>

                            <div class="mt-3 space-y-3">
                                <div
                                    v-for="message in ticket.messages"
                                    :key="message.id"
                                    class="rounded-md border border-zinc-700 bg-zinc-800 p-4"
                                >
                                    <div class="flex flex-wrap items-center justify-between gap-2">
                                        <p class="text-xs font-semibold uppercase tracking-wider text-zinc-300">
                                            {{ senderLabel(message.sender) }}
                                        </p>
                                        <p class="text-xs text-zinc-400">
                                            {{ formatDate(message.created_at) }}
                                        </p>
                                    </div>
                                    <p class="mt-2 whitespace-pre-line text-sm leading-6 text-zinc-200">
                                        {{ message.body }}
                                    </p>

                                    <div v-if="message.attachments.length > 0" class="mt-4 space-y-2">
                                        <button
                                            v-for="attachment in message.attachments"
                                            :key="attachment.id"
                                            type="button"
                                            class="flex w-full items-center gap-3 rounded-md border border-zinc-700 bg-zinc-900/80 px-3 py-2 text-left transition hover:border-emerald-400"
                                            @click="openMessageAttachment(attachment)"
                                        >
                                            <img
                                                v-if="attachment.display_type === 'image'"
                                                :src="attachment.public_url"
                                                :alt="attachment.file_name"
                                                class="h-12 w-12 rounded object-cover"
                                            >
                                            <div
                                                v-else
                                                class="flex h-12 w-12 items-center justify-center rounded bg-red-500/15 text-red-300"
                                            >
                                                <svg viewBox="0 0 24 24" fill="none" class="h-7 w-7" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M14.25 2.25H6.75a1.5 1.5 0 0 0-1.5 1.5v16.5a1.5 1.5 0 0 0 1.5 1.5h10.5a1.5 1.5 0 0 0 1.5-1.5V7.5L14.25 2.25z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M14.25 2.25V7.5h5.25" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.75 12.75h4.5M9.75 16.5h4.5" />
                                                </svg>
                                            </div>

                                            <div class="min-w-0 flex-1">
                                                <p class="truncate text-sm font-medium text-zinc-100">
                                                    {{ attachment.file_name }}
                                                </p>
                                                <p class="text-xs uppercase tracking-wider text-zinc-400">
                                                    {{ attachment.display_type === 'image' ? 'Imagem' : 'PDF' }}
                                                </p>
                                            </div>
                                        </button>
                                    </div>
                                </div>

                                <p
                                    v-if="ticket.messages.length === 0"
                                    class="rounded-md border border-dashed border-zinc-700 bg-zinc-800/50 p-4 text-sm text-zinc-400"
                                >
                                    Ainda não há mensagens neste ticket.
                                </p>
                            </div>

                            <form @submit.prevent="submitMessage" class="mt-4 space-y-3">
                                <label class="block text-sm font-medium text-zinc-200" for="message-body">
                                    Nova mensagem
                                </label>
                                <textarea
                                    id="message-body"
                                    v-model="messageForm.body"
                                    rows="4"
                                    class="block w-full rounded-md border-zinc-700 bg-zinc-800 text-zinc-100 shadow-sm focus:border-emerald-400 focus:ring-emerald-400"
                                    placeholder="Digite sua mensagem"
                                />

                                <InputError :message="messageForm.errors.body" />

                                <div v-if="isClient" class="space-y-3 rounded-lg border border-dashed border-zinc-700 bg-zinc-800/40 p-4">
                                    <input
                                        ref="attachmentInput"
                                        type="file"
                                        multiple
                                        accept=".jpg,.jpeg,.png,.gif,.webp,.pdf,image/jpeg,image/png,image/gif,image/webp,application/pdf"
                                        class="hidden"
                                        @change="handleAttachmentChange"
                                    >

                                    <button
                                        type="button"
                                        class="inline-flex items-center gap-2 rounded-md border border-zinc-700 bg-zinc-900 px-3 py-2 text-sm font-medium text-zinc-100 transition hover:border-emerald-400 hover:text-emerald-300"
                                        @click="openAttachmentPicker"
                                    >
                                        <svg viewBox="0 0 24 24" fill="none" class="h-4 w-4" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21.75 12.75l-8.25 8.25a6 6 0 0 1-8.485-8.485l8.25-8.25a4.5 4.5 0 1 1 6.364 6.364l-8.25 8.25a3 3 0 1 1-4.243-4.243l7.5-7.5" />
                                        </svg>
                                        Anexar arquivo
                                    </button>

                                    <p class="text-xs text-zinc-400">
                                        JPG, JPEG, PNG, GIF, WEBP ou PDF até 10 MB por arquivo.
                                    </p>

                                    <p v-if="attachmentError || attachmentServerError" class="text-sm text-red-400">
                                        {{ attachmentError || attachmentServerError }}
                                    </p>

                                    <div v-if="selectedAttachments.length > 0" class="space-y-2">
                                        <div
                                            v-for="(attachment, index) in selectedAttachments"
                                            :key="`${attachment.name}-${index}`"
                                            class="flex items-center justify-between gap-3 rounded-md border border-zinc-700 bg-zinc-900 px-3 py-2"
                                        >
                                            <div class="min-w-0">
                                                <p class="truncate text-sm text-zinc-100">
                                                    {{ attachment.name }}
                                                </p>
                                                <p class="text-xs text-zinc-400">
                                                    {{ formatFileSize(attachment.size) }}
                                                </p>
                                            </div>

                                            <button
                                                type="button"
                                                class="text-xs font-semibold uppercase tracking-wider text-zinc-400 transition hover:text-red-300"
                                                @click="removeAttachment(index)"
                                            >
                                                Remover
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex justify-end">
                                    <PrimaryButton :disabled="messageForm.processing">
                                        Enviar Mensagem
                                    </PrimaryButton>
                                </div>
                            </form>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <p class="text-sm font-medium uppercase tracking-wider text-emerald-300">
                                    Criado em
                                </p>
                                <p class="mt-1 text-sm text-zinc-300">
                                    {{ formatDate(ticket.created_at) }}
                                </p>
                            </div>

                        </div>

                        <div class="flex items-center justify-end gap-4">
                            <Link
                                :href="route('dashboard')"
                                class="inline-flex items-center rounded-md border border-zinc-700 bg-zinc-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-zinc-100 transition duration-150 ease-in-out hover:border-emerald-400 hover:text-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:ring-opacity-50"
                            >
                                Voltar ao Dashboard
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <Modal :show="previewAttachment !== null" maxWidth="3xl" @close="closeAttachmentPreview">
            <div v-if="previewAttachment" class="space-y-4 p-6">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-wider text-emerald-300">
                            Pré-visualização
                        </p>
                        <h3 class="mt-1 text-lg font-semibold text-zinc-100">
                            {{ previewAttachment.file_name }}
                        </h3>
                    </div>

                    <button
                        type="button"
                        class="rounded-md border border-zinc-700 bg-zinc-800 px-3 py-2 text-sm text-zinc-100 transition hover:border-emerald-400 hover:text-emerald-300"
                        @click="closeAttachmentPreview"
                    >
                        Fechar
                    </button>
                </div>

                <img
                    :src="previewAttachment.public_url"
                    :alt="previewAttachment.file_name"
                    class="max-h-[75vh] w-full rounded-lg object-contain bg-zinc-950"
                >
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
