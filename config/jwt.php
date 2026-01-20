<?php
return [
    'public' => [

        "crv" => "P-256",

        "key_ops" => [

            "verify"

        ],

        "kty" => "EC",

        "x" => "uaU-udvcfBqfKgHZGHN7iMNYxAY0Z5QmreIeXGsZ4tA",

        "y" => "EMRkdbL9UEifidsU9_UslSrlF-suLCgEi7kxZr0lvGQ",

        "alg" => "ES256",
        "use" => "sig",

        "kid" => "f74063a180dd915b4e6d23742daaf57f"

    ],
    'algo' => env('JWT_ALGO', 'ES256'),
    'private' => env('JWT_PRIVATE_KEY', 'your-private-key'),
];
