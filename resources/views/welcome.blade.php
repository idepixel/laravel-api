<!DOCTYPE html>

    <!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
    <!--[if lt IE 9]><script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script><![endif]-->
    <!--[if lt IE 7]><html class="no-js lt-ie10 lt-ie9 lt-ie8 lt-ie7" lang="es-VE" prefix="og: http://ogp.me/ns#"><![endif]-->
    <!--[if IE 7]><html class="no-js lt-ie10 lt-ie9 lt-ie8" lang="es-VE" prefix="og: http://ogp.me/ns#"><![endif]-->
    <!--[if IE 8]><html class="no-js lt-ie10 lt-ie9" lang="es-VE" prefix="og: http://ogp.me/ns#"><![endif]-->
    <!--[if IE 9]><html class="no-js lt-ie10" lang="es-VE" prefix="og: http://ogp.me/ns#"><![endif]-->
    <!--[if gt IE 9]><html class="no-js" lang="es-VE" prefix="og: http://ogp.me/ns#"><![endif]-->

<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>

        <!-- META TAGS -->

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1.0, user-scalable=no"/>

        <meta name="robots" content="all" />
        <meta name="application-name" content="{!! config('app.name') !!}" />
        <meta name="author" content="Daniel Rodríguez" />
        <meta name="owner" content="idepixel" />
        <meta name="copyright" content="Copyrigth (c) 2018 Idepixel LLC" />
        <meta name="generator" content="Visual Studio Code" />
        <meta name="rating" content="General" />
        <meta name="distribution" content="global"/>
        <meta name="revisit-after" content="2 days">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>idepixel | Laravel API</title>

        <meta name="description" content="Laravel-Api es una API completa, desarrollada en Laravel 5.7 y que incluye las funciones necesarias para verificar la dirección correo al registrar un usuario, un sistema de gestión roles y permisos de acceso para los usuarios, y las funciones de perfil de usuarios." />

        <link rel="icon" href="{!! asset('assets/images/logo/logo_color.png') !!}" type="image/x-icon" />
        <link rel="shortcut icon" href="{!! asset('assets/images/logo/logo_color.png') !!}" type="image/x-icon" />
        <link rel="apple-touch-icon" href="{!! asset('assets/images/logo/logo_color.png') !!}" />
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="{!! asset('assets/images/logo/logo_color.png') !!}" />
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>

    </head>

    <body>

        <div class="flex-center position-ref full-height">

            <div class="content">

                <div class="title m-b-md">

                    <h4>Laravel API v1.0 <br> <small>Por idepixel.</small></h4>

                </div>

                <div class="links">

                    <a href="https://laravel.com/docs">Documentación Laravel</a>
                    <a href="https://github.com/idepixel/laravel-api">GitHub</a>
                    <a href="https://idepixel.com/">idepixel</a>

                </div>

            </div>

        </div>

    </body>

</html>
