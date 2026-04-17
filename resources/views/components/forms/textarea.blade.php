@props(['disabled' => false, 'error' => false, 'rows' => 4])

<textarea {{ $disabled ? 'disabled' : '' }} rows="{{ $rows }}" {!! $attributes->merge([
    'class' =>
        'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full transition-colors duration-200 ' .
        ($error ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : ''),
]) !!}>{{ $slot }}</textarea>
