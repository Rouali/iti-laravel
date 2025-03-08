<x-layout>
    <x-slot:title>Edit Post</x-slot>
        <!-- Form Container -->
            

            <form action="{{route('posts.update',1)}}" method="POST">
                @csrf
                @method('PUT')
                <!-- Title Input -->
                <div class="mb-4">
                    <label for="title" class="block text-gray-700 font-medium">Title</label>
                    <input type="text" name="title" id="title" 
                        class="w-full p-2 border border-gray-300 rounded-lg mt-1">
                </div>

                <!-- Description Input -->
                <div class="mb-4">
                    <label for="description" class="block text-gray-700 font-medium">Description</label>
                    <textarea name="description" id="description" rows="4" 
                        class="w-full p-2 border border-gray-300 rounded-lg mt-1"></textarea>
                </div>

                <!-- Post Creator Dropdown -->
                <div class="mb-4">
                    <label for="creator" class="block text-gray-700 font-medium">Post Creator</label>
                    <select name="creator" id="creator" 
                        class="w-full p-2 border border-gray-300 rounded-lg mt-1">
                        <option value="Rawan" >Rawan</option>
                        <option value="Rawan" >Rawan</option>
                        <option value="Ali " >Ali</option>
                    </select>
                </div>

                <!-- Submit Button -->
                <x-button type="submit" class="w-full">
                    Update
                </x-button>
            </form>
</x-layout>