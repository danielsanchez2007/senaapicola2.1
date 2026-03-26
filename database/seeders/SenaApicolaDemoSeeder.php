<?php

namespace Database\Seeders;

use App\Models\Apiary;
use App\Models\ApiaryMonitoring;
use App\Models\Hive;
use App\Models\ProductionMonitoring;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\SICA\Entities\EPS;
use Modules\SICA\Entities\PensionEntity;
use Modules\SICA\Entities\Person;
use Modules\SICA\Entities\PopulationGroup;
use Modules\SICA\Entities\Role;

/**
 * Datos de demostración: apiarios con coordenadas e imágenes (Wikimedia),
 * colmenas, producciones y seguimientos, y 5 usuarios pasantes.
 *
 * Contraseña de los pasantes demo: Pasante2025!
 */
class SenaApicolaDemoSeeder extends Seeder
{
    public function run(): void
    {
        if (! \Illuminate\Support\Facades\Schema::hasTable('apiaries')) {
            $this->command->warn('Tabla apiaries no existe. Omitiendo SenaApicolaDemoSeeder.');

            return;
        }

        $epsId = EPS::query()->value('id');
        $populationGroupId = PopulationGroup::query()->value('id');
        $pensionEntityId = PensionEntity::query()->value('id');
        if (! $epsId || ! $populationGroupId || ! $pensionEntityId) {
            $this->command->error('Faltan registros base en EPS, población o pensión. Ejecute primero los seeders SICA necesarios.');

            return;
        }

        $internRole = Role::where('slug', 'senaapicola.intern')->first();
        if (! $internRole) {
            $this->command->error('No existe el rol senaapicola.intern.');

            return;
        }

        if (User::where('email', 'pasante-demo-1@senaapicola.local')->exists()) {
            $this->command->info('SenaApicola demo ya está cargado (usuarios demo existen). Solo se actualizarán apiarios si faltan coordenadas.');

            $this->ensureApiaryMapData();

            return;
        }

        $apiaryDefs = [
            [
                'name' => 'Apiario Pedagógico Zipaquirá',
                'location' => 'Municipio de Zipaquirá, Cundinamarca',
                'latitude' => 5.0225,
                'longitude' => -74.0048,
                'image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/4d/Apis_melifica_Western_honey_bee.jpg/480px-Apis_melifica_Western_honey_bee.jpg',
            ],
            [
                'name' => 'Apiario El Roble Chía',
                'location' => 'Chía, vía principal vereda El Roble',
                'latitude' => 4.8615,
                'longitude' => -74.0584,
                'image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/26/Bees_Collecting_Pollen_2004-08-14.jpg/480px-Bees_Collecting_Pollen_2004-08-14.jpg',
            ],
            [
                'name' => 'Apiario La Pradera Mosquera',
                'location' => 'Mosquera, predio La Pradera',
                'latitude' => 4.7061,
                'longitude' => -74.2302,
                'image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/cd/Western_honey_bee_on_thistle.jpg/480px-Western_honey_bee_on_thistle.jpg',
            ],
            [
                'name' => 'Apiario Vereda El Triunfo',
                'location' => 'La Mesa, vereda El Triunfo',
                'latitude' => 4.6309,
                'longitude' => -74.4642,
                'image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/70/Apis_mellifera_scutellata_1.jpg/480px-Apis_mellifera_scutellata_1.jpg',
            ],
            [
                'name' => 'Apiario Fusagasugá Centro',
                'location' => 'Fusagasugá, zona rural oriente',
                'latitude' => 4.3367,
                'longitude' => -74.3638,
                'image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/ba/Honeybee_landing_on_milkthistle02.jpg/480px-Honeybee_landing_on_milkthistle02.jpg',
            ],
            [
                'name' => 'Apiario Subachoque SENA',
                'location' => 'Subachoque, lote demostrativo',
                'latitude' => 4.9297,
                'longitude' => -74.1728,
                'image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/f3/Bees_iliate_01.jpg/480px-Bees_iliate_01.jpg',
            ],
        ];

        $apiaries = [];
        foreach ($apiaryDefs as $def) {
            $apiaries[] = Apiary::updateOrCreate(
                ['name' => $def['name']],
                $def
            );
        }

        $hiveNames = ['Alfa', 'Beta', 'Gamma', 'Delta', 'Épsilon'];
        $h = 0;
        foreach ($apiaries as $apiary) {
            for ($i = 0; $i < 3; $i++) {
                $label = $hiveNames[$h % count($hiveNames)] . ' · Colmena ' . ($i + 1);
                $name = 'C' . $apiary->id . '-' . ($i + 1) . ' ' . $label;
                Hive::firstOrCreate(
                    ['apiary_id' => $apiary->id, 'name' => $name]
                );
                $h++;
            }
        }

        $products = ['Miel multifloral 375 ml', 'Propóleo', 'Polen', 'Miel de café', 'Cera estampada'];
        foreach ($apiaries as $idx => $apiary) {
            for ($m = 0; $m < 4; $m++) {
                $date = Carbon::now()->subMonths(5 - $m)->subDays($idx * 3);
                ProductionMonitoring::create([
                    'date' => $date->format('Y-m-d'),
                    'apiary_id' => $apiary->id,
                    'quantity' => random_int(12, 120),
                    'product' => $products[($idx + $m) % count($products)],
                    'action' => $m % 3 === 0 ? 'exit' : 'entry',
                ]);
            }
        }

        $pasantes = [
            ['Laura', 'MÉNDEZ', 'ORTIZ', '5089900001', 'pdemo1'],
            ['Andrés', 'PARRA', 'CASTRO', '5089900002', 'pdemo2'],
            ['Camila', 'ROJAS', 'NIETO', '5089900003', 'pdemo3'],
            ['Diego', 'HERRERA', 'VARGAS', '5089900004', 'pdemo4'],
            ['Valentina', 'SOTO', 'MORENO', '5089900005', 'pdemo5'],
        ];

        $pwd = Hash::make('Pasante2025!');
        foreach ($pasantes as $index => $row) {
            [$fn, $ln1, $ln2, $doc, $nick] = $row;
            $person = Person::create([
                'document_type' => 'Tarjeta de identidad',
                'document_number' => (int) $doc,
                'first_name' => $fn,
                'first_last_name' => $ln1,
                'second_last_name' => $ln2,
                'date_of_birth' => Carbon::now()->subYears(19 + $index)->format('Y-m-d'),
                'personal_email' => 'pasante-demo-' . ($index + 1) . '@correo.demo',
                'address' => 'Calle ' . (40 + $index) . ' # 12-34, Cundinamarca',
                'telephone1' => 3102000000 + $index,
                'eps_id' => $epsId,
                'population_group_id' => $populationGroupId,
                'pension_entity_id' => $pensionEntityId,
            ]);

            $user = User::create([
                'nickname' => $nick,
                'person_id' => $person->id,
                'email' => 'pasante-demo-' . ($index + 1) . '@senaapicola.local',
                'password' => $pwd,
            ]);
            $user->roles()->sync([$internRole->id]);
        }

        foreach ($apiaries as $apiary) {
            ApiaryMonitoring::create([
                'date' => Carbon::now()->subWeeks(2)->format('Y-m-d'),
                'apiary_id' => $apiary->id,
                'user_nickname' => 'pdemo1',
                'role' => 'Pasante',
                'description' => 'Revisión sanitaria y registro de fuerza de colonia.',
            ]);
        }

        $this->command->info('SenaApicola demo: apiarios, colmenas, producciones, seguimientos y 5 pasantes creados.');
        $this->command->info('Login pasantes demo: pasante-demo-1@senaapicola.local … pasante-demo-5@senaapicola.local | Contraseña: Pasante2025!');
    }

