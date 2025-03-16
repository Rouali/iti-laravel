<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';

const redirectToPosts = () => {
    window.location.href = route('posts.index');
};
</script>

<template>
    <div>
        <Head title="Dashboard" />

        <AuthenticatedLayout>
            <template #header>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Dashboard
                </h2>
            </template>

            <div class="py-12">
                <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            You're logged in!
                            <div class="mt-4">
                                <button 
                                    @click="redirectToPosts"
                                    class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600"
                                >
                                    Go to Posts
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    </div>
</template>
