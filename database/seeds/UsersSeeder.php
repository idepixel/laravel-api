<?php

/**
 *  @package        laravel-api.database.seeds
 *
 *  @author         Daniel Rodríguez | idepixel (idepixel@gmail.com).
 *  @copyright      idepixel 2018 - Todos los derechos reservados.
 *
 *  @since          Versión 1.0, revisión 22/12/2018.
 *  @version        1.0
 *
 *  @final
 */

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Profile;

class UsersSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        $user = new User;

        $user->uuid              = Uuid::uuid4();
        $user->email             = 'usuario@idepixel.com';
        $user->password          = bcrypt('idepixel');
        $user->verified          = true;
        $user->email_token       = false;
        $user->email_verified_at = Carbon::now();

        if( ! $user->save() )

            return false;

        else {

            $profile = new Profile;

            $profile->uuid      = Uuid::uuid4();
            $profile->name      = 'Daniel';
            $profile->lastname  = 'Rodríguez';
            $profile->bio       = '30 años, estudiante y programador con más de 10 años de experiencia en el desarrollo de software a medida, principalmente en desarrollo web.';

            $profile->user()->associate( $user );

            $profile->save();
        }
    }
}
