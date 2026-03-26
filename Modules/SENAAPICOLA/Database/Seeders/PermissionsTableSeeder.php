<?php

namespace Modules\SENAAPICOLA\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\SICA\Entities\App;
use Modules\SICA\Entities\Permission;
use Modules\SICA\Entities\Role;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
{
    $app = App::where('name', 'SENAAPICOLA')->first();

    $permissions_admin = [];
    $permissions_intern = [];

    $permission = Permission::updateOrCreate(['slug' => 'senaapicola.admin.index'], [
        'name' => 'Vista de configuración (Administrador)',
        'description' => 'Configuración de parametros generales y testeo de impresión pos',
        'description_english' => 'Configuration of general parameters and post printing test',
        'app_id' => $app->id
    ]);
    $permissions_admin[] = $permission->id;

    $permission = Permission::updateOrCreate(['slug' => 'senaapicola.admin.welcome'], [
        'name' => 'Vista de configuración (Administrador)',
        'description' => 'Configuración de parametros generales y testeo de impresión pos',
        'description_english' => 'Configuration of general parameters and post printing test',
        'app_id' => $app->id
    ]);
    $permissions_admin[] = $permission->id;

    $permission = Permission::updateOrCreate(['slug' => 'senaapicola.intern.panelpas'], [
        'name' => 'Vista de configuración (Pasante)',
        'description' => 'Configuración de parametros generales y testeo de impresión pos',
        'description_english' => 'Configuration of general parameters and post printing test',
        'app_id' => $app->id
    ]);
    $permissions_admin[] = $permission->id; // Opcional
    $permissions_intern[] = $permission->id;

    $rol_admin = Role::where('slug', 'senaapicola.admin')->first();
    $rol_intern = Role::where('slug', 'senaapicola.intern')->first();

    $rol_admin->permissions()->syncWithoutDetaching($permissions_admin);
    $rol_intern->permissions()->syncWithoutDetaching($permissions_intern);
}

}
