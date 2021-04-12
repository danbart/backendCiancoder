<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Configuraciones

Al descargar el repositorio ejecutar el comando `composer install` para instalar todas las dependencias.

Al culminar la descarga crear el archivo `.env` donde se encuentran las variables de entorno necesarias para el funcionamiento de la aplicación.

Para terminar ejecute el comando `php artisan key:generate` para generar una clave privada que necesita laravel para el funcionamiento.

Para la configuración de la libreria `JWT` para el acceso por web tocken, para ello ejecute el comando `php artisan jwt:secret` que generara nuestra clave privada para la encriptacion de nuestro tocken.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
