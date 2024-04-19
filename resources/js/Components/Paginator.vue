<script setup>
import NavLink from "@/Components/NavLink.vue";
import { usePage } from "@inertiajs/vue3";

defineProps({
    meta: {},
});
</script>

<template>
    <div
        id="pagination"
        class="flex justify-end py-2 pr-10 bg-gray-50 border-t border-gray-200"
    >
        <NavLink
            :href="link.url"
            v-for="link in meta.links"
            v-show="link.url"
            :key="link.label"
            class="!py-2 !px-4 rounded border border-gray-300 ml-4 hover:border-gray-600 hover:bg-gray-900 hover:text-white transition ease-in-out duration-300"
            :class="{
                'border-gray-600 bg-gray-900 text-white': link.active,
            }"
            >{{ link.label }}</NavLink
        >
    </div>
</template>

<style scoped></style>
