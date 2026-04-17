@props(['disabled' => false, 'error' => false])

<select {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
    'class' =>
        'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full bg-white text-gray-700 transition-colors duration-200 cursor-pointer ' .
        ($error ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : ''),
]) !!}>
    {{ $slot }}
</select>
