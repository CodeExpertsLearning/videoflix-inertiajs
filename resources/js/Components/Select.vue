<script setup>
defineProps({
    payloadOptions: {},
});

let model = defineModel();
</script>

<template>
    <select
        class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
        v-model="model"
    >
        <option value="">Selecione uma opção!</option>

        <option
            v-for="(op, index) of payloadOptions"
            :value="op.value"
            :key="index"
        >
            {{ op.label }}
        </option>
    </select>
</template>
