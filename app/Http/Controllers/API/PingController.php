<?php

/**
 *  @package        laravel-api.Http.Controllers.API
 *
 *  @author         Daniel Rodríguez | idepixel (idepixel@gmail.com).
 *  @copyright      idepixel (c) 2018 - Todos los derechos reservados.
 *
 *  @since          Versión 1.0, revisión 22/12/2018.
 *  @version        1.0
 *
 *  @final
 */

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PingController extends Controller {

    /**
     * Responde con un status para chequear que el sistema esté en línea.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response( )->json([

            'status'  => 'ok',
            'success' => true,
            'timestamp' => Carbon::now()->toDateTimeString(),

        ], 200 );
    }
}
