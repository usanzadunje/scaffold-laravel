<?php

namespace Usanzadunje\Scaffold\Presets;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;

class Docker extends Preset
{
    /**
     * Initiate Docker scaffolding.
     *
     * @return void
     */
    public static function install(): void {
        // Bootstrapping
        static::ensureDirectoriesExist();
        static::updateBootstrapping();
        static::updateEnvContent();
        static::updateWebpackConfiguration();
    }

    /**
     * Ensure directories we need actually exists in project.
     *
     * @return void
     */
    protected static function ensureDirectoriesExist(): void {
        (new Filesystem)->ensureDirectoryExists(base_path('docker-compose'));
    }

    /**
     * Bootstrap application with Docker.
     *
     * @return void
     */
    protected static function updateBootstrapping(): void {
        File::copyDirectory(__DIR__ . '/docker-stubs/docker-compose', base_path('docker-compose'));
        copy(__DIR__ . '/docker-stubs/docker-compose.yml', base_path('docker-compose.yml'));
        copy(__DIR__ . '/docker-stubs/docker-compose.prod.yml', base_path('docker-compose.prod.yml'));
        copy(__DIR__ . '/docker-stubs/.dockerignore', base_path('.dockerignore'));
    }

    /**
     * Updating .'env' file in order for Docker to run properly.
     *
     * @return void
     */
    public static function updateEnvContent(): void {
        if(file_exists(base_path('.env'))) {
            $replace = str_replace(
                "DB_HOST=" . env('DB_HOST'),
                "DB_HOST=db",
                file_get_contents(base_path('.env'))
            );
            file_put_contents(
                base_path('.env'),
                $replace
            );
        }

        if(env('DB_USERNAME') === 'root') {
            $replace = str_replace(
                "DB_USERNAME=" . env('DB_USERNAME'),
                "DB_USERNAME=laravel",
                file_get_contents(base_path('.env'))
            );
            file_put_contents(
                base_path('.env'),
                $replace
            );
        }

        if(env('DB_PASSWORD') === '') {
            $replace = str_replace(
                "DB_PASSWORD=",
                "DB_PASSWORD=laravel",
                file_get_contents(base_path('.env'))
            );
            file_put_contents(
                base_path('.env'),
                $replace
            );
        }

        if(env('DB_PORT') != '3306') {
            $replace = str_replace(
                "DB_PORT=" . env('DB_PORT'),
                "DB_PORT=3306",
                file_get_contents(base_path('.env'))
            );
            file_put_contents(
                base_path('.env'),
                $replace
            );
        }
    }

    /**
     * Updating 'webpack.mix.js' file in order for Docker to work with browserSync plugin.
     *
     * @return void
     */
    public static function updateWebpackConfiguration(): void {
        if(file_exists(base_path('webpack.mix.js'))) {
            $replace = str_replace(
                "127.0.0.1:8000',",
                "app_container',\n\thost: 'localhost',",
                file_get_contents(base_path('webpack.mix.js'))
            );
            $replace = str_replace(
                "open: true",
                "open: false",
                $replace
            );
            file_put_contents(
                base_path('webpack.mix.js'),
                $replace
            );
        }
    }
}


