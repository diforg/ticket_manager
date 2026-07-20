<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: String,
    required: true,
});

const textarea = ref(null);

onMounted(() => {
    if (textarea.value.hasAttribute('autofocus')) {
        textarea.value.focus();
    }
});

defineExpose({ focus: () => textarea.value.focus() });
</script>

<template>
    <textarea
        class="rounded-md border border-zinc-700 bg-zinc-800 px-3 py-2 text-zinc-100 shadow-sm transition duration-150 ease-in-out placeholder-zinc-500 focus:border-emerald-400 focus:ring-2 focus:ring-emerald-400 focus:ring-opacity-50 resize-y"
        v-model="model"
        ref="textarea"
        rows="5"
    />
</template>
