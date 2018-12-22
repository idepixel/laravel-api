## Laravel-Api (Base)
#### Laravel-Api es una API completa, desarrollada en Laravel 5.7 y que incluye las funciones necesarias para verificar la dirección correo al registrar un usuario, un sistema de gestión roles y permisos de acceso para los usuarios, y las funciones de perfil de usuarios.

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

#### Índice
- [¿Qué es Laravel API?](#laravel-api)
- [Características](#características)
- [¿Cómo instalarlo?](#instrucciones-de-instalación)
- [Seeds](#seeds)
- [Rutas](#rutas)
- [Archivo .env](#archivo-environment)
- [Actualizaciones](#actualizaciones)
- [Licencia de Laravel Api](#licencia-laravel-api)

### Laravel API
Laravel API es la base ideal para iniciar el desarrollo de un proyecto completo, hecha bajo el framework [Laravel](https://laravel.com/) v5.7 incluye todas las potentes funcionalidades que nos brinda este framework e incluye otras que complementan todo lo necesario para iniciar un nuevo proyecto. Incluye [Laravel Passport](https://laravel.com/docs/5.7/passport), [Laravel CORS](https://github.com/barryvdh/laravel-cors), [Laravel Permission](https://github.com/spatie/laravel-permission) para gestionar los roles y permisos de los usuarios, además, todas los Controladores necesarios para iniciar sesión mediante la generación de Tokens, registrar usuarios con confirmación de correo, recuperación de contraseña con envío de correo, envío de datos en formato [JSON](https://www.json.org/) y más. Laravel API es bastante útil y rápida para iniciar proyectos.

### Características

| Laravel API  |
| :------------ |
|Desarrollado en [Laravel](https://laravel.com/) v5.7|
|Configurada con una Base de Datos [MySQL](https://github.com/mysql) (se puede configurar otra)|
|Registro de usuarios con verificación de correo|
|Reinicio de contraseña vía Token enviado al correo|
|Inicio de sesión con opción "recuérdame" (remember)|

### Instrucciones de instalación
1. Ejecuta desde la terminal `git clone https://github.com/idepixel/laravel-api.git laravel-api`
2. Crea una nueva base de datos para el proyecto
    * ```mysql -u root -p```, si usas Vagrant: ```mysql -u homestead -psecret```
    * ```create database laravel-api;```
    * ```\q```
3. Desde la carpeta raíz del proyecto ejecuta `cp .env.example .env` o copia y renombra el archivo `.env.example` por `.env`
4. Configura el archivo `.env` como se muestra más abajo
5. Ejecuta `composer update` desde la carpeta raíz del proyecto
6. Desde la carpeta raíz del proyecto ejecuta:
```
php artisan vendor:publish --provider="Barryvdh\Cors\ServiceProvider" &&
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" --tag="migrations" &&
php artisan vendor:publish --provider="Laravolt\Avatar\ServiceProvider"
```
7. Desde la carpeta raíz del proyecto ejecuta `sudo chmod -R 755 ../laravel-api`
8. Desde la carpeta raíz del proyecto ejecuta `php artisan key:generate`
9. Desde la carpeta raíz del proyecto ejecuta `php artisan migrate`
10. Desde la carpeta raíz del proyecto ejecuta `composer dump-autoload`
11. Desde la carpeta raíz del proyecto ejecuta `php artisan db:seed`

#### Build Cache (Opcional)
1. Desde la carpeta raíz del proyecto ejecuta `php artisan config:cache`

### Seeds
#### Seeded Roles y Permisos
##### Roles
  * Unverified
  * User
  * Administrator
  * SuperUser

##### Permisos
  * delete.user
  * list.profiles
  * show.profile
  * update.profile
  * password.profile
  * list.roles
  * create.role
  * assign.role
  * revoke.role
  * delete.role
  * list.permissions
  * create.permission
  * assign.permission
  * revoke.permission
  * delete.permission

#### Seeded Usuarios

|Email|Password|Access|
|:------------|:------------|:------------|
|user@user.com|password|SuperUser Access|

### Rutas
```
+--------+----------+-----------------------------------------+-----------------------------------+---------------------------------------------------------------------------+--------------------------------------------------------+ | Domain | Method | URI | Name | Action | Middleware | +--------+----------+-----------------------------------------+-----------------------------------+---------------------------------------------------------------------------+--------------------------------------------------------+ | | GET|HEAD | / | | Closure | web | | | POST | api/auth/activate/{token} | | App\Http\Controllers\API\Users\AuthController@activate | api,throttle:10,1 | | | POST | api/auth/login | | App\Http\Controllers\API\Users\AuthController@login | api,throttle:10,1 | | | GET|HEAD | api/auth/logout | | App\Http\Controllers\API\Users\AuthController@logout | api,throttle:10,1,auth:api | | | POST | api/auth/password/create | | App\Http\Controllers\API\Users\AuthController@pass_create | api,throttle:10,1 | | | GET|HEAD | api/auth/password/find/{token} | | App\Http\Controllers\API\Users\AuthController@pass_find | api,throttle:10,1 | | | POST | api/auth/password/reset | | App\Http\Controllers\API\Users\AuthController@pass_reset | api,throttle:10,1 | | | GET|HEAD | api/permissions | | App\Http\Controllers\API\Users\PermissionController@index | api,throttle:10,1,auth:api,role:SuperUser | | | POST | api/permissions/assign | | App\Http\Controllers\API\Users\PermissionController@assign | api,throttle:10,1,auth:api,role:SuperUser | | | DELETE | api/permissions/delete | | App\Http\Controllers\API\Users\PermissionController@delete | api,throttle:10,1,auth:api,role:SuperUser | | | POST | api/permissions/revoke | | App\Http\Controllers\API\Users\PermissionController@revoke | api,throttle:10,1,auth:api,role:SuperUser | | | POST | api/permissions/store | | App\Http\Controllers\API\Users\PermissionController@store | api,throttle:10,1,auth:api,role:SuperUser | | | GET|HEAD | api/ping | | App\Http\Controllers\API\PingController@index | api,throttle:10,1 | | | GET|HEAD | api/roles | | App\Http\Controllers\API\Users\RoleController@index | api,throttle:10,1,auth:api,role:SuperUser | | | POST | api/roles/assign | | App\Http\Controllers\API\Users\RoleController@assign | api,throttle:10,1,auth:api,role:SuperUser | | | DELETE | api/roles/delete | | App\Http\Controllers\API\Users\RoleController@delete | api,throttle:10,1,auth:api,role:SuperUser | | | POST | api/roles/revoke | | App\Http\Controllers\API\Users\RoleController@revoke | api,throttle:10,1,auth:api,role:SuperUser | | | POST | api/roles/store | | App\Http\Controllers\API\Users\RoleController@store | api,throttle:10,1,auth:api,role:SuperUser | | | GET|HEAD | api/routes | | Closure | api,throttle:10,1 | | | DELETE | api/users/delete/{uuid} | | App\Http\Controllers\API\Users\UserController@delete | api,throttle:10,1,auth:api,permission:delete user | | | GET|HEAD | api/users/profile | | App\Http\Controllers\API\Users\ProfileController@index | api,throttle:10,1,auth:api,permission:list profiles | | | POST | api/users/profile/password | | App\Http\Controllers\API\Users\ProfileController@password | api,throttle:10,1,auth:api,permission:password profile | | | GET|HEAD | api/users/profile/show/{uuid} | | App\Http\Controllers\API\Users\ProfileController@show | api,throttle:10,1,auth:api,permission:show profile | | | POST | api/users/profile/update/{uuid} | | App\Http\Controllers\API\Users\ProfileController@update | api,throttle:10,1,auth:api,permission:update profile | | | POST | api/users/store | | App\Http\Controllers\API\Users\UserController@store | api,throttle:10,1 | | | GET|HEAD | oauth/authorize | passport.authorizations.authorize | Laravel\Passport\Http\Controllers\AuthorizationController@authorize | Barryvdh\Cors\HandleCors,web,auth | | | DELETE | oauth/authorize | passport.authorizations.deny | Laravel\Passport\Http\Controllers\DenyAuthorizationController@deny | Barryvdh\Cors\HandleCors,web,auth | | | POST | oauth/authorize | passport.authorizations.approve | Laravel\Passport\Http\Controllers\ApproveAuthorizationController@approve | Barryvdh\Cors\HandleCors,web,auth | | | GET|HEAD | oauth/clients | passport.clients.index | Laravel\Passport\Http\Controllers\ClientController@forUser | Barryvdh\Cors\HandleCors,web,auth | | | POST | oauth/clients | passport.clients.store | Laravel\Passport\Http\Controllers\ClientController@store | Barryvdh\Cors\HandleCors,web,auth | | | DELETE | oauth/clients/{client_id} | passport.clients.destroy | Laravel\Passport\Http\Controllers\ClientController@destroy | Barryvdh\Cors\HandleCors,web,auth | | | PUT | oauth/clients/{client_id} | passport.clients.update | Laravel\Passport\Http\Controllers\ClientController@update | Barryvdh\Cors\HandleCors,web,auth | | | POST | oauth/personal-access-tokens | passport.personal.tokens.store | Laravel\Passport\Http\Controllers\PersonalAccessTokenController@store | Barryvdh\Cors\HandleCors,web,auth | | | GET|HEAD | oauth/personal-access-tokens | passport.personal.tokens.index | Laravel\Passport\Http\Controllers\PersonalAccessTokenController@forUser | Barryvdh\Cors\HandleCors,web,auth | | | DELETE | oauth/personal-access-tokens/{token_id} | passport.personal.tokens.destroy | Laravel\Passport\Http\Controllers\PersonalAccessTokenController@destroy | Barryvdh\Cors\HandleCors,web,auth | | | GET|HEAD | oauth/scopes | passport.scopes.index | Laravel\Passport\Http\Controllers\ScopeController@all | Barryvdh\Cors\HandleCors,web,auth | | | POST | oauth/token | passport.token | Laravel\Passport\Http\Controllers\AccessTokenController@issueToken | Barryvdh\Cors\HandleCors,throttle | | | POST | oauth/token/refresh | passport.token.refresh | Laravel\Passport\Http\Controllers\TransientTokenController@refresh | Barryvdh\Cors\HandleCors,web,auth | | | GET|HEAD | oauth/tokens | passport.tokens.index | Laravel\Passport\Http\Controllers\AuthorizedAccessTokenController@forUser | Barryvdh\Cors\HandleCors,web,auth | | | DELETE | oauth/tokens/{token_id} | passport.tokens.destroy | Laravel\Passport\Http\Controllers\AuthorizedAccessTokenController@destroy | Barryvdh\Cors\HandleCors,web,auth | +--------+----------+-----------------------------------------+-----------------------------------+---------------------------------------------------------------------------+--------------------------------------------------------+
```

### Archivo Environment
Ejemplo del archivo `.env`:

```bash

APP_NAME="Laravel Api"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_api
DB_USERNAME=homestead
DB_PASSWORD=secret

BROADCAST_DRIVER=log
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_DRIVER=smtp
MAIL_HOST=smtp.googlemail.com
MAIL_PORT=587
MAIL_USERNAME=gmail_account
MAIL_PASSWORD=gmail_password
MAIL_ENCRYPTION=tls

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=mt1

MIX_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
MIX_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"

```

### Actualizaciones
* Primer commit al repositorio (22/12/2018)

### Licencia Laravel Api
Laravel-Api is licensed under the [MIT license](https://opensource.org/licenses/MIT). Enjoy!
