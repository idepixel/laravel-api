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

use App\Http\Resources\User as UserResource;
use App\Notifications\EmailActivate as emailActivate;

class UserController extends Controller {

    protected $model;

    /**
     * Crea una nueva instancia del controlador.
     *
     * @return void
     */
    public function __construct(User $model) {

        $this->model = $model;

        // Aplica un Middleware de permisos para cada función
        $this->middleware('permission:delete user')->only('delete');
    }

    /**
     * Crea una nueva instancia del modelo User.
     *
     * @return [string] message
     */
    public function store( Request $request ) {

        // Hace las validaciones de los datos enviados por el request
        $request->validate([

            'name'                  => 'required|string',
            'lastname'              => 'required|string',
            'bio'                   => 'required|string|max:255',
            'email'                 => 'required|email|max:120|unique:users',
            'password'              => 'required|string|min:6|max:60|confirmed',
            'password_confirmation' => 'required|same:password',
        ]);

        // Guarda los datos en el modelo User
        $user = new $this->model;

        $user->uuid        = Uuid::uuid4();
        $user->email       = $request->email;
        $user->password    = Hash::make( $request->password_confirmation );
        $user->email_token = str_random(64);

        // Guarda el modelo User en la BD, si no, retorna un error 400 (Bad Request)
        if( $user->save( ) ) {

            // Guarda los datos en el modelo Profile
            $profile = new Profile;

            $profile->uuid        = Uuid::uuid4();
            $profile->name        = $request->name;
            $profile->lastname    = $request->lastname;
            $profile->bio         = $request->bio;
            $profile->user_id     = $request->user_id;

            $profile->user()->associate( $user );

            // Si se guarda el modelo Profile en la BD lo asocia con el usuario, si no, elimina al usuario creado y retorna un error 400 (Bad Request)
            if( ! $profile->save( ) ) {

                // Elimina al usuario creado
                $user->delete( );

                return response( )->json([

                    'success' => false,
                    'message' => 'No se ha podido crear el perfil de usuario.',
                    'code'    => 400,

                ], 400 );
            }

            // Asigna el Role de usuario al nuevo usuario registrado
            $role = Role::where('name','User')->first( );
            $user->assignRole($role);

            // Crea el avatar del usuario y lo guarda en la carpeta public
            $avatar = Avatar::create( $profile->name .' '. $profile->lastname )->getImageObject( )->encode( 'png' );
            Storage::put( 'assets/images/avatars/' . $profile->uuid . '/avatar.png', ( string ) $avatar );

            // Envía el correo de validación de usuario
            $user->notify( new emailActivate( $user ) );

            // Retorna una respuesta 201 (Created)
            return response( )->json([

                'success' => true,
                'message' => 'Usuario creado con éxito.',
                'code'    => 201,

            ], 201 );

        } else return response( )->json([

                'success' => false,
                'message' => 'No se ha podido crear el usuario.',
                'code'    => 400,

            ], 400 );
    }

    /**
     * Elimina una instancia del modelo User enviada por parámetros a la función.
     *
     * @return [string] message
     */
    public function delete( Request $request, $uuid ) {

        // Obtiene al usuario que envía la solicitud
        $currentUser = $request->user();

        // Obtiene al usuario a eliminar por UUID
        $user = $this->model::where( 'uuid', $uuid )->first( );

        // Si no consigue al usuario retorna un error 404 (Not Found)
        if ( ! $user )

            return response( )->json([

                'success' => false,
                'message' => 'No existe un usuario con el UUID enviado.',
                'code'    => 404,

            ], 404 );

        // Valida que el usuario que envía la solicitud no sea el mismo que está eliminando
        if( $currentUser->id != $user->id ) {

            // Elimina primero el perfil de usuario y luego al usuario
            $user->profile->delete( );
            $user->delete( );

            // Retorna una respuesta 200 (Ok)
            return response( )->json([

                'success' => true,
                'message' => 'Usuario eliminado con éxito.',
                'code'    => 200,

            ], 200 );
        }

        // Si el usuario que envía la solicitud es igual que es está eliminando retorna un error 409 (Conflict)
        return response( )->json([

            'success' => false,
            'message' => 'No puedes eliminar tu propia cuenta de usuario.',
            'code'    => 409,

        ], 409 );
    }
}
