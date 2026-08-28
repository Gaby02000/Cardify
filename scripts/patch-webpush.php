<?php

/*
 * Compat PHP 8.5 para minishlink/web-push v10.0.x
 * -------------------------------------------------
 * Encryption::createLocalKeyObject() genera la clave efímera con
 *   openssl_pkey_new(['curve_name' => ..., 'private_key_type' => ...])
 * (forma plana). PHP 8.5 la rechaza con:
 *   "openssl_pkey_new(): Private key length must be at least 384 bits, configured to 0"
 * La forma anidada ['ec' => [...]] sí funciona (y es la que usa web-push v10.1+).
 *
 * Subir de versión no es opción: arrastra symfony/console a 7.4 y rompe
 * Laravel 12.10. Así que parcheamos ese único archivo.
 *
 * Se ejecuta desde composer (post-install-cmd / post-update-cmd) y en Vercel
 * vía "scripts.vercel" (que vercel-php corre después del install). Idempotente.
 */

$file = __DIR__ . '/../vendor/minishlink/web-push/src/Encryption.php';

if (! is_file($file)) {
    fwrite(STDERR, "patch-webpush: no se encontró $file, se omite\n");
    exit(0);
}

$src = file_get_contents($file);

if (preg_match('/openssl_pkey_new\(\s*\[\s*[\'"]ec[\'"]\s*=>/', $src)) {
    echo "patch-webpush: ya estaba parcheado\n";
    exit(0);
}

$pattern = '/openssl_pkey_new\(\s*\[\s*'
    . '[\'"]curve_name[\'"]\s*=>\s*[\'"]prime256v1[\'"]\s*,\s*'
    . '[\'"]private_key_type[\'"]\s*=>\s*OPENSSL_KEYTYPE_EC\s*,?\s*'
    . '\]\s*\)/';

$replacement = "openssl_pkey_new([\n"
    . "            'ec' => [\n"
    . "                'curve_name' => 'prime256v1',\n"
    . "                'private_key_type' => OPENSSL_KEYTYPE_EC,\n"
    . "            ],\n"
    . "        ])";

$patched = preg_replace($pattern, $replacement, $src, 1, $count);

if ($count !== 1 || $patched === null) {
    fwrite(STDERR, "patch-webpush: patrón no encontrado (¿cambió la versión?), se omite\n");
    exit(0);
}

file_put_contents($file, $patched);
echo "patch-webpush: aplicado a vendor/minishlink/web-push/src/Encryption.php\n";
