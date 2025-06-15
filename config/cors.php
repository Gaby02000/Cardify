<?php

return [
    'paths' => ['apis/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    // 'allowed_origins' => ['http://localhost:5173', 'https://cardify-frontend-git-dev-gaby02000s-projects.vercel.app'],
    'allowed_origins' => ['*'],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];
