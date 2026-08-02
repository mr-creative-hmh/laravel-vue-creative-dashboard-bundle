<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Locale & Direction Settings
    |--------------------------------------------------------------------------
    |
    | Supported locales: 'en' (English), 'ar' (Arabic)
    | Auto-detects layout direction (LTR for 'en', RTL for 'ar')
    |
    */
    'default_locale' => env('CRUD_STUDIO_LOCALE', 'en'),

    /*
    |--------------------------------------------------------------------------
    | Vue Page Layout
    |--------------------------------------------------------------------------
    |
    | The Inertia layout component path used for wrapping generated Vue pages.
    | Auto-detects Creative Vue Dashboard or Stock Laravel Vue Starter Kit.
    | Common values: '@/layouts/AppLayout.vue' or 'AppLayout'
    |
    */
    'layout' => env('CRUD_STUDIO_LAYOUT', '@/layouts/AppLayout.vue'),

    /*
    |--------------------------------------------------------------------------
    | Script Language Mode
    |--------------------------------------------------------------------------
    |
    | Script setup mode for generated Vue components.
    | Options: 'ts' (TypeScript - default) or 'js' (JavaScript)
    |
    */
    'script_language' => env('CRUD_STUDIO_SCRIPT_LANG', 'ts'),

    /*
    |--------------------------------------------------------------------------
    | Web UI Settings
    |--------------------------------------------------------------------------
    |
    | Enable or disable the Web UI dashboard route.
    | Recommend keeping enabled only in local development environments.
    |
    */
    'web_ui' => [
        'enabled' => env('CRUD_STUDIO_UI_ENABLED', true),
        'route' => env('CRUD_STUDIO_UI_ROUTE', 'crud-studio'),
        'middleware' => ['web'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Stubs & Generation Defaults
    |--------------------------------------------------------------------------
    |
    | Default flags when generating new CRUD stacks.
    |
    */
    'defaults' => [
        'migration' => true,
        'controller' => true,
        'request' => true,
        'resource' => true,
        'vue_pages' => true,
        'routes' => true,
        'soft_deletes' => false,
        'generate_lang_files' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Schema Storage Directory
    |--------------------------------------------------------------------------
    |
    | Directory relative to base_path() where JSON schemas are persisted.
    |
    */
    'schema_path' => '.crud-studio/schemas',
];
