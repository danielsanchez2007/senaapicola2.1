<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tablas necesarias para login, personas, roles/permisos (SICA mínimo) y SENA APICOLA.
     * El resto se elimina (módulos retirados del proyecto).
     */
    private function tablesToKeep(): array
    {
        return [
            'migrations',
            // Laravel / paquetes
            'password_reset_tokens',
            'password_resets',
            'sessions',
            'cache',
            'cache_locks',
            'jobs',
            'job_batches',
            'failed_jobs',
            'personal_access_tokens',
            // Auditoría
            'audits',
            // Núcleo SICA usado por la app
            'apps',
            'roles',
            'permissions',
            'permission_role',
            'role_user',
            'users',
            'people',
            'e_p_s',
            'population_groups',
            'pension_entities',
            // SENA APICOLA
            'apiaries',
            'hives',
            'production_monitorings',
            'apiary_monitorings',
            'apiary_activities',
        ];
    }

    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();
        if ($driver !== 'mysql' && $driver !== 'mariadb') {
            return;
        }

        $database = DB::getDatabaseName();
        $key = 'Tables_in_'.$database;

        $rows = DB::select('SHOW FULL TABLES WHERE Table_type = \'BASE TABLE\'');
        $keep = array_flip($this->tablesToKeep());

        $toDrop = [];
        foreach ($rows as $row) {
            $name = $row->{$key} ?? null;
            if ($name === null || isset($keep[$name])) {
                continue;
            }
            $toDrop[] = $name;
        }

        if ($toDrop === []) {
            return;
        }

        Schema::disableForeignKeyConstraints();
        try {
            foreach ($toDrop as $table) {
                Schema::dropIfExists($table);
            }
        } finally {
            Schema::enableForeignKeyConstraints();
        }
    }

    public function down(): void
    {
        // Irreversible: no se recrean decenas de tablas de módulos eliminados.
    }
};
