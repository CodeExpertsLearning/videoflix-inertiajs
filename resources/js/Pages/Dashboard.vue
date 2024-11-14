<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";

defineProps({ contents: {} });
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
            >
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white dark:bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg"
                >
                    <div
                        class="w-full p-2"
                        v-for="(contentGroup, index) of contents"
                        :key="index"
                    >
                        <div
                            class="w-full border-b border-gray-700 mb-10 py-4 px-2"
                        >
                            <strong class="text-gray-400 text-xl">
                                {{ index == 1 ? "Filmes" : "Series" }}</strong
                            >
                        </div>

                        <div
                            class="w-full md:grid md:grid-cols-3 lg:grid-cols-4 md:gap-0.5 mb-10 px-4"
                        >
                            <div
                                class="w-[280px] h-[380px] mb-16 bg-gray-900 rounded shadow-lg shadow-gray-800 hover:shadow-gray-400 transition duration-300 ease-in-out"
                                v-for="content of contentGroup"
                                :key="content.id"
                            >
                                <img v-if="content.cover"
                                    :src="`/storage/${content.cover}`"
                                    :alt="`Capa do conteúdo: ${content.title}`"
                                    class="block mb-8 rounded-t w-[280px] h-[40%]"
                                />

                                <img v-else
                                    src="/storage/no-photo.jpg"
                                    :alt="`Capa do conteúdo: ${content.title}`"
                                    class="block mb-8 rounded-t w-[280px] h-[40%]"
                                />

                                <div
                                    class="px-4 pb-4 text-white relative h-[60%]"
                                >
                                    <h5 class="font-extrabold text-2xl mb-4">
                                        {{ content.title }}
                                    </h5>

                                    <p class="leading-4 text-xl mb-20">
                                        {{ content.description }}
                                    </p>

                                    <a
                                        :href="route('video.player', content)"
                                        class="mt-8 font-bold text-2xl block w-full text-center px-2 py-4 bg-white text-gray-900 hover:bg-gray-900 hover:text-white hover:border-t hover:border-white rounded-b transition duration-300 ease-in-out absolute bottom-0.5 left-0 right-0"
                                        >Assistir</a
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
