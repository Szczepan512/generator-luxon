<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application for file storage.
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Below you may configure as many filesystem disks as necessary, and you
    | may even configure multiple disks for the same driver. Examples for
    | most supported storage drivers are configured here for reference.
    |
    | Supported drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'serve' => true,
            'throw' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL') . '/storage',
            'visibility' => 'public',
            'throw' => false,
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
        ],




        'devst_ftp' => [
            'driver' => 'ftp',
            'host' => env('DEV_HOST'),
            'username' => env('DEV_USERNAME'),
            'password' => env('DEV_PASSWORD'),
            'root' => env('DEV_ROOT'),
            'passive' => true,
            'ssl' => true,
            'timeout' => 30,
            'ignorePassiveAddress' => true,
        ],

        'individual_luxon_ftp_pl' => [
            'driver' => 'ftp',
            'host' => env('PL_INDIVIDUAL_FTP_HOST'),
            'username' => env('PL_INDIVIDUAL_FTP_USERNAME'),
            'password' => env('PL_INDIVIDUAL_FTP_PASSWORD'),
            'root' => env('PL_INDIVIDUAL_FTP_ROOT'),
            'passive' => true,
            'ssl' => true,
            'timeout' => 30,
            'ignorePassiveAddress' => true,
        ],

        'individual_luxon_ftp_de' => [
            'driver' => 'ftp',
            'host' => env('DE_INDIVIDUAL_FTP_HOST'),
            'username' => env('DE_INDIVIDUAL_FTP_USERNAME'),
            'password' => env('DE_INDIVIDUAL_FTP_PASSWORD'),
            'root' => env('DE_INDIVIDUAL_FTP_ROOT'),
            'passive' => true,
            'ssl' => true,
            'timeout' => 30,
            'ignorePassiveAddress' => true,
        ],

        'individual_luxon_ftp_en' => [
            'driver' => 'ftp',
            'host' => env('EN_INDIVIDUAL_FTP_HOST'),
            'username' => env('EN_INDIVIDUAL_FTP_USERNAME'),
            'password' => env('EN_INDIVIDUAL_FTP_PASSWORD'),
            'root' => env('EN_INDIVIDUAL_FTP_ROOT'),
            'passive' => true,
            'ssl' => true,
            'timeout' => 30,
            'ignorePassiveAddress' => true,
        ],

        'family_luxon_ftp_pl' => [
            'driver' => 'ftp',
            'host' => env('PL_FAMILY_FTP_HOST'),
            'username' => env('PL_FAMILY_FTP_USERNAME'),
            'password' => env('PL_FAMILY_FTP_PASSWORD'),
            'root' => env('PL_FAMILY_FTP_ROOT'),
            'passive' => true,
            'ssl' => true,
            'timeout' => 30,
            'ignorePassiveAddress' => true,
        ],

        'family_luxon_ftp_de' => [
            'driver' => 'ftp',
            'host' => env('DE_FAMILY_FTP_HOST'),
            'username' => env('DE_FAMILY_FTP_USERNAME'),
            'password' => env('DE_FAMILY_FTP_PASSWORD'),
            'root' => env('DE_FAMILY_FTP_ROOT'),
            'passive' => true,
            'ssl' => true,
            'timeout' => 30,
            'ignorePassiveAddress' => true,
        ],

        'family_luxon_ftp_en' => [
            'driver' => 'ftp',
            'host' => env('EN_FAMILY_FTP_HOST'),
            'username' => env('EN_FAMILY_FTP_USERNAME'),
            'password' => env('EN_FAMILY_FTP_PASSWORD'),
            'root' => env('EN_FAMILY_FTP_ROOT'),
            'passive' => true,
            'ssl' => true,
            'timeout' => 30,
            'ignorePassiveAddress' => true,
        ],


        'luxon_ftp' => [
            'driver' => 'ftp',
            'host' => env('LUXON_FTP_HOST'),
            'username' => env('LUXON_FTP_USERNAME'),
            'password' => env('LUXON_FTP_PASSWORD'),
            'root' => env('LUXON_FTP_ROOT'),
            // 'passive' => true,
            'passive' => false,
            'ignorePassiveAddress' => true,
            'ssl' => true,
            // 'timeout' => 30,
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
        public_path('storage') => storage_path('app/public'),
    ],

];
