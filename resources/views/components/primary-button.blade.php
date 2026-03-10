<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-brand-btn border border-transparent rounded-md font-semibold text-sm text-white hover:bg-brand-btn-hover focus:bg-brand-btn-hover active:bg-brand-btn-hover focus:outline-none focus:ring-2 focus:ring-brand-btn focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
