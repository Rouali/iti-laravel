<script setup>
import { useForm } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref } from 'vue';

const props = defineProps({
  post: Object,
  users: Array,
});

const form = useForm({
  title: props.post.title,
  description: props.post.description,
  user_id: props.post.user_id,
  image: null, // Add this line for image upload
  _method: 'PUT', // Add this for method spoofing
});

const imagePreview = ref(props.post.image_url);

const handleFileChange = (event) => {
  const file = event.target.files[0];
  if (file) {
    form.image = file;
    imagePreview.value = URL.createObjectURL(file);
  }
};

const submit = () => {
  form.post(route("posts.update", props.post.id), {
    forceFormData: true,
    onSuccess: () => {
      // Success handling
    },
  });
};
</script>

<template>
  <AppLayout title="Edit Post">
    <div class="max-w-3xl mx-auto">
      <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
          <h2 class="text-xl font-semibold text-gray-800">Edit Post</h2>
        </div>

        <div class="px-6 py-4">
          <form @submit.prevent="submit" enctype="multipart/form-data">
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

            <!-- Image Upload -->
            <div class="mt-4">
              <label for="image" class="block text-sm font-medium text-gray-700">Post Image</label>
              <input 
                type="file" 
                id="image" 
                @change="handleFileChange"
                accept="image/*"
                class="mt-1 p-2 w-full border rounded"
              />
              
              <!-- Image Preview -->
              <div v-if="imagePreview" class="mt-2">
                <img :src="imagePreview" alt="Post Image Preview" class="max-w-xs rounded-md" />
              </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-center">
              <button 
                type="submit" 
                class="mt-4 bg-blue-500 text-white p-2 w-full rounded-lg"
                :disabled="form.processing"
              >
                Update Post
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

