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

class PasswordReset extends Model {

    /**
     * La tabla asociada con el modelo.
     *
     * @var string
     */

    protected $table = "password_resets";

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

    protected $fillable = ['email', 'token'];
}
