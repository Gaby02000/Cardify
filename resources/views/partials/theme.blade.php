{{-- Sistema de temas (oscuro por defecto = colores originales, + modo claro). Incluir en <head>. --}}
<script>
    (function () {
        try {
            var t = localStorage.getItem('cardify-theme');
            document.documentElement.classList.add(t === 'light' ? 'light' : 'dark');
        } catch (e) {
            document.documentElement.classList.add('dark');
        }
    })();
    function __toggleTheme() {
        var el = document.documentElement;
        var toLight = !el.classList.contains('light');
        el.classList.toggle('light', toLight);
        el.classList.toggle('dark', !toLight);
        try { localStorage.setItem('cardify-theme', toLight ? 'light' : 'dark'); } catch (e) {}
    }
</script>
<style>
    :root.dark  { color-scheme: dark; }
    :root.light { color-scheme: light; }

    /* ===== Tema OSCURO (por defecto — paleta original de Cardify) ===== */
    :root, :root.dark {
        --page:        #142234;
        --content:     #050f1b;
        --surface:     #050f1b;
        --surface-2:   #163f47;
        --sidebar:     #163f47;
        --text:        #a4cadc;
        --text-strong: #ffffff;
        --text-muted:  #7c9fad;
        --border:      #274550;
        --border-2:    #37596a;
        --accent:      #1e5d64;
        --accent-hover:#2a7d89;
        --row-hover:   #0e2029;
        --ok-bg:       rgba(55, 227, 155, 0.12);
        --ok-border:   rgba(55, 227, 155, 0.30);
        --ok-text:     #6ee7b7;
        --err-bg:      rgba(255, 84, 112, 0.12);
        --err-border:  rgba(255, 84, 112, 0.30);
        --err-text:    #fca5a5;
    }

    /* ===== Tema CLARO ===== */
    :root.light {
        --page:        #f3f4f6;
        --content:     #f3f4f6;
        --surface:     #ffffff;
        --surface-2:   #f9fafb;
        --sidebar:     #ffffff;
        --text:        #374151;
        --text-strong: #111827;
        --text-muted:  #6b7280;
        --border:      #e5e7eb;
        --border-2:    #d1d5db;
        --accent:      #1f2937;
        --accent-hover:#111827;
        --row-hover:   #f9fafb;
        --ok-bg:       #f0fdf4;
        --ok-border:   #bbf7d0;
        --ok-text:     #15803d;
        --err-bg:      #fef2f2;
        --err-border:  #fecaca;
        --err-text:    #b91c1c;
    }

    html, body { background-color: var(--page); }
    body { color: var(--text); }
    .main-bg { background-color: var(--content) !important; }
    .sidebar-bg { background-color: var(--sidebar) !important; }

    /* Remapeo de las utilidades que usan las vistas -> variables del tema */
    .bg-white            { background-color: var(--surface) !important; }
    .bg-gray-50          { background-color: var(--surface-2) !important; }
    .bg-gray-100         { background-color: var(--surface-2) !important; }
    .bg-gray-800         { background-color: var(--accent) !important; }
    .hover\:bg-gray-900:hover { background-color: var(--accent-hover) !important; }
    .hover\:bg-gray-100:hover { background-color: var(--row-hover) !important; }
    .hover\:bg-gray-50:hover  { background-color: var(--row-hover) !important; }

    .text-gray-900       { color: var(--text-strong) !important; }
    .text-gray-700       { color: var(--text) !important; }
    .text-gray-600       { color: var(--text) !important; }
    .text-gray-500       { color: var(--text-muted) !important; }
    .text-gray-400       { color: var(--text-muted) !important; }
    .text-a4cadc         { color: var(--text) !important; }
    .hover\:text-gray-900:hover { color: var(--text-strong) !important; }
    .hover\:text-gray-700:hover { color: var(--text) !important; }
    .hover-link          { color: var(--text-muted); }
    .hover-link:hover    { color: var(--text-strong); }
    .placeholder-gray-400::placeholder { color: var(--text-muted) !important; }

    .border-gray-100     { border-color: var(--border) !important; }
    .border-gray-200     { border-color: var(--border) !important; }
    .border-gray-300     { border-color: var(--border-2) !important; }
    .focus\:border-gray-400:focus { border-color: var(--border-2) !important; }

    /* Alerts / mensajes flash */
    .bg-green-50  { background-color: var(--ok-bg) !important; }
    .border-green-200 { border-color: var(--ok-border) !important; }
    .text-green-700, .text-green-800 { color: var(--ok-text) !important; }
    .bg-red-50   { background-color: var(--err-bg) !important; }
    .border-red-200 { border-color: var(--err-border) !important; }
    .text-red-700, .text-red-800 { color: var(--err-text) !important; }
    :root.dark  .bg-amber-50 { background-color: rgba(255, 204, 77, 0.12) !important; }
    :root.dark  .border-amber-200 { border-color: rgba(255, 204, 77, 0.30) !important; }
    :root.dark  .text-amber-700 { color: #fcd34d !important; }

    /* Toggle: se ve el ícono del tema al que se cambiaría */
    :root.light .theme-icon-light { display: none; }
    :root.dark  .theme-icon-dark  { display: none; }
</style>
