<?php

use Illuminate\Support\ServiceProvider;

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    */

    'name' => env('APP_NAME', 'Laravel'),

    // 👇 your custom currency config is fine
    'currency' => env('APP_CURRENCY', 'PHP'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    */

    'debug' => (bool) env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    */

    'url' => env('APP_URL', 'http://localhost'),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    */

    'timezone' => 'UTC',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    */

    'locale' => env('APP_LOCALE', 'en'),

    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),

    'faker_locale' => env('APP_FAKER_LOCALE', 'en_US'),
    

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    */

    'cipher' => 'AES-256-CBC',

    'key' => env('APP_KEY'),

    'previous_keys' => [
        ...array_filter(
            explode(',', (string) env('APP_PREVIOUS_KEYS', ''))
        ),
    ],

    /*
    |--------------------------------------------------------------------------
    | Application Service Providers
    |--------------------------------------------------------------------------
    |
    | IMPORTANT: we use Laravel's default provider set and just MERGE anything
    | extra we want. Do NOT overwrite this like we did earlier.
    |
    */

    'providers' => ServiceProvider::defaultProviders()->merge([

        /*
         * Package Service Providers...
         */

        // You actually *don't* need to register these manually because
        // spatie/laravel-permission and intervention/image-laravel
        // both support auto-discovery.
        //
        // If you *really* want to force-register Intervention, you can
        // uncomment this line:
        //
        // \Intervention\Image\Laravel\ServiceProvider::class,

        /*
         * Application Service Providers...
         */

        // If you had your own providers they would go here, e.g.:
        // App\Providers\AppServiceProvider::class,

    ])->toArray(),

    /*
    |--------------------------------------------------------------------------
    | Maintenance Mode Driver
    |--------------------------------------------------------------------------
    */

    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],
    

];