    protected function ensureApiaryMapData(): void
    {
        $defs = [
            ['name' => 'Apiario Pedagógico Zipaquirá', 'location' => 'Municipio de Zipaquirá, Cundinamarca', 'latitude' => 5.0225, 'longitude' => -74.0048, 'image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/4d/Apis_melifica_Western_honey_bee.jpg/480px-Apis_melifica_Western_honey_bee.jpg'],
            ['name' => 'Apiario El Roble Chía', 'location' => 'Chía, vía principal vereda El Roble', 'latitude' => 4.8615, 'longitude' => -74.0584, 'image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/26/Bees_Collecting_Pollen_2004-08-14.jpg/480px-Bees_Collecting_Pollen_2004-08-14.jpg'],
            ['name' => 'Apiario La Pradera Mosquera', 'location' => 'Mosquera, predio La Pradera', 'latitude' => 4.7061, 'longitude' => -74.2302, 'image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/cd/Western_honey_bee_on_thistle.jpg/480px-Western_honey_bee_on_thistle.jpg'],
            ['name' => 'Apiario Vereda El Triunfo', 'location' => 'La Mesa, vereda El Triunfo', 'latitude' => 4.6309, 'longitude' => -74.4642, 'image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/70/Apis_mellifera_scutellata_1.jpg/480px-Apis_mellifera_scutellata_1.jpg'],
            ['name' => 'Apiario Fusagasugá Centro', 'location' => 'Fusagasugá, zona rural oriente', 'latitude' => 4.3367, 'longitude' => -74.3638, 'image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/ba/Honeybee_landing_on_milkthistle02.jpg/480px-Honeybee_landing_on_milkthistle02.jpg'],
            ['name' => 'Apiario Subachoque SENA', 'location' => 'Subachoque, lote demostrativo', 'latitude' => 4.9297, 'longitude' => -74.1728, 'image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/f3/Bees_iliate_01.jpg/480px-Bees_iliate_01.jpg'],
        ];
        foreach ($defs as $def) {
            Apiary::updateOrCreate(['name' => $def['name']], $def);
        }
    }
}
