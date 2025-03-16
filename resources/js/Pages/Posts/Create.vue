<script setup>
import { ref, defineProps } from "vue";
import { useField, useForm as useVeeForm } from "vee-validate";
import * as yup from "yup";
import { router } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import axios from "axios";

const props = defineProps({
  users: Array,
  csrf_token: String, 
});

// Validation Schema
const schema = yup.object({
  title: yup.string().min(3, "Title must be at least 3 characters").required("Title is required"),
  description: yup.string().min(10, "Description must be at least 10 characters").required("Description is required"),
  user_id: yup.string().required("Please select a user"),
  image: yup.mixed().nullable().test('fileSize', 'The file is too large', (value) => !value || value.size <= 2048 * 1024).test('fileType', 'The file must be an image', (value) => !value || ['image/jpeg', 'image/png'].includes(value.type)),
});

const { handleSubmit, errors, resetForm } = useVeeForm({ validationSchema: schema });
const { value: title, errorMessage: titleError } = useField("title");
const { value: description, errorMessage: descriptionError } = useField("description");
const { value: user_id, errorMessage: user_idError } = useField("user_id");
const { value: image, errorMessage: imageError } = useField("image");

const imageUrl = ref(null);

const handleFileChange = (event) => {
  const file = event.target.files[0];
  if (file) {
    image.value = file;
    imageUrl.value = URL.createObjectURL(file);
  }
};

const submit = handleSubmit(async (values) => {
  try {
    const formData = new FormData();
    formData.append('title', values.title);
    formData.append('description', values.description);
    formData.append('user_id', values.user_id);

    if (image.value) {
      formData.append('image', image.value);
    }

    const headers = {
      'Content-Type': 'multipart/form-data',
      'X-CSRF-TOKEN': props.csrf_token
    };
    
    await axios.post('/posts', formData, { headers });
    router.visit('/posts');
  } catch (error) {
    console.error("Error submitting the form:", error);
  }
});
</script>

<template>
  <AppLayout>
    <div class="max-w-3xl mx-auto">
      <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
          <h2 class="text-xl font-semibold text-gray-800">Create New Post</h2>
        </div>

        <div class="px-6 py-4">
          <form @submit.prevent="submit" enctype="multipart/form-data">
            <!-- Title Field -->
            <div class="mb-4">
              <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
              <input 
                type="text" 
                id="title" 
                v-model="title" 
                class="mt-1 p-2 w-full border rounded"
                :class="{ 'border-red-500': titleError }"
                placeholder="Enter post title"
              />
              <p v-if="titleError" class="text-red-500 text-sm mt-1">{{ titleError }}</p>
            </div>

            <!-- Description Field -->
            <div class="mb-4">
              <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
              <textarea 
                id="description" 
                v-model="description" 
                class="mt-1 p-2 w-full border rounded"
                :class="{ 'border-red-500': descriptionError }"
                placeholder="Enter post description"
              ></textarea>
              <p v-if="descriptionError" class="text-red-500 text-sm mt-1">{{ descriptionError }}</p>
            </div>

            <!-- Select User -->
            <div class="mb-4">
              <label for="user_id" class="block text-sm font-medium text-gray-700">Select User</label>
              <select 
                id="user_id" 
                v-model="user_id" 
                class="mt-1 p-2 w-full border rounded"
                :class="{ 'border-red-500': user_idError }"
              >
                <option value="">Select a User</option>
                <option v-for="user in props.users" :key="user.id" :value="user.id">
                  {{ user.name }}
                </option>
              </select>
              <p v-if="user_idError" class="text-red-500 text-sm mt-1">{{ user_idError }}</p>
            </div>

            <!-- Image Field -->
            <div class="mb-4">
              <label for="image" class="block text-sm font-medium text-gray-700">Post Image</label>
              <input 
                type="file" 
                id="image" 
                @change="handleFileChange"
                accept="image/*"
                class="mt-1 p-2 w-full border rounded"
                :class="{ 'border-red-500': imageError }"
              />
              <p v-if="imageError" class="text-red-500 text-sm mt-1">{{ imageError }}</p>

              <!-- Image Preview -->
              <div v-if="imageUrl" class="mt-2">
                <img :src="imageUrl" alt="Post Image" class="max-w-xs"/>
              </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-center">
              <button 
                type="submit" 
                class="mt-4 bg-green-500 text-white p-2 w-full rounded-lg"
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

<style scoped>
input, select, textarea {
  transition: border-color 0.3s ease;
}

input:focus, select:focus, textarea:focus {
  border-color: #4CAF50;
}

button:disabled {
  background-color: #BDBDBD;
  cursor: not-allowed;
}
</style>

