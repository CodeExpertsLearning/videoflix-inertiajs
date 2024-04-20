<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

import Hls from "hls.js";
import { usePage, Link, Head } from "@inertiajs/vue3";
import { onMounted, reactive, ref } from "vue";

defineProps({ videos: {} });

let video = reactive(usePage().props.videos[0]);
let player = "";
const playerEl = ref(null);
const titleTab = ref(video.name);

onMounted(() => {
    player = new Hls();
    player.loadSource(
        route("stream_player", { code: video.code, video: video.video })
    );
    player.attachMedia(playerEl.value);
});

const playVideo = (v) => {
    video = v;
    titleTab.value = v.name;

    player.loadSource(route("stream_player", { code: v.code, video: v.video }));
};
</script>
<template>
    <AuthenticatedLayout>
        <Head :title="`Assistindo: ${titleTab}`" />
        <div class="max-w-7xl mx-auto">
            <div class="w-full block text-white ml-10 mt-14">
                <Link :href="route('dashboard')" class="underline"
                    >Meus Conteúdos</Link
                >
                &raquo; <strong>{{ titleTab }}</strong>
            </div>
            <div class="w-full flex justify-between">
                <video
                    ref="playerEl"
                    id="player"
                    class="mt-14 ml-10 h-[480px]"
                    controls
                ></video>

                <div
                    class="w-80 top-0 right-0 bg-gray-800 border-l border-gray-900 rounded-xl shadow-xl"
                >
                    <ul>
                        <li
                            class="block text-white"
                            v-for="(v, index) of videos"
                            :key="v.id"
                        >
                            <a
                                href="#"
                                @click.prevent="playVideo(v)"
                                class="linkVideo block px-4 py-4 border-b border-gray-900 hover:bg-black hover:text-white transition duration-300 ease-in-out"
                                :class="{
                                    'bg-black': video.id == v.id,
                                    'rounded-t-xl': index == 0,
                                }"
                            >
                                {{ v.name }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
