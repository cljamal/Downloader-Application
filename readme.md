<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>
 
## Web:

get: / - Главная страница

get: /get-file/{file} - Получить конкретный файл по ID из базы данных

=====================================

## Api:

post: download-link - Получение ссылки для загруки методом пост

get: get-files - Получение всех  загруженных файлов из базы данных

===================================== 

## Console:
php artisan download:file { file_url } - Загрузка файла по url из консоли

===================================== 
