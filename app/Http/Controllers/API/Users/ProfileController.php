<?php

/**
 *  @package        laravel-api.Http.Controllers.API.Users
 *
 *  @author         Daniel Rodríguez | idepixel (idepixel@gmail.com).
 *  @copyright      idepixel (c) 2018 - Todos los derechos reservados.
 *
 *  @since          Versión 1.0, revisión 22/12/2018.
 *  @version        1.0
 *
 *  @final
 */

namespace App\Http\Controllers\API\Users;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Hash;
use Validator;

use App\Models\User;
use App\Models\Profile;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use App\Http\Resources\Profile as ProfileResource;

class ProfileController extends Controller {

    protected $model;

    /**
     * Crea una nueva instancia del controlador.
     *
     * @return void
     */
    public function __construct(User $model) {

        $this->model = $model;

        // Aplica un Middleware de permisos para cada función
        $this->middleware('permission:list profiles')->only('index');
        $this->middleware('permission:show profile')->only('show');
        $this->middleware('permission:update profile')->only('update');
        $this->middleware('permission:password profile')->only('password');
    }

    /**
     * Ver todas las instancias del modelo Profile registradas.
     *
     * @return [collection] ProfileResource
     */
    public function index( ) {

        // Retorna una colección de usuarios
        return ProfileResource::collection( $this->model::get( ) );
    }

    /**
     * Ver una instancia específica del modelo Profile.
     *
     * @return [resource] ProfileResource
     */
    public function show( Request $request, $uuid ) {

        if( $request->user( )->uuid != $uuid) {

            // Obtiene al usuario a mostrar por UUID
            $user = User::where( 'uuid', $uuid )->first( );

            // Si no consigue al usuario retorna un error 404 (Not Found)
            if ( ! $user )

                return response( )->json([

                    'success' => false,
                    'message' => 'No existe un usuario con el UUID enviado.',
                    'code'    => 404,

                ], 404 );

        } else $user = $request->user( );

        // Retorna el recurso de perfil del usuario
        return new ProfileResource( $user );
    }

    /**
     * Actualiza una instancia del modelo Profile enviada por parámetros a la función.
     *
     * @return [string] message
     */
    public function update( Request $request, $uuid ) {

        // Hace las validaciones de los datos enviados por el request
        $request->validate([

            'name'                  => 'required|string',
            'lastname'              => 'required|string',
            'bio'                   => 'required|string|max:255',
        ]);

        if( $request->user( )->uuid != $uuid) {

            // Obtiene al usuario a mostrar por UUID
            $user = User::where( 'uuid', $uuid )->first( );

            // Si no consigue al usuario retorna un error 404 (Not Found)
            if ( ! $user )

                return response( )->json([

                    'success' => false,
                    'message' => 'No existe un usuario con el UUID enviado.',
                    'code'    => 404,

                ], 404 );

        } else $user = $request->user( );

        // Obtiene el perfil del usuario
        $profile = $user->profile;

        // Si no consigue el perfil de usuario retorna un error 404 (Not Found)
        if ( ! $profile )

            // Retorna un error 404 (Not Found)
            return response( )->json([

                'success' => false,
                'message' => 'No existe un perfil de usuario.',
                'code'    => 404,

            ], 404 );

        // Guarda los datos en el modelo Profile
        $profile->name     = $request->input( 'name' );
        $profile->lastname = $request->input( 'lastname' );
        $profile->bio      = $request->input( 'bio' );

        // Guarda el modelo Profile en la BD
        if( $profile->save( ) )

            // Retorna una respuesta 200 (OK)
            return response( )->json([

                'success' => true,
                'message' => 'Perfil actualizado con éxito.',
                'code'    => 200,

            ], 200 );
    }

    /**
     * Actualiza la contraseña del usuario.
     *
     * @return [string] message
     */
    public function password( Request $request ) {

        // Hace las validaciones de los datos enviados por el request
        $request->validate([

            'current'               => 'required|string|min:6|max:60',
            'password'              => 'required|string|min:6|max:60|confirmed',
            'password_confirmation' => 'required|same:password',
        ]);

        // Obtiene al usuario que genera la solicitud
        $user = $request->user( );

        if ( $request->input('password') != null ) {

            if ( ! Hash::check( $request->current, $user->password ) )

                // Si no coincide el password actual retorna un error 403 (Forbidden)
                return response( )->json([

                    'success' => false,
                    'message' => 'La contraseña actual no coincide.',
                    'code'    => 403,

                ], 403 );

            $user->password = Hash::make( $request->input( 'password_confirmation' ) );
        }

        // Guarda los cambios en la BD
        if( $user->save( ) ) {

            // Cierra la sesión del usuario
            $request->user()->token()->delete();

            // Retorna una respuesta 201 (Created)
            return response( )->json([

                'success' => true,
                'message' => 'Contraseña actualizada con éxito, se ha cerrado la sesión.',
                'code'    => 201,

            ], 201 );
        }
    }
}
