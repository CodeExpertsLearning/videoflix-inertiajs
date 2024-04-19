<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import { Head, usePage, router } from "@inertiajs/vue3";
import AutheticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import TextInputFile from "@/Components/TextInputFile.vue";
import ListUpload from "@/Components/ListUpload.vue";

import { ref } from "vue";
import { createUpload } from "@mux/upchunk";

defineProps({ content: {} });

const uploads = ref([]);
const isDragged = ref(false);

const getVideoById = (id) => {
    return uploads.value.find((video) => video.id == id);
};

const cancelUpload = (videoId) => {
    getVideoById(videoId).file.abort();

    router.delete(route("videos.destroy", videoId), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            uploads.value = uploads.value.filter(
                (video) => video.id !== videoId
            );
        },
    });
};

const pauseUpload = (videoId) => {
    getVideoById(videoId).file.pause();
};

const resumeUpload = (videoId) => {
    getVideoById(videoId).file.resume();
};

const initChunkUpload = (file, params) => {
    const upload = createUpload({
        endpoint: route("videos.upload.process", params),
        headers: {
            "X-CSRF-TOKEN": usePage().props.csrf_token,
        },
        method: "post",
        file: file,
        chunkSize: 20 * 1024,
    });

    upload.on("progress", (e) => {
        getVideoById(params.video).uploadProgress = e.detail;
    });

    upload.on("success", (e) => {
        getVideoById(params.video).uploading = false;
    });

    return upload;
};

const mainHandleVideos = (videos) => {
    const contentData = usePage().props.content;

    Array.from(videos).forEach((video) => {
        axios
            .post(route("videos.store", contentData), { name: video.name })
            .then((response) => {
                uploads.value.unshift({
                    id: response.data.id,
                    name: video.name,
                    file: initChunkUpload(video, {
                        content: contentData,
                        video: response.data.id,
                    }),
                    uploading: true,
                    uploadProgress: 0,
                    encoding: false,
                    encodingProgress: 0,
                    thumb: null,
                });
            });
    });
};
</script>

<template>
    <Head title="Upload Arquivos de Vídeo" />

    <AutheticatedLayout>
        <template #header>
            <h2
                class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
            >
                Upload Arquivos de Vídeo
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="px-4 sm:px-6 lg:px-8">
                            <form>
                                <div class="mt-4">
                                    <InputLabel
                                        @dragover.prevent="isDragged = true"
                                        @dragleave.prevent="isDragged = false"
                                        @drop.prevent="
                                            mainHandleVideos(
                                                $event.dataTransfer.files
                                            )
                                        "
                                        for="photos"
                                        value="Clique ou arraste seus vídeos para realizar o upload"
                                        class="h-28 border-2 border-dashed border-gray-500 bg-gray-700 rounded flex items-center justify-center"
                                        :class="{
                                            'bg-gray-900': isDragged,
                                        }"
                                    />

                                    <TextInputFile
                                        id="photos"
                                        type="file"
                                        class="sr-only"
                                        @change="
                                            mainHandleVideos(
                                                $event.target.files
                                            )
                                        "
                                        multiple
                                    />
                                </div>
                            </form>
                        </div>
                    </div>

                    <ListUpload
                        v-for="upload of uploads"
                        :key="upload.id"
                        :upload="upload"
                        :content="content.id"
                        @cancel="cancelUpload"
                        @pause="pauseUpload"
                        @resume="resumeUpload"
                    />
                </div>
            </div>
        </div>
    </AutheticatedLayout>
</template>
