<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Select from "@/Components/Select.vue";
import TextArea from "@/Components/TextArea.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";

import AutheticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import NavLink from "@/Components/NavLink.vue";

const form = useForm({
    title: "",
    description: "",
    body: "",
    type: "",
});

const opts = [
    { label: "Filme", value: 1 },
    { label: "Serie", value: 2 },
];

const submit = () => {
    form.post(route("contents.store"), {
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Criar Anúncio" />

    <AutheticatedLayout>
        <template #header>
            <h2
                class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
            >
                Criar Conteúdo
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="px-4 sm:px-6 lg:px-8">
                            <form @submit.prevent="submit" novalidate>
                                <div>
                                    <InputLabel for="title" value="Título" />

                                    <TextInput
                                        id="title"
                                        type="text"
                                        class="mt-1 block w-full p-2"
                                        v-model="form.title"
                                        required
                                        autofocus
                                    />

                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.title"
                                    />
                                </div>

                                <div class="mt-4">
                                    <InputLabel
                                        for="description"
                                        value="Descrição"
                                    />

                                    <TextInput
                                        id="description"
                                        type="text"
                                        class="mt-1 block w-full p-2"
                                        v-model="form.description"
                                        required
                                        autofocus
                                        autocomplete="username"
                                    />

                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.description"
                                    />
                                </div>

                                <div class="mt-4">
                                    <InputLabel for="body" value="Conteúdo" />

                                    <TextArea
                                        id="body"
                                        class="mt-1 block w-full p-2"
                                        v-model="form.body"
                                    />

                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.body"
                                    />
                                </div>

                                <div class="mt-4">
                                    <InputLabel for="type" value="Status" />

                                    <Select
                                        id="type"
                                        :payloadOptions="opts"
                                        class="mt-1 block w-full p-2"
                                        v-model="form.type"
                                    />

                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.type"
                                    />
                                </div>

                                <div class="mt-8">
                                    <PrimaryButton
                                        :class="{
                                            'opacity-25': form.processing,
                                        }"
                                        :disabled="form.processing"
                                    >
                                        Criar
                                    </PrimaryButton>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AutheticatedLayout>
</template>

<style scoped></style>
