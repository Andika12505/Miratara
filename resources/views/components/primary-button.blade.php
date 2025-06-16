<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-pink']) }}>
    {{ $slot }}
</button>