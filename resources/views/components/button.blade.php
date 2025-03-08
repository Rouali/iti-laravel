@props(['href' => '#', 'color' => 'blue'])

@php
    $colors = [
        'green' => 'bg-green-500 hover:bg-green-600',
        'blue' => 'bg-blue-500 hover:bg-blue-600',
        'yellow' => 'bg-yellow-500 hover:bg-yellow-600',
        'red' => 'bg-red-500 hover:bg-red-600',
    ];
@endphp

<a href="{{ $href }}" class="px-4 py-2 text-white font-semibold rounded-lg {{ $colors[$color] ?? 'bg-gray-500' }}">
    {{ $slot }}
</a>
