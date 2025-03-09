<x-layout :title="'Create Post'">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Create New Post</h2>
            </div>

            <div class="px-6 py-4">
                <form action="{{ route('posts.store') }}" method="POST">
                    @csrf
                
                    <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                    <input type="text" name="title" id="title" required class="mt-1 p-2 w-full border rounded">
                
                    <label for="description" class="block mt-2 text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" required class="mt-1 p-2 w-full border rounded"></textarea>
                
                    <label for="user_id" class="block mt-2 text-sm font-medium text-gray-700">Select User</label>
                    <select name="user_id" id="user_id" required class="mt-1 p-2 w-full border rounded">
                        <option value="">Select a User</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                
                    <div class="flex justify-center">
                        <button type="submit" class="mt-4 bg-green-500 text-white p-2 w-full rounded-lg">
                            Create Post
                        </button>
                    </div>
                                    
                </form>
                
            </div>
        </div>
    </div>
</x-layout> 