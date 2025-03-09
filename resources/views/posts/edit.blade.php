<x-layout>
    <x-slot:title>Edit Post</x-slot>
        <!-- Form Container -->
            

        <form onsubmit="console.log('Form submitted!')" action="{{ route('posts.update', $post->id) }}" method="POST">
            @csrf
            @method('PUT')
        
        
            <!-- Title Input -->
            <div class="mb-4">
                <label for="title" class="block text-gray-700 font-medium">Title</label>
                <input type="text" name="title" id="title" 
                    value="{{ old('title', $post->title) }}" 
                    class="w-full p-2 border border-gray-300 rounded-lg mt-1">
            </div>
        
            <!-- Description Input -->
            <div class="mb-4">
                <label for="description" class="block text-gray-700 font-medium">Description</label>
                <textarea name="description" id="description" rows="4" 
                    class="w-full p-2 border border-gray-300 rounded-lg mt-1">{{ old('description', $post->description) }}</textarea>
            </div>
        
           <!-- Post Creator Dropdown -->
            <div class="mb-4">
                <label for="user_id" class="block text-gray-700 font-medium">Post Creator</label>
                <select name="user_id" id="user_id" 
                    class="w-full p-2 border border-gray-300 rounded-lg mt-1">
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ $post->user_id == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

        
            <!-- Submit Button -->
            <button type="submit" 
            class="inline-block px-4 py-2 text-white bg-green-600 rounded hover:bg-green-700">
            Update
        </button>
        </form>     
            
        
</x-layout>