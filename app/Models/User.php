<?php

/**
 *  @package        laravel-api.app.Models
 *
 *  @author         Daniel Rodríguez | idepixel (idepixel@gmail.com).
 *  @copyright      idepixel (c) 2018 - Todos los derechos reservados.
 *
 *  @since          Versión 1.0, revisión 22/12/2018.
 *  @version        1.0
 *
 *  @final
 */

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Models\Profile;

class User extends Authenticatable implements MustVerifyEmail {

    use HasRoles, HasApiTokens, Notifiable, SoftDeletes;

    protected $guard_name = 'api';

    /**
     * La tabla que se asocia al modelo User.
     *
     * @var string
     */
    protected $table = "users";

    /**
     * Los atributos que deben mutarse a las fechas.
     *
     * @var array
     */
    protected $dates = ['email_verified_at','deleted_at'];

    /**
     * Los atributos que no son asignados en masa.
     *
     * @var array
     */
    protected $guarded = ['id','uuid'];

    /**
     * Los atributos que son asignados en masa.
     *
     * @var array
     */
    protected $fillable = [

        'email',
        'verified',
    ];

    /**
     * Los atributos que deberían estar ocultos para las matrices.
     *
     * @var array
     */
    protected $hidden = [

        'password',
        'email_token',
        'remember_token',
    ];

    /**
     * Retorna el perfil del usuario.
     *
     * @return type Profile
     */
    public function profile( ) {

        return $this->hasOne( Profile::class );
    }
}
