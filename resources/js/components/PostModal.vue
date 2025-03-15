<template>
  <div v-if="isOpen" class="fixed inset-0 flex items-center justify-center z-50">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm" @click="close"></div>
    
    <!-- Modal Content -->
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4 z-10 overflow-hidden">
      <!-- Header -->
      <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
        <h2 class="text-xl font-semibold text-gray-800">Post Details</h2>
        <button @click="close" class="text-gray-500 hover:text-gray-700 text-lg">&times;</button>
      </div>
      
      <!-- Loading State -->
      <div v-if="loading" class="p-6 flex justify-center items-center">
        <svg class="animate-spin h-8 w-8 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
      </div>
      
      <!-- Post Content -->
      <div v-else-if="post" class="p-6">
        <!-- Post Info -->
        <div class="mb-4">
          <h3 class="text-lg font-medium text-gray-800 mb-2">Post Information</h3>
          <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
            <div class="mb-2">
              <span class="font-semibold">Title:</span> {{ post.title }}
            </div>
            <div>
              <span class="font-semibold">Description:</span>
              <p class="mt-1 text-gray-600">{{ post.description }}</p>
            </div>
          </div>
        </div>
        
        <!-- User Info -->
        <div>
          <h3 class="text-lg font-medium text-gray-800 mb-2">User Information</h3>
          <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
            <div class="mb-2">
              <span class="font-semibold">Name:</span> {{ post.user?.name || 'No User Found' }}
            </div>
            <div>
              <span class="font-semibold">Email:</span> {{ post.user?.email || 'No Email' }}
            </div>
          </div>
        </div>
      </div>

      <!-- Error State -->
      <div v-else class="p-6 text-center text-red-500">
        <p>{{ error || "Failed to load post data" }}</p>
        <button 
          @click="fetchPostData(postId)" 
          class="mt-4 px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition"
        >
          Retry
        </button>
      </div>
      
      <!-- Footer -->
      <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end">
        <button 
          @click="close" 
          class="px-4 py-2 bg-gray-600 text-white font-medium rounded hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
        >
          Close
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import axios from 'axios';

const props = defineProps({
  postId: {
    type: Number,
    required: false,
    default: null
  },
  isOpen: {
    type: Boolean,
    default: false
  }
});

const emit = defineEmits(['close']);

const post = ref(null);
const loading = ref(false);
const error = ref(null);

// Watch only when modal opens with a valid postId
watch(() => props.isOpen, async (newVal) => {
  if (newVal && props.postId) {
    await fetchPostData(props.postId);
  }
});

// Fetch post data
async function fetchPostData(postId) {
  if (!postId) return;

  loading.value = true;
  error.value = null;
  post.value = null; // Reset to prevent stale data

  try {
    const response = await axios.get(`/api/posts/${postId}`);
    post.value = response.data;
  } catch (err) {
    console.error('Error fetching post data:', err);
    error.value = err.response?.data?.message || 'Failed to load post data';
  } finally {
    loading.value = false;
  }
}

// Close modal and reset state
function close() {
  post.value = null;
  error.value = null;
  emit('close');
}
</script>
