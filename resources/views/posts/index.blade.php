<x-layout>
    <div class="text-center">
        <a href="{{ route('posts.create') }}" 
            class="mt-4 px-4 py-2 bg-green-600 text-white font-medium rounded-md hover:bg-green-700">
            Create Post
        </a>
    </div>

    <!-- Vue App Wrapper -->
    <div id="app"> 
      <!-- Table Component -->
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
                @foreach ($posts as $post)
                <tr class="{{ $post->deleted_at ? 'bg-red-100' : '' }}">
                    <td class="px-4 py-2 font-medium text-gray-900">{{ $post->id }}</td>
                    <td class="px-4 py-2 text-gray-700">{{ $post->title }}</td>
                    <td class="px-4 py-2 text-gray-700">{{ $post->user ? $post->user->name : 'No User Found' }}</td>
                    <td class="px-4 py-2 text-gray-700">{{ $post->formatted_date }}</td>
                    <td class="px-4 py-2 text-gray-700 space-x-2 flex items-center">
                        <!-- Show Button -->
                        <a href="{{ route('posts.show', $post->id) }}" class="bg-blue-500 text-white px-3 py-2 rounded hover:bg-blue-600">
                            Show
                        </a>
                        <!-- View Button (Vue Modal) -->
                        <post-modal :post-id="{{ $post->id }}"></post-modal>
                        <!-- Edit Button -->
                        <a href="{{ route('posts.edit', $post->id) }}" class="bg-yellow-500 text-white px-3 py-2 rounded hover:bg-yellow-600">
                            Edit
                        </a>
                        @if ($post->deleted_at)
                            <!-- Restore Button -->
                            <form action="{{ route('posts.restore', $post->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="bg-green-500 text-white px-3 py-2 rounded hover:bg-green-600">
                                    Restore
                                </button>
                            </form>
                        @else
                            <!-- Delete Button -->
                            <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-3 py-2 rounded hover:bg-red-600">
                                    Delete
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination (Centered & Inside Table Container) -->
    <div class="mt-6 flex flex-col items-center justify-center p-4 rounded-lg border-t border-gray-200">
        <!-- Navigation Buttons -->
        <div class="inline-flex space-x-2">
            {{-- Previous Page --}}
            @if ($posts->onFirstPage())
                <span class="px-3 py-2 text-gray-400 bg-gray-200 rounded-md cursor-not-allowed">
                    Prev
                </span>
            @else
                <a href="{{ $posts->previousPageUrl() }}" class="px-3 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                    Prev
                </a>
            @endif

            {{-- Page Numbers --}}
            @php
                $totalPages = $posts->lastPage();
                $currentPage = $posts->currentPage();
                $pageGroup = ceil($currentPage / 10); // Group pages in sets of 10
                $startPage = ($pageGroup - 1) * 10 + 1;
                $endPage = min($startPage + 9, $totalPages);
            @endphp

            @for ($i = $startPage; $i <= $endPage; $i++)
                @if ($i == $currentPage)
                    <span class="px-3 py-2 font-bold text-white bg-blue-400 rounded-md">
                        {{ $i }}
                    </span>
                @else
                    <a href="{{ $posts->url($i) }}" class="px-3 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                        {{ $i }}
                    </a>
                @endif
            @endfor

            {{-- Next Page --}}
            @if ($posts->hasMorePages())
                <a href="{{ $posts->nextPageUrl() }}" class="px-3 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                    Next
                </a>
            @else
                <span class="px-3 py-2 text-gray-400 bg-gray-200 rounded-md cursor-not-allowed">
                    Next
                </span>
            @endif
        </div>
    </div>
</div> <!-- End Table Component -->

    </div> <!-- End Vue App Wrapper -->

    @vite(['resources/js/app.js'])
</x-layout>
