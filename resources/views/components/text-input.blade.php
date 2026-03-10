@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 focus:border-brand-navy focus:ring-blue-500 rounded-md shadow-sm']) }}>
