# Scaffold Laravel application.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/usanzadunje/scaffold-laravel.svg?style=flat-square)](https://packagist.org/packages/usanzadunje/scaffold-laravel)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/usanzadunje/scaffold-laravel/run-tests?label=tests)](https://github.com/usanzadunje/scaffold-laravel/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/usanzadunje/scaffold-laravel/Check%20&%20fix%20styling?label=code%20style)](https://github.com/usanzadunje/scaffold-laravel/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/usanzadunje/scaffold-laravel.svg?style=flat-square)](https://packagist.org/packages/usanzadunje/scaffold-laravel)

Multiple scaffolding  options for Laravel application. 

Installing Vue, adding Vue Router/Inertia as your frontend router, adding Vuex as state manager. Replacing Webpack with Vite or installing Browser Sync plugin for Webpack. Also adds scaffolding for Docker with any of these presets mentioned.

## Installation

You can install the package via composer:

```bash
composer require usanzadunje/scaffold-laravel
```

## Usage

```php
php artisan scaffold [preset]
```
Available presets :

- `vue`
- `vue-router` *(if you have vue application set up)*
- `vuex` *(if you have vue application set up)*
- `vite` *(replaces webpack for vite)*
- `docker`
- `all` *(installs vue, vite and docker preset all together)*

## Vue 
Will generate following files and directories inside your resources/js directory :

- `components` directory
- `composables` directory
- `views` directory and `Example.vue` inside it
- `app.js` files with minimal scaffolding for Vue application
- `app.blade.php` file with #app container defined
- Change `/` route to return `app` view instead of `welcome`

Additionally, it will ask if you would like router or state manager installed as well.

## Vue Router
Will generate following files and directories inside your resources/js directory :

- `router` directory and inside it basic scaffolding for routes with one example route defined
- `Welcome.vue` file inside `views` which will be shown for example route defined
- `ExampleApp.vue` which will serve as entrypoint to router *(it has <router-view/> defined in it)*
- It will initialize itself inside `app.js` file

Additionally, it will ask if you would like basic middleware scaffolding for your routes. This will then create `middlewarePipeline.js` file which is responsible for calling all middlewares defined on certain route. And inside `router/index,js` file where routes are defined it will add `beforeEach` navigation gourd and call this pipeline.

Routes which require middleware(s) should have meta filed with middleware property defined on them which is an array of middlewares. You can define your middlewares in `middlewares` directory which will be created along with example middleware.

## Vuex
Will generate following files and directories inside your resources/js directory :

- `store` directory and inside it basic scaffolding for vuex in module fashion
- `Module.js` file inside `store` which is example of single module state
- It will initialize itself inside `app.js` file
- Vuex scaffolding comes along with `vue-persistedstate` plugin which will persist data from state in `Local Storage`

## Vite
Will remove all packages, scripts and files associated with `Webpack`. Additionally, it will create `vite.config.js` in root of project directory and setup basic configuration.

If you have `Vue` installed it will add `Vite` plugin for `Vue` and optimize configuration for it as well (same goes if `Vue Router` or `Vuex` are installed)

If you installed `Vue` using this package it will generate `app.blade.php` file and `Vite` scaffolding will remove assets(css/js) imported using `Webpack` and generate `@vite` blade directive which will generate imports for `Vite` assets(css/js). 
If you however do not have `app.blade.php` file it will not try and replace these, so you will need to add `@vite` directive yourself to your layout file.
It will also import your `css/app.css` file inside app.js since this is how `Vite` handles css.

## Docker
Will generate `docker-compose.yml` and `docker-compose.prod.yml` files with container details. Also it will generate docker-compose directory which contains configuration for: `nginx`, `php`, `suppervisord` and initialization scripts for development and production.

It will adopt itself depending on whether you use `Webpack` or `Vite` in order to work smoothly with them and change `Webpack` or `Vite` configuration accordingly

It will change your `.env` file and following contents of it:

- `DB_HOST` and set it to `db` so docker can resolve it, since database will live inside container
- `DB_USERNAME` **only** if it is set to `root` and will change it to `laravel`
- `DB_PASSWORD` **only** if it is set to nothing(empty) and will change it to `laravel`
- `DB_PORT` **only** if it is set to something other than `3306` since this is port used by container_name

Additional information: 

Database port exposed on your local machine is set to 3310 if you want to connect to it from your host machine. 

Application port if using with `Webpack` but without `Browser Sync` plugin setup will be exposed to `8000`(served on `localhost:8000`).

If you are however using `Webpack` with `Browser Sync` port exposed will be `3000` (served on `localhost:3000`)

When using `Vite` port exposed will be `3000` (served on `localhost:3000`)

Scripts that are responsible for applications in production / development are inside `docker-compose` directory, and they are `init.sh` / `initDev.sh` files respectively.
Feel free to change them as these are commands that will run on start of the container which hold application.

## Disclaimer

This is my first package, and I made it primarily because I caught myself doing same things on almost every project(copy -> pasting) so I wanted to save myself some time and created this. I also tweaked it a little, so it could be used in multiple occasions so maybe other people can use it. But keep in mind it was made having my problems in mind. 

If you have any suggestion that would help you or things to add I would be happy to do it. If it helps one person I would be glad :).

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Dusan Djordjevic](https://github.com/usanzadunje)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
