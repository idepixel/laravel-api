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

use Illuminate\Database\Eloquent\Model;

use App\Models\User;

use Storage;

class Profile extends Model {

    protected $appends = ['avatar_url'];

    /**
     * La tabla asociada con el modelo.
     *
     * @var string
     */
    protected $table = "profiles";

    /**
     * Los atributos que deben mutarse a las fechas.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are not mass assignable.
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

        'bio',
        'name',
        'lastname',
        'avatar',
        'user_id',
    ];

    /**
     * Los atributos que deberían estar ocultos para las matrices.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Retorna el usuario.
     *
     * @return type User
     */
    public function user( ) {

        return $this->belongsTo( User::class );
    }

    /**
     * Retorna la ruta donde se almacena el Avatar del perfil.
     *
     * @return type User
     */
    public function getAvatarUrlAttribute( ) {

        return Storage::url( 'assets/images/avatars/' . $this->uuid . '/' . $this->avatar );
    }
}
