@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border border-solid border-gray-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 shadow h-7 px-2']) !!}>
