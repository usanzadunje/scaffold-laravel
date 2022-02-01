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
    public static function install(): void
    {
        // Bootstrapping
        static::ensureDirectoriesExist();
        static::updateBootstrapping();
        static::updateEnvContent();
        if(file_exists(base_path('vite.config.js')))
        {
            static::updateViteConfiguration();
        }else
        {
            static::updateWebpackConfiguration();
        }
    }

    /**
     * Ensure directories we need actually exists in project.
     *
     * @return void
     */
    protected static function ensureDirectoriesExist(): void
    {
        (new Filesystem())->ensureDirectoryExists(base_path('docker-compose'));
    }

    /**
     * Bootstrap application with Docker.
     *
     * @return void
     */
    protected static function updateBootstrapping(): void
    {
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
    public static function updateEnvContent(): void
    {
        if(file_exists(base_path('.env')))
        {
            $replaced = str_replace(
                "DB_HOST=" . env('DB_HOST'),
                "DB_HOST=db",
                file_get_contents(base_path('.env'))
            );
            file_put_contents(
                base_path('.env'),
                $replaced
            );
        }

        if(env('DB_USERNAME') === 'root')
        {
            $replaced = str_replace(
                "DB_USERNAME=root",
                "DB_USERNAME=laravel",
                file_get_contents(base_path('.env'))
            );
            file_put_contents(
                base_path('.env'),
                $replaced
            );
        }

        if(env('DB_PASSWORD') === '')
        {
            $replaced = str_replace(
                "DB_PASSWORD=",
                "DB_PASSWORD=laravel",
                file_get_contents(base_path('.env'))
            );
            file_put_contents(
                base_path('.env'),
                $replaced
            );
        }

        if(env('DB_PORT') != '3306')
        {
            $replaced = str_replace(
                "DB_PORT=" . env('DB_PORT'),
                "DB_PORT=3306",
                file_get_contents(base_path('.env'))
            );
            file_put_contents(
                base_path('.env'),
                $replaced
            );
        }
    }

    /**
     * Updating 'webpack.mix.js' file in order for Docker to work with browserSync plugin.
     *
     * @return void
     */
    public static function updateWebpackConfiguration(): void
    {
        if(
            file_exists(base_path('webpack.mix.js')) &&
            preg_match('/mix.browserSync/', file_get_contents(base_path('webpack.mix.js')))
        )
        {
            $matches = null;
            preg_match("/proxy: '([^']+)'/", file_get_contents(base_path('webpack.mix.js')), $matches);

            $replaced = str_replace(
                'mix.browserSync({',
                "mix.browserSync({\n\thost: 'localhost',",
                file_get_contents(base_path('webpack.mix.js'))
            );
            $replaced = str_replace(
                $matches[1],
                "app_container",
                $replaced
            );
            $replaced = str_replace(
                "open: true",
                "open: false",
                $replaced
            );
            file_put_contents(
                base_path('webpack.mix.js'),
                $replaced
            );
        }
    }

    /**
     * Updating 'vite.config.js' file in order to work with Docker.
     *
     * @return void
     */
    public static function updateViteConfiguration(): void
    {
        // Replacing host in 'vite.config.js' file
        $matches = null;
        preg_match("/host: '([^']+)'/", file_get_contents(base_path('vite.config.js')), $matches);

        $replaced = str_replace(
            $matches[0],
            "host: '0.0.0.0'",
            file_get_contents(base_path('vite.config.js'))
        );

        file_put_contents(
            base_path('vite.config.js'),
            $replaced
        );

        // Replacing npm command in 'docker-compose.yml' file
        $replaced = str_replace(
            "watch",
            "dev",
            file_get_contents(base_path('docker-compose.yml'))
        );

        file_put_contents(
            base_path('docker-compose.yml'),
            $replaced
        );

        // Replacing ports in 'docker-compose.yml' file
        $replaced = str_replace(
            "  - \"3000:3000\"",
            "",
            file_get_contents(base_path('docker-compose.yml'))
        );
        $replaced = str_replace(
            "entrypoint: [ \"bash\",\"./docker-compose/initDev.sh\" ]",
            "ports:\n      - \"3000:3000\"\n    entrypoint: [ \"bash\",\"./docker-compose/initDev.sh\" ]",
            $replaced
        );

        file_put_contents(
            base_path('docker-compose.yml'),
            $replaced
        );

        // Replacing serving port
        $replaced = str_replace(
            "--port=80",
            "--port=3000",
            file_get_contents(base_path('docker-compose/initDev.sh'))
        );

        file_put_contents(
            base_path('docker-compose/initDev.sh'),
            $replaced
        );
    }
}
