import './bootstrap';

import Alpine from 'alpinejs';

// Alpine se cargaba desde unpkg.com en cada request del panel. Ahora entra al
// bundle: una dependencia externa menos en el camino crítico y version fija.
window.Alpine = Alpine;
Alpine.start();
