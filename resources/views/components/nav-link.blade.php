@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-autumn-orange text-sm font-medium leading-5 text-autumn-cream focus:outline-none focus:border-autumn-brown transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-autumn-cream/70 hover:text-autumn-cream hover:border-autumn-brown focus:outline-none focus:text-autumn-cream focus:border-gray-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
