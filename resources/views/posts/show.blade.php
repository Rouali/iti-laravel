<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <title>Show Post</title>

   @vite(['resources/css/app.css', 'resources/js/app.js'])

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
                       <a class="px-3 py-2 text-sm font-medium text-gray-900 border-b-2 border-blue-500" href="#">All Posts</a>
                   </div>
               </div>
               <div class="flex items-center">
                   <button type="button" class="md:hidden flex items-center justify-center p-2 rounded-md text-gray-400">
                       <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                       </svg>
                   </button>
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
                    <h3 class="text-lg font-medium text-gray-800">Title :- <span class="font-normal">{{$post->title}}</span></h3>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-800">Description :-</h3>
                    <p class="text-gray-600">{{$post->description}}</p>
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
                        <h3 class="text-lg font-medium text-gray-800">Name: <span class="font-normal">{{ $post->user->name ?? 'No User Found' }}</span></h3>
                    </div>
                    <div class="mb-2">
                        <h3 class="text-lg font-medium text-gray-800">Email: <span class="font-normal">{{ $post->user->email ?? 'No Email' }}</span></h3>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium text-gray-800">Created At: <span class="font-normal">{{ $post->full_date }}
                            </span></h3>
                    </div>
                </div>
            </div>
            <div class="mt-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Comments</h2>
            
                <!-- Add Comment Form -->
                <form action="{{ route('comments.store', $post) }}" method="POST" class="mb-6">
                    @csrf
                    <textarea 
                        name="body" 
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
                    @foreach ($post->comments as $comment)
                        <div class="p-4 bg-white rounded-lg shadow-md">
                            <p class="text-gray-700">{{ $comment->body }}</p>
            
                            <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="mt-2">
                                @csrf
                                @method('DELETE')
                                <button 
                                    type="submit" 
                                    class="text-sm text-red-600 hover:underline"
                                >
                                    Delete
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
           <!-- Back Button -->
           <div class="flex justify-end">
            <a href="{{ route('posts.index') }}" class="px-4 py-2 bg-gray-600 text-white font-medium rounded hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                Back to All Posts
            </a>
           </div>
       </div>
   </div>
</body>
</html>