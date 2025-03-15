<script setup>
import { useForm } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";

const props = defineProps({
  users: Array,
});

const form = useForm({
  title: "",
  description: "",
  user_id: "",
});

const submit = () => {
  form.post(route("posts.store"), {
    onSuccess: () => form.reset(), // Reset form after successful submission
  });
};
</script>

<template>
  <AppLayout title="Create Post">
    <div class="max-w-3xl mx-auto">
      <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
          <h2 class="text-xl font-semibold text-gray-800">Create New Post</h2>
        </div>

        <div class="px-6 py-4">
          <form @submit.prevent="submit">
            <!-- Title -->
            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
            <input 
              type="text" 
              id="title" 
              v-model="form.title" 
              required 
              class="mt-1 p-2 w-full border rounded"
            />

            <!-- Description -->
            <label for="description" class="block mt-2 text-sm font-medium text-gray-700">Description</label>
            <textarea 
              id="description" 
              v-model="form.description" 
              required 
              class="mt-1 p-2 w-full border rounded"
            ></textarea>

            <!-- Select User -->
            <label for="user_id" class="block mt-2 text-sm font-medium text-gray-700">Select User</label>
            <select 
              id="user_id" 
              v-model="form.user_id" 
              required 
              class="mt-1 p-2 w-full border rounded"
            >
              <option value="">Select a User</option>
              <option v-for="user in props.users" :key="user.id" :value="user.id">
                {{ user.name }}
              </option>
            </select>

            <!-- Submit Button -->
            <div class="flex justify-center">
              <button 
                type="submit" 
                class="mt-4 bg-green-500 text-white p-2 w-full rounded-lg"
                :disabled="form.processing"
              >
                Create Post
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
