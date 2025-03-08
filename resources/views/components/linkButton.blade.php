@props([
    'variant' => 'default',
    'color' => 'gray'
])

@php
    $baseClasses = 'px-4 py-2 rounded-lg transition';
    
    $variants = [
        'default' => [
            'blue' => 'bg-blue-500 text-white hover:bg-blue-600',
            'green' => 'bg-green-500 text-white hover:bg-green-600',
            'yellow' => 'bg-yellow-500 text-white hover:bg-yellow-600',
            'red' => 'bg-red-500 text-white hover:bg-red-600',
            'gray' => 'bg-white text-gray-500 border border-gray-300 hover:bg-gray-100 hover:text-gray-700',
        ],
        'active' => 'bg-gray-200 text-gray-700 border border-gray-300',
    ];

    $classes = $variant === 'active' 
        ? $variants['active']
        : $variants['default'][$color];
@endphp

<a {{ $attributes->merge(['class' => "{$baseClasses} {$classes}"]) }}>
    {{ $slot }}
</a>