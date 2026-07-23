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
            <h2 class="text-xl font-extrabold leading-tight text-[#111827]">
                {{ ticket.title }}
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-4xl space-y-6 sm:px-6 lg:px-8">
                <div class="rounded-3xl border border-[#ddd6fe] bg-white p-6 shadow-sm sm:p-8">
                    <div class="space-y-6">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-wider text-[#6b21a8]">
                                Status
                            </p>
                            <template v-if="isAttendant">
                                <form @submit.prevent="submitStatus" class="mt-2 space-y-3">
                                    <select
                                        v-model="statusForm.status"
                                        class="tm-select mt-2 block"
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
                            <p v-else class="mt-1 text-base text-[#1f2937]">
                                {{ formatStatus(ticket.status) }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm font-semibold uppercase tracking-wider text-[#6b21a8]">
                                Descrição
                            </p>
                            <p class="mt-1 whitespace-pre-line text-sm leading-6 text-[#374151]">
                                {{ ticket.description }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm font-semibold uppercase tracking-wider text-[#6b21a8]">
                                Conversa
                            </p>

                            <div class="mt-3 space-y-3">
                                <div
                                    v-for="message in ticket.messages"
                                    :key="message.id"
                                    class="rounded-2xl border border-[#e9d5ff] bg-[#faf5ff] p-4"
                                >
                                    <div class="flex flex-wrap items-center justify-between gap-2">
                                        <p class="text-xs font-semibold uppercase tracking-wider text-[#6b21a8]">
                                            {{ senderLabel(message.sender) }}
                                        </p>
                                        <p class="text-xs text-[#9ca3af]">
                                            {{ formatDate(message.created_at) }}
                                        </p>
                                    </div>
                                    <p class="mt-2 whitespace-pre-line text-sm leading-6 text-[#374151]">
                                        {{ message.body }}
                                    </p>

                                    <div v-if="message.attachments.length > 0" class="mt-4 space-y-2">
                                        <button
                                            v-for="attachment in message.attachments"
                                            :key="attachment.id"
                                            type="button"
                                            class="flex w-full items-center gap-3 rounded-2xl border border-[#ddd6fe] bg-white px-3 py-2 text-left transition hover:border-[#7c3aed]"
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
                                                class="flex h-12 w-12 items-center justify-center rounded-xl bg-red-100 text-red-500"
                                            >
                                                <svg viewBox="0 0 24 24" fill="none" class="h-7 w-7" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M14.25 2.25H6.75a1.5 1.5 0 0 0-1.5 1.5v16.5a1.5 1.5 0 0 0 1.5 1.5h10.5a1.5 1.5 0 0 0 1.5-1.5V7.5L14.25 2.25z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M14.25 2.25V7.5h5.25" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.75 12.75h4.5M9.75 16.5h4.5" />
                                                </svg>
                                            </div>

                                            <div class="min-w-0 flex-1">
                                                <p class="truncate text-sm font-semibold text-[#1f2937]">
                                                    {{ attachment.file_name }}
                                                </p>
                                                <p class="text-xs uppercase tracking-wider text-[#9ca3af]">
                                                    {{ attachment.display_type === 'image' ? 'Imagem' : 'PDF' }}
                                                </p>
                                            </div>
                                        </button>
                                    </div>
                                </div>

                                <p
                                    v-if="ticket.messages.length === 0"
                                    class="rounded-2xl border border-dashed border-[#c4b5fd] bg-[#faf5ff] p-4 text-sm text-[#9ca3af]"
                                >
                                    Ainda não há mensagens neste ticket.
                                </p>
                            </div>

                            <form @submit.prevent="submitMessage" class="mt-4 space-y-3">
                                <label class="tm-label" for="message-body">
                                    Nova mensagem
                                </label>
                                <textarea
                                    id="message-body"
                                    v-model="messageForm.body"
                                    rows="4"
                                    class="tm-textarea block"
                                    placeholder="Digite sua mensagem"
                                />

                                <InputError :message="messageForm.errors.body" />

                                <div v-if="isClient" class="space-y-3 rounded-2xl border border-dashed border-[#c4b5fd] bg-[#faf5ff] p-4">
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
                                        class="tm-btn-secondary items-center gap-2 px-3 py-2"
                                        @click="openAttachmentPicker"
                                    >
                                        <svg viewBox="0 0 24 24" fill="none" class="h-4 w-4" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21.75 12.75l-8.25 8.25a6 6 0 0 1-8.485-8.485l8.25-8.25a4.5 4.5 0 1 1 6.364 6.364l-8.25 8.25a3 3 0 1 1-4.243-4.243l7.5-7.5" />
                                        </svg>
                                        Anexar arquivo
                                    </button>

                                    <p class="text-xs text-[#9ca3af]">
                                        JPG, JPEG, PNG, GIF, WEBP ou PDF até 10 MB por arquivo.
                                    </p>

                                    <p v-if="attachmentError || attachmentServerError" class="text-sm text-red-400">
                                        {{ attachmentError || attachmentServerError }}
                                    </p>

                                    <div v-if="selectedAttachments.length > 0" class="space-y-2">
                                        <div
                                            v-for="(attachment, index) in selectedAttachments"
                                            :key="`${attachment.name}-${index}`"
                                            class="flex items-center justify-between gap-3 rounded-2xl border border-[#ddd6fe] bg-white px-3 py-2"
                                        >
                                            <div class="min-w-0">
                                                <p class="truncate text-sm text-[#1f2937]">
                                                    {{ attachment.name }}
                                                </p>
                                                <p class="text-xs text-[#9ca3af]">
                                                    {{ formatFileSize(attachment.size) }}
                                                </p>
                                            </div>

                                            <button
                                                type="button"
                                                class="text-xs font-semibold uppercase tracking-wider text-[#6b7280] transition hover:text-red-500"
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
                                <p class="text-sm font-semibold uppercase tracking-wider text-[#6b21a8]">
                                    Criado em
                                </p>
                                <p class="mt-1 text-sm text-[#374151]">
                                    {{ formatDate(ticket.created_at) }}
                                </p>
                            </div>

                        </div>

                        <div class="flex items-center justify-end gap-4">
                            <Link
                                :href="route('dashboard')"
                                class="tm-btn-secondary rounded-full px-4 py-2 text-xs uppercase tracking-widest"
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
                        <p class="text-sm font-semibold uppercase tracking-wider text-[#6b21a8]">
                            Pré-visualização
                        </p>
                        <h3 class="mt-1 text-lg font-bold text-[#111827]">
                            {{ previewAttachment.file_name }}
                        </h3>
                    </div>

                    <button
                        type="button"
                        class="tm-btn-secondary px-3 py-2 text-sm"
                        @click="closeAttachmentPreview"
                    >
                        Fechar
                    </button>
                </div>

                <img
                    :src="previewAttachment.public_url"
                    :alt="previewAttachment.file_name"
                    class="max-h-[75vh] w-full rounded-2xl object-contain bg-[#faf5ff]"
                >
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
