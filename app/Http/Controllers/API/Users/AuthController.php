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

use Illuminate\Support\Facades\Auth;

use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;

use App\Models\User;
use App\Models\PasswordReset;

use DB;
use Hash;

class AuthController extends Controller {

    /**
     * Inicia sesión de usuario mediante generación de Token de autenticación.
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [json] acces_token array
     */
    public function login( Request $request ) {

        // Hace las validaciones de los datos enviados por el request desde el formulario
        $request->validate([
            'email'       => 'required|string|email',
            'password'    => 'required|string',
            'remember_me' => 'boolean',
        ]);

        // Obtiene el usuario por el email
        $user = User::where( 'email', $request->email )->first( );

        if( $user ) {

            // Guarda las credenciales en una variable
            $credentials = request(['email', 'password']);
            $credentials['deleted_at'] = null;
            $credentials['verified'] = true;

            // Verifica que las credenciales sean correctas
            if ( ! Auth::attempt( $credentials ) )

                return response( )->json([

                    'success' => false,
                    'message' => 'No autorizado.',
                    'code'    => 401,

                ], 401 );

            if( $user->verified == false)

                return response( )->json([

                    'success' => false,
                    'message' => 'Su correo electrónico aún no ha sido validado, por favor verifique la bandeja de entrada de su dirección de correo.',
                    'code'    => 401,

                ], 401 );

            // Genera el token de autorización
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;

            // Si se habilita la casilla "recuérdame" el Token expira en una semana desde su fecha de creación, caso contrario expira en un día
            if ( $request->remember_me )

                $token->expires_at = Carbon::now()->addWeeks(1);

            else

                $token->expires_at = Carbon::now()->addDays(1);

            // Guarda el Token en la BD
            if( $token->save() )

                // Retorna una respuesta 201 (Created)
                return response()->json([

                    'success'      => true,
                    'message'      => 'Se ha creado el Token de Acceso Personal satisfactoriamente.',
                    'access_token' => $tokenResult->accessToken,
                    'token_type'   => 'Bearer',
                    'expires_at'   => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString(),
                    'code'         => 201,

                ], 201);

        } else return response( )->json([

            'success' => false,
            'message' => 'No existe un usuario con el email enviado.',
            'code'    => 404,

        ], 404 ); // Si no consigue al usuario retorna un error 404 (Not Found)
    }

    /**
     * Cierra sesión de usuario eliminando el Token de autenticación.
     *
     * @return [string] message
     */
    public function logout( Request $request ) {

        // Verifica que el Token exista en la BD
        if ( ! DB::table( 'oauth_access_tokens' )->where( 'id', $request->user( )->token( )->id )->exists( ) )

            return response( )->json([

                'success' => false,
                'message' => 'No se encontró ninguna sesión de usuario activa.',
                'code'     => 404,

            ], 404 ); // Si no hay una sesión activa del usuario retorna un error 404 (Not Found)

        // Elimina el Token de acceso al sistema del usuario
        if( $request->user( )->token( )->delete( ) )

            // Retorna una respuesta 200 (Ok)
            return response( )->json([

                'success' => true,
                'message' => 'Se ha cerrado la sesión de usuario.',
                'code'    => 200,

            ], 200 );
    }

    /**
     * Valida la dirección de correo del usuario mediante email_token.
     *
     * @return [string] message
     */
    public function activate( $token ) {

        // Obtiene el usuario por el Token
        $user = User::where( 'email_token', $token )->first( );

        // Si no consigue al usuario retorna un error 404 (Not Found)
        if ( ! $user )

            return response( )->json([

                'success' => false,
                'message' => 'No existe un usuario con el Token enviado.',
                'code'    => 404,

            ], 404 );

        // Guarda los datos en el modelo User para activarlo
        $user->verified = true;
        $user->email_token = '0';
        $user->email_verified_at = Carbon::now();

        // Guarda los datos en la BD
        if( $user->save() )

            // Retorna una respuesta 200 (Ok)
            return response( )->json([

                'success' => true,
                'message' => 'Su dirección de correo ha sido validada con éxito.',
                'code'    => 200,

            ], 200 );
    }

