<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {

            $table->engine = 'InnoDB';

            $table->charset = 'utf8mb4';

            $table->collation = 'utf8mb4_unicode_ci';

            $table->increments('id');

            $table->uuid('uuid')->index()->unique();

            $table->string('bio')
                  ->comment('Bio del usuario.');

            $table->string('name')
                  ->comment('Nombre del usuario.');

            $table->string('lastname')
                  ->comment('Apellido del usuario.');

            $table->string('avatar')
                  ->default('avatar.png')
                  ->comment('Avatar del usuario.');

            $table->integer('user_id')->unsigned()->comment('ID del usuario.');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profiles');
    }
}
