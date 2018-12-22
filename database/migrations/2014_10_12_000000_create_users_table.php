<?php

/**
 *  @package        laravel-api.database.migrations
 *
 *  @author         Daniel Rodríguez | idepixel (idepixel@gmail.com).
 *  @copyright      idepixel (c) 2018 - Todos los derechos reservados.
 *
 *  @since          Versión 1.0, revisión 22/12/2018.
 *  @version        1.0
 *
 *  @final
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {

            $table->engine = 'InnoDB';

            $table->charset = 'utf8mb4';

            $table->collation = 'utf8mb4_unicode_ci';

            $table->increments('id');

            $table->uuid('uuid')->index()->unique();

            $table->string('email', 100)
                  ->comment('Dirección de correo del usuario.')
                  ->unique();

            $table->string('password', 60)
                  ->comment('Contraseña del usuario.');

            $table->string('email_token', 64)
                  ->comment('Token generado para validar el correo del usuario.')
                  ->nullable();

            $table->boolean('verified')
                  ->default(false)
                  ->comment('Booleano que indica si el correo del usuario está validado o no.');

            $table->timestamp('email_verified_at')->nullable();

            $table->rememberToken();

            $table->timestamps();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
