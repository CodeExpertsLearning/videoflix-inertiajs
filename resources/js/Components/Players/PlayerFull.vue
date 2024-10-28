<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

import Hls from "hls.js";
import { onMounted, ref } from "vue";
import { Head, Link, usePage } from "@inertiajs/vue3";

defineProps({ videos: {} });

const video = ref(usePage().props.videos[0]);
const playerEl = ref();
let player = {};
console.log(video.value);
onMounted(() => {
    player = new Hls();
    player.loadSource(
        route("stream_player", {
            code: video.value.code,
            video: video.value.video,
        })
    );
    player.attachMedia(playerEl.value);
});
</script>
<template>
    <AuthenticatedLayout>
        <Head :title="`Assistindo: ${video.name}`" />
        <div class="max-w-7xl mx-auto">
            <div class="w-full block text-white ml-10 mt-14">
                <Link :href="route('dashboard')" class="underline"
                    >Meus Conteúdos</Link
                >
                / <strong>{{ video.name }}</strong>
            </div>
            <div class="max-w-full flex justify-between">
                <video
                    ref="playerEl"
                    id="player"
                    class="mt-8 ml-10 max-w-full h-[780px]"
                    controls
                ></video>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
