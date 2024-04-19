<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import NavLink from "@/Components/NavLink.vue";
import Paginator from "@/Components/Paginator.vue";
import { useForm, Head } from "@inertiajs/vue3";

defineProps({ contents: {} });

const form = useForm({});

const removeContent = (content) => {
    if (!confirm("Deseja mesmo remover este conteúdo?")) return;

    form.delete(route("contents.destroy", content));
};
</script>

<template>
    <Head title="Conteúdos Cadastrados" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
            >
                Media Conteúdos
            </h2>
        </template>

        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="w-full py-4 flex justify-end">
                <NavLink
                    :href="route('contents.create')"
                    class="px-6 !py-3 border border-green-900 bg-green-600 !text-white hover:bg-green-900 transition duration-300 ease-in-out rounded"
                >
                    Criar Conteúdo
                </NavLink>
            </div>

            <div
                class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg mb-10"
            >
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th
                                scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                #
                            </th>
                            <th
                                scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Title
                            </th>
                            <th
                                scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Criado em
                            </th>
                            <th
                                scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Ação
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="content of contents.data" :key="content.id">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ content.id }}
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ content.title }}
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800"
                                >
                                    {{ content.created_at }}
                                </span>
                            </td>

                            <td
                                class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"
                            >
                                <div class="flex">
                                    <NavLink
                                        :href="
                                            route('contents.edit', content.id)
                                        "
                                        class="px-2 py-1 border !border-blue-600 rounded mr-2 !text-blue-900 hover:bg-blue-900 hover:!text-white transition duration-300 ease-in-out"
                                        >Editar</NavLink
                                    >

                                    <button
                                        @click="removeContent(content.id)"
                                        class="px-2 py-1 border border-red-600 rounded mr-2 !text-red-900 hover:bg-red-900 hover:!text-white transition duration-300 ease-in-out"
                                    >
                                        Apagar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <Paginator v-if="contents.data.length > 10" :meta="contents" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
