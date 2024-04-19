<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import Textarea from "@/Components/TextArea.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputError from "@/Components/InputError.vue";

import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    upload: Object,
    content: Number,
});

const form = useForm({
    name: props.upload.name,
    description: "",
});

const emit = defineEmits();
</script>

<template>
    <div class="w-full overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="flex space-x-6">
                <div class="max-w-[350px] w-full space-y-3">
                    <div class="w-full h-[180px]" v-if="upload.thumb">
                        <img
                            :src="`/storage/${upload.thumb}`"
                            alt=""
                            class="w-full h-[180px] p-1 bg-white shadow rounded"
                        />
                    </div>
                    <div class="space-y-1" v-if="upload.encoding">
                        <div
                            class="bg-gray-100 shadow-inner h-3 rounded overflow-hidden"
                        >
                            <div
                                class="bg-green-500 h-full"
                                :style="{
                                    width: `${upload.encodingProgress}%`,
                                }"
                            ></div>
                        </div>
                        <div class="text-sm text-white font-bold">
                            Convertendo vídeo
                        </div>
                    </div>

                    <div class="space-y-1" v-if="upload.uploading">
                        <div
                            class="bg-gray-100 shadow-inner h-3 rounded overflow-hidden"
                        >
                            <div
                                class="bg-blue-500 h-full"
                                :style="{
                                    width: `${upload.uploadProgress}%`,
                                }"
                            ></div>
                        </div>
                        <div class="text-sm text-white font-bold">
                            Enviando Vídeo
                        </div>
                    </div>

                    <div
                        class="flex items-center space-x-3"
                        v-if="upload.uploading"
                    >
                        <button
                            class="text-blue-500 text-sm font-medium"
                            @click="emit('pause', upload.id)"
                            v-if="!upload.file.paused"
                        >
                            Pausar
                        </button>
                        <button
                            class="text-blue-500 text-sm font-medium"
                            @click="emit('resume', upload.id)"
                            v-if="upload.file.paused"
                        >
                            Continuar
                        </button>

                        <button
                            class="text-blue-500 text-sm font-medium"
                            @click="emit('cancel', upload.id)"
                        >
                            Cancelar envio
                        </button>
                    </div>
                </div>

                <form
                    class="flex-grow space-y-6"
                    v-on:submit.prevent="
                        form.patch(
                            route('videos.update', {
                                content: content,
                                video: upload.id,
                            }),
                            { preserveScroll: true, preserveState: true }
                        )
                    "
                >
                    <div>
                        <InputLabel for="name" value="Video" />
                        <TextInput
                            id="name"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.name"
                        />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <div>
                        <InputLabel for="description" value="Description" />
                        <Textarea
                            id="description"
                            class="mt-1 block w-full"
                            v-model="form.description"
                        />
                        <InputError
                            class="mt-2"
                            :message="form.errors.description"
                        />
                    </div>

                    <div class="flex items-center space-x-3">
                        <PrimaryButton
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                        >
                            Atualizar
                        </PrimaryButton>
                        <div
                            class="text-sm text-white font-bold"
                            v-if="form.recentlySuccessful"
                        >
                            Video Atualizado
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