    /**
     * Crea el token_password_reset
     *
     * @param  [string] email
     * @return [string] message
     */
    public function pass_create( Request $request ) {

        // Hace las validaciones de los datos enviados por el request desde el formulario
        $request->validate(['email' => 'required|string|email']);

        // Obtiene el usuario por el email
        $user = User::where( 'email', $request->email )->first( );

        if ( ! $user )

            // Si no consigue al usuario retorna un error 404 (Not Found)
            return response( )->json([

                'success' => false,
                'message' => 'No existe un usuario con el email enviado.',
                'code'    => 404,

            ], 404 );

        // Obtiene el PasswordReset por el email
        $passwordReset = PasswordReset::where( 'email', $user->email )->first( );

        // Si no existe el PasswordReset crea uno nuevo
        if( ! $passwordReset) {

            $passwordReset = new PasswordReset;

            $passwordReset->uuid  = Uuid::uuid4();
            $passwordReset->email = $user->email;
        }

        // Genera y guarda el Token en el modelo PasswordReset
        $passwordReset->token = str_random(64);

        // Guarda los datos en la BD
        if ( $passwordReset->save( ) ) {

            // Envía por correo la URL para reiniciar la contraseña al usuario
            $user->notify(new PasswordResetRequest( $passwordReset ));

            // Retorna una respuesta 200 (Ok)
            return response( )->json([

                'success' => true,
                'message' => '¡Hemos enviado por correo electrónico el enlace para restablecer su contraseña!',
                'code'    => 200,

            ], 200 );
        }
    }

    /**
     * Obtiene el token_password_reset
     *
     * @param  [string] $token
     * @return [string] message
     * @return [json] passwordReset object
     */
    public function pass_find( $token ) {

        // Obtiene el PasswordReset por el Token
        $passwordReset = PasswordReset::where( 'token', $token )->first( );

        if ( ! $passwordReset )

            // Si no consigue el PasswordReset retorna un error 404 (Not Found)
            return response( )->json([

                'success' => false,
                'message' => 'No existe el Token.',
                'code'    => 404,

            ], 404 );

        // Verifica que el Token no tenga más de 120 minutos creado, de ser así lo elimina
        if ( Carbon::parse( $passwordReset->updated_at )->addMinutes( 120 )->isPast( ) ) {

            // Elimina el PasswordReset
            $passwordReset->delete();

            // Retorna un error 404 (Not Found)
            return response( )->json([

                'success' => false,
                'message' => 'El Token ha expirado.',
                'code'    => 404,

            ], 404 );
        }

        // Retorna un nuevo recurso de PasswordReset
        return response( )->json([

            'success' => true,
            'passwordReset' => $passwordReset,

        ], 200 );
    }

    /**
     * Reinicia la contraseña
     *
     * @param  [string] password
     * @param  [string] password_confirmation
     * @param  [string] token
     * @return [string] message
     */
    public function pass_reset( Request $request ) {

        // Hace las validaciones de los datos enviados por el request desde el formulario
        $request->validate([

            'password'              => 'required|string|min:6|max:60|confirmed',
            'password_confirmation' => 'required|same:password',
            'token'                 => 'required|string|max:64',
        ]);

        // Obtiene el PasswordReset por el Token
        $passwordReset = PasswordReset::where( 'token', $request->token )->first( );

        if ( ! $passwordReset )

            // Si no consigue el PasswordReset retorna un error 404 (Not Found)
            return response( )->json([

                'success' => false,
                'message' => 'No existe el Token.',
                'code'    => 404,

            ], 404 );

        // Verifica que el Token no tenga más de 120 minutos creado, de ser así lo elimina
        if ( Carbon::parse( $passwordReset->updated_at )->addMinutes( 120 )->isPast( ) ) {

            // Elimina el PasswordReset
            $passwordReset->delete();

            // Retorna un error 404 (Not Found)
            return response( )->json([

                'success' => false,
                'message' => 'El Token ha expirado.',
                'code'    => 404,

            ], 404 );
        }

        // Obtiene el usuario por el email
        $user = User::where( 'email', $passwordReset->email )->first( );

        // Si no consigue al usuario retorna un error 404 (Not Found)
        if ( ! $user )

            return response( )->json([

                'success' => false,
                'message' => 'No existe un usuario con el email enviado.',
                'code'    => 404,

            ], 404 );

        // Crea el password del usuario
        $user->password = Hash::make( $request->password );

        // Guarda los datos en la BD
        if( $user->save( ) ) {

            // Elimina el PasswordReset de la BD
            $passwordReset->delete( );

            // Envía un correo al usuario notificando que su contraseña ha sido cambiada con éxito
            $user->notify( new PasswordResetSuccess( $passwordReset ) );

            // Retorna una respuesta 200 (Ok)
            return response( )->json([

                'success' => true,
                'message' => '¡Se ha cambiado la contraseña satisfactortimente!',
                'code'    => 200,

            ], 200 );
        }
    }
}
