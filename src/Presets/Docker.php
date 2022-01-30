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
        static::ensureDirectoriesExist();
        static::updateBootstrapping();
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
}