{{--
    Modal de confirmación reutilizable (Alpine).
    Se dispara desde cualquier botón con:
      x-data
      x-on:click="$dispatch('confirm-delete', { form: $root, message: '...' })"
--}}
<div
    x-data="{
        open: false,
        message: '',
        targetForm: null,
        show(detail) {
            this.message = (detail && detail.message) || 'Esta acción no se puede deshacer.';
            this.targetForm = detail ? detail.form : null;
            this.open = true;
            this.$nextTick(() => this.$refs.acceptBtn && this.$refs.acceptBtn.focus());
        },
        accept() {
            const f = this.targetForm;
            this.open = false;
            if (f) f.submit();
        }
    }"
    x-on:confirm-delete.window="show($event.detail)"
    x-on:keydown.escape.window="open = false"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-[100] flex items-center justify-center p-4"
>
    <div class="absolute inset-0 bg-black/50" x-on:click="open = false"></div>

    <div
        x-show="open"
        x-transition.opacity
        role="dialog"
        aria-modal="true"
        aria-labelledby="confirm-modal-title"
        class="relative w-full max-w-sm rounded-lg border border-gray-200 bg-white p-6 text-gray-700 shadow-lg"
    >
        <div class="flex items-start gap-3">
            <span class="grid h-10 w-10 shrink-0 place-items-center rounded-full bg-red-50 text-red-600">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v4m0 4h.01M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z" />
                </svg>
            </span>
            <div>
                <h3 id="confirm-modal-title" class="text-base font-semibold text-gray-900">
                    ¿Estás seguro de que querés borrar?
                </h3>
                <p class="mt-1 text-sm text-gray-500" x-text="message"></p>
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-2">
            <button type="button" x-on:click="open = false"
                class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 transition hover:bg-gray-100">
                Cancelar
            </button>
            <button type="button" x-ref="acceptBtn" x-on:click="accept()"
                class="rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-red-700">
                Sí, borrar
            </button>
        </div>
    </div>
</div>
