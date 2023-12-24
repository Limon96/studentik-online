<?php

return [
    'api_key' => env('SIGMA_API_KEY', ''),
    'email' => env('SIGMA_EMAIL', ''),
    'project_id' => env('SIGMA_PROJECT_ID', ''),
    'wallet_id' => env('SIGMA_WALLET_ID', ''),
    'payout_wallet_id' => env('SIGMA_PAYOUT_WALLET_ID', ''),
    'is_test' => env('SIGMA_IS_TEST', false),
];
