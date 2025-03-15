<template>
  <div>
    <!-- View Button (Styled like Authors) -->
    <button 
      @click="fetchPost"
      :disabled="loading"
      class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition disabled:bg-gray-400"
    >
      {{ loading ? "Loading..." : "View" }}
    </button>

    <!-- Modal -->
    <Teleport to="body">
      <Transition name="fade">
        <div v-if="showModal" class="modal">
          <div class="modal-content">
            <!-- Close Button -->
            <button @click="closeModal" class="close-btn">&times;</button>

            <h2 class="text-xl font-bold">
              {{ loading ? "Loading..." : post?.title || "No Title Available" }}
            </h2>

            <p v-if="loading">Fetching post details...</p>
            <p v-else-if="error" class="text-red-500">{{ error }}</p>
            <div v-else>
              <p>{{ post?.description || "No description available." }}</p>
              <p><strong>Posted by:</strong> {{ post?.user?.name || "Unknown" }}</p>
            </div>

            <button 
              @click="closeModal"
              class="mt-4 px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition"
            >
              Close
            </button>
          </div>
        </div>
      </Transition>
    </Teleport>
  </div>
</template>

<script setup>
import { ref } from "vue";
import axios from "axios";

const props = defineProps({
  postId: {
    type: Number,
    required: true
  }
});

const post = ref(null);
const showModal = ref(false);
const loading = ref(false);
const error = ref(null);

// Fetch post data
const fetchPost = async () => {
  loading.value = true;
  error.value = null;

  try {
    const response = await axios.get(`/api/posts/${props.postId}`, {
      headers: { Accept: "application/json" } // 👈 Forces JSON response
    });

    post.value = response.data;
    showModal.value = true;
  } catch (err) {
    console.error("Error fetching post:", err);
    error.value = "Failed to fetch post data.";
  } finally {
    loading.value = false;
  }
};

// Close Modal
const closeModal = () => {
  showModal.value = false;
};
</script>

<style scoped>
/* Fade Transition */
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter, .fade-leave-to {
  opacity: 0;
}

/* Modal Background */
.modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

/* Modal Content */
.modal-content {
  background: white;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
  max-width: 500px;
  width: 90%;
  text-align: center;
  position: relative;
}

/* Close Button */
.close-btn {
  position: absolute;
  top: 10px;
  right: 15px;
  background: transparent;
  border: none;
  font-size: 24px;
  cursor: pointer;
}
</style>
