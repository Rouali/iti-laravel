<template>
  <!DOCTYPE html>
  <html lang="en">
    <head>
      <meta charset="UTF-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <meta http-equiv="X-UA-Compatible" content="ie=edge" />
      <title>Show Post</title>
    </head>

    <body class="bg-gray-50">
      <!-- Navigation -->
      <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between h-16">
            <div class="flex">
              <div class="flex-shrink-0 flex items-center">
                <a class="text-lg font-semibold text-gray-900" href="#">ITI Blog Post</a>
              </div>
              <div class="ml-6 flex items-center space-x-4">
                <a
                  class="px-3 py-2 text-sm font-medium text-gray-900 border-b-2 border-blue-500"
                  href="/posts"
                >
                  All Posts
                </a>
              </div>
            </div>
          </div>
        </div>
      </nav>

      <!-- Container -->
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="max-w-3xl mx-auto space-y-6">
          <!-- Post Info Card -->
          <div class="bg-white rounded border border-gray-200">
            <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
              <h2 class="text-base font-medium text-gray-700">Post Info</h2>
            </div>
            <div class="px-4 py-4">
              <div class="mb-2">
                <h3 class="text-lg font-medium text-gray-800">
                  Title: <span class="font-normal">{{ post.title }}</span>
                </h3>
              </div>
              <div>
                <h3 class="text-lg font-medium text-gray-800">Description:</h3>
                <p class="text-gray-600">{{ post.description }}</p>
              </div>
              <div>
                <h3 class="text-lg font-medium text-gray-800">Slug:</h3>
                <p class="text-gray-600">{{ post.slug }}</p>
              </div>
              <div v-if="post.image_url" class="mt-4">
                <h3 class="text-lg font-medium text-gray-800">Image:</h3>
                <img :src="post.image_url" alt="Post Image" class="mt-2 max-w-full h-auto rounded-lg shadow-md">
              </div>
            </div>
          </div>

          <!-- Post Creator Info Card -->
          <div class="bg-white rounded border border-gray-200">
            <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
              <h2 class="text-base font-medium text-gray-700">Post Creator Info</h2>
            </div>
            <div class="px-4 py-4">
              <div class="mb-2">
                          <h3 class="text-lg font-medium text-gray-800">
                              Name:
                              <span class="font-normal">{{ post.user ? post.user.name : "No User Found" }}</span>
                          </h3>
                          </div>
                          <div class="mb-2">
                          <h3 class="text-lg font-medium text-gray-800">
                              Email:
                              <span class="font-normal">{{ post.user ? post.user.email : "No Email" }}</span>
                          </h3>
                          </div>
              <div>
                <h3 class="text-lg font-medium text-gray-800">
                  Created At:
                  <span class="font-normal">{{ formattedDate(post.created_at) }}</span>
                </h3>
              </div>
            </div>
          </div>

          <!-- Comments Section -->
          <div class="mt-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Comments</h2>

            <!-- Add Comment Form -->
            <form @submit.prevent="addComment" class="mb-6">
              <textarea
                v-model="newComment"
                required
                class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-300"
                placeholder="Write a comment..."
              ></textarea>
              <button
                type="submit"
                class="mt-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
              >
                Add Comment
              </button>
            </form>

            <!-- Comments List -->
            <div class="space-y-4">
              <div v-for="comment in post.comments" :key="comment.id" class="p-4 bg-white rounded-lg shadow-md">
                <p class="text-gray-700">{{ comment.body }}</p>
                <button
                  @click="deleteComment(comment.id)"
                  class="text-sm text-red-600 hover:underline mt-2"
                >
                  Delete
                </button>
              </div>
            </div>
          </div>

          <!-- Back Button -->
          <div class="flex justify-end">
            <button
              @click="goBack"
              class="px-4 py-2 bg-gray-600 text-white font-medium rounded hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
            >
              Back to All Posts
            </button>
          </div>
        </div>
      </div>
    </body>
  </html>
</template>

<script setup>
import { computed, ref } from "vue";
import { usePage, router } from "@inertiajs/vue3";

// Get post data from Inertia props
const page = usePage();
const post = computed(() => page.props.post);

// New comment input
const newComment = ref("");

// Format date function
const formattedDate = (dateString) => {
  if (!dateString) return "Unknown date";
  return new Date(dateString).toLocaleDateString("en-US", {
    year: "numeric",
    month: "long",
    day: "numeric",
  });
};

// Add new comment function
const addComment = () => {
  if (!newComment.value.trim()) return;

  router.post(`/posts/${post.value.id}/comments`, { body: newComment.value }, {
    onSuccess: () => {
      newComment.value = "";
    },
  });
};

// Delete comment function
const deleteComment = (commentId) => {
  router.delete(`/comments/${commentId}`);
};

// Back button function
const goBack = () => {
  router.visit("/posts");
};
</script>

<style scoped>
.container {
  max-width: 800px;
}

.shadow-lg {
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
}
</style>

