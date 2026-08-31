{{-- Botón de cambio de tema claro/oscuro --}}
<button type="button" onclick="__toggleTheme()" aria-label="Cambiar tema"
        class="w-9 h-9 flex items-center justify-center rounded-md border border-gray-200 text-gray-500 hover:text-gray-900 hover:bg-gray-50 transition">
    {{-- visible en modo claro: pasar a oscuro --}}
    <svg class="theme-icon-dark w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79Z" />
    </svg>
    {{-- visible en modo oscuro: pasar a claro --}}
    <svg class="theme-icon-light w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
        <circle cx="12" cy="12" r="4" />
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 2v2m0 16v2M4.93 4.93l1.41 1.41m11.32 11.32 1.41 1.41M2 12h2m16 0h2M4.93 19.07l1.41-1.41m11.32-11.32 1.41-1.41" />
    </svg>
</button>
