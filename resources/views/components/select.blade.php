@props([
    'options' => [], // Assuming $relationshipOptions is predefined
    'selectedValues' => [] // Default to an empty array for no selection
])

<select
        {{ $attributes->class([
            'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-1 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
        ]) }}
>
    @foreach ($options as $value => $label)
        <option value="{{ $value }}" @if(in_array($value, $selectedValues)) selected @endif>{{ $label }}</option>
    @endforeach
</select>
