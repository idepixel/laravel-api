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

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\User;
use Spatie\Permission\Models\Role;

class RoleController extends Controller {

    protected $model;

    /**
     * Crea una nueva instancia del controlador.
     *
     * @return void
     */
    public function __construct(Role $model) {

        $this->model = $model;

        // Aplica un Middleware de role para todas las funciones
        $this->middleware(['role:SuperUser']);
    }

    /**
     * Retorna todas las instancias del modelo Role.
     *
     * @return [object] Role array
     */
    public function index( ) {

        $roles = $this->model::with('permissions')->get( );

        if( $roles )

            return response( )->json([

                'success' => true,
                'roles' => $roles,

            ], 200 );
    }

    /**
     * Crea una nueva instancia del modelo Role.
     *
     * @return [string] message
     */
    public function store( Request $request ) {

        // Hace las validaciones de los datos enviados por el request
        $request->validate(['name' => 'required|string']);

        // Crea el Role en la BD
        $role = $this->model::create( ['name' => $request->name, 'guard_name' => 'api'] );

        if( $role )

            // Retorna una respuesta 201 (Created)
            return response( )->json([

                'success' => true,
                'message' => 'Se ha creado el Role con éxito.',
                'code'    => 201,

            ], 201 );
    }

    /**
     * Asignar un Role a un User.
     *
     * @return [string] message
     */
    public function assign( Request $request ) {

        // Hace las validaciones de los datos enviados por el request
        $request->validate([

            'role_id'   => 'required|string',
            'user_uuid' => 'required|string',
        ]);

        // Obtiene el rol por ID
        $role = $this->model::where( 'id', $request->role_id )->first( );

        // Si no consigue al rol retorna un error 404 (Not Found)
        if ( ! $role )

            return response( )->json([

                'success' => false,
                'message' => 'No existe un rol con el ID enviado.',
                'code'    => 404,

            ], 404 );

        // Obtiene al usuario a asignarle un rol por UUID
        $user = User::where( 'uuid', $request->user_uuid )->first( );

        // Si no consigue al usuario retorna un error 404 (Not Found)
        if ( ! $user )

            return response( )->json([

                'success' => false,
                'message' => 'No existe un usuario con el UUID enviado.',
                'code'    => 404,

            ], 404 );

        if( $role && $user ) {

            // Asigna el rol al usuario
            $user->assignRole( $role );

            // Retorna una respuesta 200 (OK)
            return response( )->json([

                'success' => true,
                'message' => 'Rol asignado con éxito.',
                'code'    => 200,

            ], 200 );
        }
    }

    /**
     * Revoca un Role a un User.
     *
     * @return [string] message
     */
    public function revoke( Request $request ) {

        // Hace las validaciones de los datos enviados por el request
        $request->validate([

            'role_id'   => 'required|string',
            'user_uuid' => 'required|string',
        ]);

        // Obtiene el rol por ID
        $role = $this->model::where( 'id', $request->role_id )->first( );

        // Si no consigue al rol retorna un error 404 (Not Found)
        if ( ! $role )

            return response( )->json([

                'success' => false,
                'message' => 'No existe un rol con el ID enviado.',
                'code'    => 404,

            ], 404 );

        // Obtiene al usuario a asignarle un rol por UUID
        $user = User::where( 'uuid', $request->user_uuid )->first( );

        // Si no consigue al usuario retorna un error 404 (Not Found)
        if ( ! $user )

            return response( )->json([

                'success' => false,
                'message' => 'No existe un usuario con el UUID enviado.',
                'code'    => 404,

            ], 404 );

        if( $role && $user ) {

            /**
             * AQUI QUITA EL ROL AL USUARIO
             */

            // Retorna una respuesta 200 (OK)
            return response( )->json([

                'success' => true,
                'message' => 'Rol revocado con éxito.',
                'code'    => 200,

            ], 200 );
        }
    }

    /**
     * Elimina una instancia del modelo Role enviada por parámetros a la función.
     *
     * @return [string] message
     */
    public function delete( $id ) {

        // Obtiene el rol por ID
        $role = $this->model::where( 'id', $id )->first( );

        // Si no consigue al rol retorna un error 404 (Not Found)
        if ( ! $role )

            return response( )->json([

                'success' => false,
                'message' => 'No existe un rol con el ID enviado.',
                'code'    => 404,

            ], 404 );

        // Elimina el rol de la BD
        if( $role->delete( ) )

            // Retorna una respuesta 200 (OK)
            return response( )->json([

                'success' => true,
                'message' => 'Rol eliminado con éxito.',
                'code'    => 200,

            ], 200 );
    }
}
