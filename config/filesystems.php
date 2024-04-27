<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been set up for each driver as an example of the required values.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
            'throw' => false,
            'visibility' => 'public',
        ],

        'db_backup' => [
            'driver' => 'local',
            'root' => storage_path('app/backup'),
        ],

        'root' => [
            'driver' => 'local',
            'root' => base_path('/'),
        ],

        'audio' => [
            'driver' => 'local',
            'root'   => public_path() . '/storage',
            'url' => env('APP_URL').'/public',
            'visibility' => 'public',
        ],

        'public' => [
            'driver' => 'local',
            'root' => public_path(),
            'url' => env('APP_URL').'/public',
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
            'visibility' => 'public',
            'options' => ['ContentDisposition' => 'attachment'],
        ],

        'wasabi' => [
            'driver' => 's3',
            'key' => env('WASABI_ACCESS_KEY_ID'),
            'secret' => env('WASABI_SECRET_ACCESS_KEY'),
            'region' => env('WASABI_DEFAULT_REGION'),
            'bucket' => env('WASABI_BUCKET'),
            'endpoint' => 'https://s3.' . env('WASABI_DEFAULT_REGION') . '.wasabisys.com',
            'visibility' => 'public',
            'options' => ['ContentDisposition' => 'attachment'],
        ],

        'gcs' => [
            'driver' => 'gcs',
            'key_file_path' => env('GOOGLE_APPLICATION_CREDENTIALS', null), 
            'bucket' => env('GOOGLE_STORAGE_BUCKET', 'your-bucket'),
            'visibility' => 'public', 
            'visibility_handler' => null, 
            'metadata' => ['cacheControl'=> 'public,max-age=86400', 'contentDisposition' => 'attachment'], 
        ],

        'storj' => [
            'driver' => 's3',
            'key' => env('STORJ_ACCESS_KEY_ID'),
            'secret' => env('STORJ_SECRET_ACCESS_KEY'),
            'region' => 'us-east-1',
            'bucket' => env('STORJ_BUCKET'),
            'endpoint' => 'https://gateway.storjshare.io',
            'visibility' => 'public',
            'options' => ['ContentDisposition' => 'attachment', 'CacheControl'=> 'public,max-age=86400',],
        ],

        'dropbox' => [
            'driver' => 'dropbox',
            'key' => env('DROPBOX_APP_KEY'),
            'secret' => env('DROPBOX_APP_SECRET'),
            'authorization_token' => env('DROPBOX_ACCESS_TOKEN'),
        ],

        'r2' => [
            'driver' => 's3',
            'key' => env('CLOUDFLARE_R2_ACCESS_KEY_ID'),
            'secret' => env('CLOUDFLARE_R2_SECRET_ACCESS_KEY'),
            'region' => 'us-east-1',
            'bucket' => env('CLOUDFLARE_R2_BUCKET'),
            'url' => env('CLOUDFLARE_R2_URL'),
            'visibility' => 'public',
            'endpoint' => env('CLOUDFLARE_R2_ENDPOINT'),
            'use_path_style_endpoint' => env('CLOUDFLARE_R2_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
        ],


    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
      
    ],

];
