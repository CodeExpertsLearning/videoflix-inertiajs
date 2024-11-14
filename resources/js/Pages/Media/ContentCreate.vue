<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Select from "@/Components/Select.vue";
import TextArea from "@/Components/TextArea.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";

import AutheticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { ref } from "vue";

const form = useForm({
    title: "",
    description: "",
    body: "",
    type: "",
    photo: ""
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

const filesFront = ref([]);
const isDragged = ref(false);

const resetFilesProp = () => {
    form.photo = [];
    filesFront.value = [];
};

const mainHandleImages = (target) => {
    let images = target;

    form.photo = images.length ? images[0] : [];

    mountFilesFront(images);
};

const imagesHandle = (e) => {
    resetFilesProp();
    mainHandleImages(e.target.files);
};

const handleDragEve = (e) => {
    resetFilesProp();
    mainHandleImages(e.dataTransfer.files);
    isDragged.value = false;
};

const mountFilesFront = (images) => {
    Array.from(images).forEach((image) => {
        const reader = new FileReader();
        reader.readAsDataURL(image);

        reader.onload = (e) => {
            filesFront.value.push(e.target.result);
        };
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

                                <div class="mt-4">
                                    <div class="w-full py-10 text-left">
                                        <h2 class="font-bold">Foto de Capa</h2>
                                    </div>
                                    <div class="flex justify-around">
                                        <div class="w-[48%] flex items-center justify-center ">
                                            <InputLabel
                                                @dragover.prevent="isDragged = true"
                                                @dragleave.prevent="isDragged = false"
                                                @drop.prevent="handleDragEve"
                                                for="photos"
                                                value="Clique ou arraste a foto da capa (1280x720)"
                                                class="w-full h-28 border-2 border-dashed border-gray-500 bg-gray-700 rounded flex items-center justify-center"
                                                :class="{
                                            'bg-gray-900': isDragged,
                                        }"
                                            />

                                            <TextInput
                                                id="photos"
                                                type="file"
                                                class="sr-only"
                                                @change="imagesHandle"
                                            />

                                            <InputError
                                                class="mt-2"
                                                :message="form.errors.photo"
                                            />
                                        </div>

                                        <div
                                            class="w-[48%] border-l border-gray-900 mt-10 pt-10 px-10 flex items-center justify-center"
                                            v-if="filesFront.length"
                                        >
                                            <div
                                                v-for="(img, key) of filesFront"
                                                :key="key"
                                            >
                                                <img
                                                    :src="img"
                                                    class="p-1 bg-white shadow rounded"
                                                />
                                            </div>
                                        </div>
                                    </div>
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
