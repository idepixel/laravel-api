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
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller {

    protected $model;

    /**
     * Crea una nueva instancia del controlador.
     *
     * @return void
     */
    public function __construct(Permission $model) {

        $this->model = $model;

        // Aplica un Middleware de role para todas las funciones
        $this->middleware(['role:SuperUser']);
    }

    /**
     * Retorna todas las instancias del modelo Permission.
     *
     * @return [object] Permission array
     */
    public function index( ) {

        $permissions = $this->model::with('roles')->get( );

        if( $permissions )

            return response( )->json([

                'success' => true,
                'permissions' => $permissions,

            ], 200 );
    }

    /**
     * Crea una nueva instancia del modelo Permission.
     *
     * @return [string] message
     */
    public function store( Request $request ) {

        // Hace las validaciones de los datos enviados por el request
        $request->validate(['name' => 'required|string']);

        // Obtiene el rol SuperUser
        $role = Role::where('name','SuperUser')->first( );

        // Si no consigue al rol retorna un error 404 (Not Found)
        if ( ! $role )

            return response( )->json([

                'success' => false,
                'message' => 'No existe un rol con el nombre SuperUser.',
                'code'    => 404,

            ], 404 );

        // Crea el permiso en la BD
        $permission = $this->model::create( ['name' => $request->name, 'guard_name' => 'api'] );

        // Asigna el permiso creado al rol SuperUser
        $role->givePermissionTo( $permission );

        if( $permission )

            // Retorna una respuesta 201 (Created)
            return response( )->json([

                'success' => true,
                'message' => 'Se ha creado el permiso con éxito.',
                'code'    => 201,

            ], 201 );
    }

    /**
     * Asignar un permiso a un rol.
     *
     * @return [string] message
     */
    public function assign( Request $request ) {

        // Hace las validaciones de los datos enviados por el request
        $request->validate([

            'role_id'       => 'required|string',
            'permission_id' => 'required|string',
        ]);

        // Obtiene el permiso por ID
        $permission = $this->model::where( 'id', $request->permission_id )->first( );

        // Si no consigue al permiso retorna un error 404 (Not Found)
        if ( ! $permission )

            return response( )->json([

                'success' => false,
                'message' => 'No existe un permiso con el ID enviado.',
                'code'    => 404,

            ], 404 );

        // Obtiene el rol por ID
        $role = Role::where( 'id', $request->role_id )->first( );

        // Si no consigue al rol retorna un error 404 (Not Found)
        if ( ! $role )

            return response( )->json([

                'success' => false,
                'message' => 'No existe un rol con el ID enviado.',
                'code'    => 404,

            ], 404 );

        if( $role && $permission ) {

            // Asigna el rol al usuario
            $role->givePermissionTo( $permission );

            // Retorna una respuesta 200 (OK)
            return response( )->json([

                'success' => true,
                'message' => 'Permiso asignado con éxito.',
                'code'    => 200,

            ], 200 );
        }
    }

    /**
     * Revoca un permiso a un rol.
     *
     * @return [string] message
     */
    public function revoke( Request $request ) {

        // Hace las validaciones de los datos enviados por el request
        $request->validate([

            'role_id'       => 'required|string',
            'permission_id' => 'required|string',
        ]);

        // Obtiene el permiso por ID
        $permission = $this->model::where( 'id', $request->permission_id )->first( );

        // Si no consigue al permiso retorna un error 404 (Not Found)
        if ( ! $permission )

            return response( )->json([

                'success' => false,
                'message' => 'No existe un permiso con el ID enviado.',
                'code'    => 404,

            ], 404 );

        // Obtiene el rol por ID
        $role = Role::where( 'id', $request->role_id )->first( );

        // Si no consigue al rol retorna un error 404 (Not Found)
        if ( ! $role )

            return response( )->json([

                'success' => false,
                'message' => 'No existe un rol con el ID enviado.',
                'code'    => 404,

            ], 404 );

        if( $role && $permission ) {

            // Asigna el rol al usuario
            $role->revokePermissionTo( $permission );

            // Retorna una respuesta 200 (OK)
            return response( )->json([

                'success' => true,
                'message' => 'Permiso revocado con éxito.',
                'code'    => 200,

            ], 200 );
        }
    }

    /**
     * Elimina una instancia del modelo Permission enviada por parámetros a la función.
     *
     * @return [string] message
     */
    public function delete( $id ) {

        // Obtiene el permiso por ID
        $permission = $this->model::where( 'id', $id )->first( );

        // Si no consigue al permiso retorna un error 404 (Not Found)
        if ( ! $permission )

            return response( )->json([

                'success' => false,
                'message' => 'No existe un permiso con el ID enviado.',
                'code'    => 404,

            ], 404 );

        // Elimina el permiso de la BD
        if( $permission->delete( ) )

            // Retorna una respuesta 200 (OK)
            return response( )->json([

                'success' => true,
                'message' => 'Permiso eliminado con éxito.',
                'code'    => 200,

            ], 200 );
    }
}
