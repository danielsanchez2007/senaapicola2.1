<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Modules\SICA\Entities\App;
use Modules\SICA\Entities\EPS;
use Modules\SICA\Entities\Person;
use Modules\SICA\Entities\PensionEntity;
use Modules\SICA\Entities\PopulationGroup;
use Modules\SICA\Entities\Role;

class PublicRegistrationController extends Controller
{
    public function create()
    {
        return view('auth.register-senaapicola');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nickname' => ['required', 'string', 'max:255', 'unique:users,nickname'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'document_type' => ['required', Rule::in([
                'Cédula de ciudadanía',
                'Tarjeta de identidad',
                'Cédula de extranjería',
                'Pasaporte',
                'Documento nacional de identidad',
                'Registro civil',
                'Número de Identificación Tributaria',
            ])],
            'document_number' => ['required', 'regex:/^[0-9]{5,15}$/', Rule::unique('people', 'document_number')],
            'first_name' => ['required', 'string', 'max:120'],
            'first_last_name' => ['required', 'string', 'max:120'],
            'second_last_name' => ['nullable', 'string', 'max:120'],
        ]);

        $epsId = EPS::query()->value('id');
        $populationGroupId = PopulationGroup::query()->value('id');
        $pensionEntityId = PensionEntity::query()->value('id');

        if (! $epsId || ! $populationGroupId || ! $pensionEntityId) {
            return back()
                ->withInput()
                ->withErrors(['email' => 'No hay datos base (EPS, grupo poblacional o pensión) en la base de datos. Ejecute los seeders del sistema.']);
        }

        $person = Person::create([
            'document_type' => $validated['document_type'],
            'document_number' => (int) $validated['document_number'],
            'first_name' => mb_strtoupper($validated['first_name']),
            'first_last_name' => mb_strtoupper($validated['first_last_name']),
            'second_last_name' => $validated['second_last_name'] ? mb_strtoupper($validated['second_last_name']) : null,
            'blood_type' => 'No registra',
            'gender' => 'No registra',
            'eps_id' => $epsId,
            'population_group_id' => $populationGroupId,
            'pension_entity_id' => $pensionEntityId,
            'personal_email' => $validated['email'],
        ]);

        $user = User::create([
            'nickname' => $validated['nickname'],
            'person_id' => $person->id,
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $role = Role::where('slug', 'senaapicola.usuarioinfo')->first();
        if (! $role) {
            $app = App::where('name', 'SENAAPICOLA')->firstOrFail();
            $role = Role::create([
                'name' => 'Usuario informativo',
                'slug' => 'senaapicola.usuarioinfo',
                'description' => 'Acceso solo al contenido informativo de SENA APICOLA',
                'description_english' => 'Read-only informational access to SENA APICOLA',
                'full_access' => 'No',
                'app_id' => $app->id,
            ]);
        }

        $user->roles()->sync([$role->id]);

        return redirect()
            ->route('login')
            ->with('status', 'Registro exitoso. Inicia sesión para ver el contenido informativo sobre apicultura.');
    }
}
