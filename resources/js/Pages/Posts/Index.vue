<template>
  <AppLayout>
    <!-- Create Post Button -->
    <div class="text-center">
        <Link 
        href="/posts/create"
      class="mt-4 px-4 py-2 bg-green-600 text-white font-medium rounded-md hover:bg-green-700 transition"
    >
      Create Post
    </Link>
    </div>

    <!-- Posts Table -->
    <div class="mt-6 rounded-lg border border-gray-200 shadow-md bg-white p-4">
      <div class="overflow-x-auto rounded-t-lg">
        <table class="min-w-full divide-y-2 divide-gray-200 bg-white text-sm">
          <thead class="text-left bg-gray-100">
            <tr>
              <th class="px-4 py-2 font-medium text-gray-900">#</th>
              <th class="px-4 py-2 font-medium text-gray-900">Title</th>
              <th class="px-4 py-2 font-medium text-gray-900">Posted By</th>
              <th class="px-4 py-2 font-medium text-gray-900">Created At</th>
              <th class="px-4 py-2 font-medium text-gray-900">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            <tr v-for="post in posts.data" :key="post.id" :class="post.deleted_at ? 'bg-red-100' : ''">
              <td class="px-4 py-2 font-medium text-gray-900">{{ post.id }}</td>
              <td class="px-4 py-2 text-gray-700">{{ post.title }}</td>
              <td class="px-4 py-2 text-gray-700">{{ post.user ? post.user.name : 'No User Found' }}</td>
              <td class="px-4 py-2 text-gray-700">{{ post.formatted_date }}</td>
              <td class="px-4 py-2 text-gray-700 space-x-2 flex items-center">
                <!-- Show Button -->
                <Link 
                  :href="`/posts/${post.id}`" 
                  class="bg-blue-500 text-white px-3 py-2 rounded hover:bg-blue-600 transition"
                >
                  Show
                </Link>
                
                <!-- View Button (Ajax Modal) -->
                <ViewPostButton :postId="post.id" />
                
                <!-- Edit Button -->
                <Link 
                  :href="`/posts/${post.id}/edit`" 
                  class="bg-yellow-500 text-white px-3 py-2 rounded hover:bg-yellow-600 transition"
                >
                  Edit
                </Link>
                
                <!-- Restore/Delete Button -->
                <button 
                  v-if="post.deleted_at" 
                  @click="restorePost(post.id)" 
                  class="bg-green-500 text-white px-3 py-2 rounded hover:bg-green-600 transition"
                >
                  Restore
                </button>
                <button 
                  v-else 
                  @click="deletePost(post.id)" 
                  class="bg-red-500 text-white px-3 py-2 rounded hover:bg-red-600 transition"
                >
                  Delete
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
     <!-- Pagination -->
<div class="mt-6 flex justify-center p-4 border-t border-gray-200">
  <div class="flex items-center space-x-1 overflow-x-auto">
    
    <!-- Previous Page -->
    <Link 
      v-if="posts.prev_page_url" 
      :href="posts.prev_page_url" 
      class="px-4 py-2 text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition"
    >
      Prev
    </Link>
    <span 
      v-else 
      class="px-4 py-2 text-gray-400 bg-gray-100 rounded-md cursor-not-allowed"
    >
      Prev
    </span>

    <!-- Page Numbers with Ellipsis -->
    <template v-for="i in visiblePages" :key="i">
      <span 
        v-if="i === '...'" 
        class="px-3 py-2 text-gray-500"
      >
        ...
      </span>
      <Link 
        v-else 
        :href="`/posts?page=${i}`" 
        :class="[ 
          'px-4 py-2 rounded-md transition',
          i === posts.current_page 
            ? 'font-bold text-white bg-blue-500' 
            : 'text-gray-700 bg-gray-100 hover:bg-gray-200'
        ]"
      >
        {{ i }}
      </Link>
    </template>

    <!-- Next Page -->
    <Link 
      v-if="posts.next_page_url" 
      :href="posts.next_page_url" 
      class="px-4 py-2 text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition"
    >
      Next
    </Link>
    <span 
      v-else 
      class="px-4 py-2 text-gray-400 bg-gray-100 rounded-md cursor-not-allowed"
    >
      Next
    </span>

  </div>
</div>

  </AppLayout>
</template>
<script setup>
import { ref } from "vue";
import { computed } from "vue";
import { Link, router , usePage} from "@inertiajs/vue3";
import axios from "axios";
import AppLayout from "@/Layouts/AppLayout.vue";
import ViewPostButton from "@/Components/ViewPostButton.vue";
import { route } from "ziggy-js";

const props = defineProps({
  posts: Object
});
const page = usePage();


// Function to handle post deletion
const deletePost = async (id) => {
  if (confirm("Are you sure you want to delete this post?")) {
    try {
      await axios.delete(`/posts/${id}`);
      router.reload(); // ✅ More efficient than window.location.reload()
    } catch (error) {
      console.error("Error deleting post:", error);
    }
  }
};

// Function to handle post restoration
const restorePost = async (id) => {
  try {
    await axios.patch(`/posts/${id}/restore`);
    router.reload(); // ✅ More efficient than window.location.reload()
  } catch (error) {
    console.error("Error restoring post:", error);
  }
};
const visiblePages = computed(() => {
  const totalPages = props.posts.last_page;
  const currentPage = props.posts.current_page;
  const maxPagesToShow = 5;

  if (totalPages <= maxPagesToShow) {
    return [...Array(totalPages).keys()].map((i) => i + 1);
  }

  let pages = [1];

  if (currentPage > 3) pages.push("...");

  let start = Math.max(2, currentPage - 1);
  let end = Math.min(totalPages - 1, currentPage + 1);

  for (let i = start; i <= end; i++) {
    pages.push(i);
  }

  if (currentPage < totalPages - 2) pages.push("...");

  pages.push(totalPages);
  return pages;
});

</script>
