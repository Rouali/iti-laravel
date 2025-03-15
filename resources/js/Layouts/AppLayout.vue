<template>
    <div>
        <!-- Navbar -->
        <nav class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <a class="text-lg font-semibold text-gray-900" href="/posts">ITI Blog Post</a>
                        </div>
                        <div class="ml-6 flex items-center space-x-4">
                            <a class="px-3 py-2 text-sm font-medium text-gray-900 border-b-2 border-blue-500"
                                href="/posts">All Posts</a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Flash Messages -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <slot />
        </div>
    </div>
</template>
