<?php

return [
  'cielo'  => [
    'client_id' => env('CIELO_CLIENT_ID', null),
    'client_secret' => env('CIELO_CLIENT_SECRET', null),
    'base_url' => env('CIELO_REGISTER_URL', 'https://api2.cielo.com.br'),
    'auth_url' => env('CIELO_AUTH_URL', 'https://minhaconta2.cielo.com.br/oauth'),
  ],
];
