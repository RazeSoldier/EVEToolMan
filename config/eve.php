<?php

return [
    'clientId' => env('EVE_Client_Id'),
    'clientSecret' => env('EVE_Client_Secret'),
    'callbackURL' => env('EVE_Callback', '/eve-sso/callback'),
];
