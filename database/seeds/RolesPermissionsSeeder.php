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

use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesPermissionsSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        $data = [ 'permissions' => [

            // Usuarios
            ['name' => 'delete user'],

            // Perfiles
            ['name' => 'list profiles'],
            ['name' => 'show profile'],
            ['name' => 'update profile'],
            ['name' => 'password profile'],

            // Roles y permisos
            ['name' => 'list roles'],
            ['name' => 'create role'],
            ['name' => 'assign role'],
            ['name' => 'revoke role'],
            ['name' => 'delete role'],

            ['name' => 'list permissions'],
            ['name' => 'create permission'],
            ['name' => 'assign permission'],
            ['name' => 'revoke permission'],
            ['name' => 'delete permission'],
        ]];

        // Lista de permisos precargados

        foreach($data['permissions'] as $permission) {

            Permission::create(['name' => $permission['name'], 'guard_name' => 'api']);
        }

        // Permisos de super usuario

        $su = Role::create(['name' => 'SuperUser', 'guard_name' => 'api']);

        $su->givePermissionTo( Permission::all( ) );

        // Permisos de administrador

        $admin = Role::create(['name' => 'Administrator', 'guard_name' => 'api']);

        $admin->givePermissionTo([

            'delete user',

            'list profiles',
            'show profile',
            'update profile',
            'password profile',
        ]);

        // Permisos de invitado

        $user = Role::create(['name' => 'User', 'guard_name' => 'api']);

        $user->givePermissionTo([

            'show profile',
            'update profile',
            'password profile',
        ]);

        // Asigna role de SuperUsuario al usuario

        $user = User::find(1);

        $user->assignRole( $su );
    }
}
