<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Select from "@/Components/Select.vue";
import TextArea from "@/Components/TextArea.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";

import AutheticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm, usePage, router } from "@inertiajs/vue3";
import NavLink from "@/Components/NavLink.vue";
import {onMounted, reactive, ref} from "vue";

defineProps({ content: Object });

onMounted(() => {
    Echo.channel("videos")
        .listen(".App\\Events\\VideoEncodingStart", (e) => {
            const video = getVideoById(e.videoId);

            if (!video) return;

            video.encoding = true;
        })
        .listen(".App\\Events\\VideoEncodingProgress", (e) => {
            const video = getVideoById(e.videoId);

            if (!video) return;

            if (!video.encoding) video.encoding = true;

            video.progress = e.percentage;
        })
        .listen(".App\\Events\\VideoEncodingFinished", (e) => {
            const video = getVideoById(e.videoId);

            if (!video) return;

            video.encoding = false;
        })
        .listen(".App\\Events\\VideoThumbGenerated", (e) => {
            const video = getVideoById(e.videoId);

            if (!video) return;

            video.thumb = e.thumb;
        });
});

const videos = reactive(usePage().props.content.videos);

const getVideoById = (id) => {
    return videos.find((video) => video.id == id);
};

const form = useForm(usePage().props.content);

const opts = [
    { label: "Filme", value: 1 },
    { label: "Serie", value: 2 },
];

const submit = () => {
    let data = {
        _token: usePage().props.csrf_token,
        _method: 'PUT',
        title: form.title,
        description: form.description,
        body: form.body,
        type: form.type,
        photo: form.photo,
    }

    router.post(route("contents.update", form.id), data)
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
    <Head title="Atualizar Conteúdo" />

    <AutheticatedLayout>
        <template #header>
            <h2
                class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
            >
                Atualizar Conteúdo
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="w-full py-4 flex justify-end">
                    <NavLink
                        :href="route('videos.upload', content)"
                        class="px-6 !py-3 border border-green-900 bg-green-600 !text-white hover:bg-green-900 transition duration-300 ease-in-out rounded"
                    >
                        Realizar Upload Vídeo(s)
                    </NavLink>
                </div>
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
                                        <div
                                            v-else
                                            class="w-[48%] border-l border-gray-900 mt-10 pt-10 px-10 flex items-center justify-center"
                                        >
                                            <img
                                                :src="`/storage/${form.cover}`"
                                                class="p-1 bg-white shadow rounded"
                                            />
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
                                        Atualizar
                                    </PrimaryButton>
                                </div>
                            </form>
                        </div>
                        <!-- Componentizar trecho abaixo-->
                        <div
                            v-if="videos.length"
                            class="p-7 mt-10 pt-10 border-t border-gray-500 grid grid-cols-4 gap-3"
                        >
                            <div
                                class="w-[230px]"
                                v-for="video of videos"
                                :key="video.id"
                            >
                                <img
                                    :src="`/storage/${video.thumb}`"
                                    :alt="`Capa vídeo ${video.name}`"
                                    class="p-1 bg-white rounded shadow-xl mb-4"
                                />
                                <h2
                                    class="font-white font-bold text-xl mb-4 text-clip overflow-hidden"
                                >
                                    {{ video.name }}
                                </h2>

                                <div class="space-y-1" v-if="video.encoding">
                                    <div
                                        class="bg-gray-100 shadow-inner h-3 rounded overflow-hidden"
                                    >
                                        <div
                                            class="bg-green-500 h-full"
                                            v-bind:style="{
                                                width: `${video.progress}%`,
                                            }"
                                        ></div>
                                    </div>
                                    <div class="text-sm text-white font-bold">
                                        Convertendo vídeo
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Fim trecho Componentizar -->
                    </div>
                </div>
            </div>
        </div>
    </AutheticatedLayout>
</template>
