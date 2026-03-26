<?php

namespace Modules\SENAAPICOLA\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Apiary;
use App\Models\ApiaryMonitoring;
use App\Models\Hive;
use App\Models\ProductionMonitoring;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Modules\SICA\Entities\Person;
use Modules\SICA\Entities\Role;

class ModuleController extends Controller
{
    public function adminHome()
    {
        return view('senaapicola::welcome', [
            'apiaryMapMarkers' => $this->apiaryMapMarkers(),
        ]);
    }

    public function internHome()
    {
        return view('senaapicola::panelpas', [
            'apiaryMapMarkers' => $this->apiaryMapMarkers(),
        ]);
    }

    /**
     * @return \Illuminate\Support\Collection<int, array<string, mixed>>
     */
    protected function apiaryMapMarkers()
    {
        // Agregamos métricas para mostrar en el panel lateral del mapa
        $hiveCounts = Hive::query()
            ->selectRaw('apiary_id, COUNT(*) as cnt')
            ->groupBy('apiary_id')
            ->pluck('cnt', 'apiary_id');

        $prodAgg = ProductionMonitoring::query()
            ->selectRaw("
                apiary_id,
                SUM(quantity) as total_qty,
                SUM(CASE WHEN action = 'entry' THEN quantity ELSE 0 END) as entry_qty,
                SUM(CASE WHEN action = 'exit' THEN quantity ELSE 0 END) as exit_qty
            ")
            ->groupBy('apiary_id')
            ->get()
            ->keyBy('apiary_id');

        return Apiary::orderBy('name')->get()->map(function (Apiary $a) use ($hiveCounts, $prodAgg) {
            $hivesCount = (int) ($hiveCounts[$a->id] ?? 0);
            $agg = $prodAgg[$a->id] ?? null;

            $totalQty = (int) ($agg?->total_qty ?? 0);
            $entryQty = (int) ($agg?->entry_qty ?? 0);
            $exitQty = (int) ($agg?->exit_qty ?? 0);
            $netQty = $entryQty - $exitQty;

            return [
                'id' => $a->id,
                'name' => $a->name,
                'location' => $a->location,
                'lat' => $a->latitude !== null ? (float) $a->latitude : null,
                'lng' => $a->longitude !== null ? (float) $a->longitude : null,
                'image_url' => $a->image_url,
                'hives_count' => $hivesCount,
                'productions_total_quantity' => $totalQty,
                'productions_entries_quantity' => $entryQty,
                'productions_exits_quantity' => $exitQty,
                'cosecha_neta_quantity' => $netQty,
            ];
        });
    }

    public function apiariesIndex(Request $request, string $scope)
    {
        $apiaries = Apiary::orderByDesc('id')->paginate(10);
        return view("senaapicola::{$scope}.apiaries.index", [
            'apiaries' => $apiaries,
            'totalApiaries' => Apiary::count(),
            'firstRegistered' => Apiary::orderBy('created_at')->first(),
            'lastRegistered' => Apiary::orderByDesc('created_at')->first(),
        ]);
    }

    public function apiariesSearch()
    {
        return response()->json(
            Apiary::orderBy('name')->get(['id', 'name', 'location', 'latitude', 'longitude', 'image_url'])
        );
    }

    public function apiariesCreate(string $scope)
    {
        return view("senaapicola::{$scope}.apiaries.create");
    }

    public function apiariesStore(Request $request, string $scope)
    {
        $data = $request->validate([
            'name' => 'required|string|max:50',
            'location' => 'required|string|max:100',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'image_url' => 'nullable|string|max:512',
        ]);
        $data['latitude'] = $request->filled('latitude') ? $data['latitude'] : null;
        $data['longitude'] = $request->filled('longitude') ? $data['longitude'] : null;
        $data['image_url'] = $request->filled('image_url') ? trim((string) $data['image_url']) : null;
        Apiary::create($data);
        return redirect()->route("senaapicola.{$scope}.apiaries.index")->with('success', 'Apiario creado correctamente.');
    }

    public function apiariesEdit(int|string $id, string $scope)
    {
        return view("senaapicola::{$scope}.apiaries.edit", ['apiary' => Apiary::findOrFail($id)]);
    }

    public function apiariesUpdate(Request $request, int|string $id, string $scope)
    {
        $apiary = Apiary::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|max:50',
            'location' => 'required|string|max:100',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'image_url' => 'nullable|string|max:512',
        ]);
        $data['latitude'] = $request->filled('latitude') ? $data['latitude'] : null;
        $data['longitude'] = $request->filled('longitude') ? $data['longitude'] : null;
        $data['image_url'] = $request->filled('image_url') ? trim((string) $data['image_url']) : null;
        $apiary->update($data);
        return redirect()->route("senaapicola.{$scope}.apiaries.index")->with('success', 'Apiario actualizado correctamente.');
    }

    public function apiariesDestroy(int|string $id, string $scope)
    {
        Apiary::findOrFail($id)->delete();
        return redirect()->route("senaapicola.{$scope}.apiaries.index")->with('success', 'Apiario eliminado correctamente.');
    }

    public function hivesIndex(Request $request, string $scope)
    {
        $query = Hive::with('apiary')->orderByDesc('id');
        if ($request->filled('apiary_id')) {
            $query->where('apiary_id', $request->integer('apiary_id'));
        }
        $hives = $query->paginate(10);
        return view("senaapicola::{$scope}.hives.index", [
            'hives' => $hives,
            'apiaries' => Apiary::orderBy('name')->get(),
            'totalHives' => Hive::count(),
            'firstHive' => Hive::orderBy('created_at')->first(),
            'lastHive' => Hive::orderByDesc('created_at')->first(),
        ]);
    }

    public function hivesCreate(string $scope)
    {
        return view("senaapicola::{$scope}.hives.create", ['apiaries' => Apiary::orderBy('name')->get()]);
    }

    public function hivesStore(Request $request, string $scope)
    {
        $data = $request->validate([
            'name' => 'required|string|max:50',
            'apiary_id' => 'required|exists:apiaries,id',
        ]);
        Hive::create($data);
        return redirect()->route("senaapicola.{$scope}.hives.index")->with('success', 'Colmena creada correctamente.');
    }

    public function hivesEdit(int|string $id, string $scope)
    {
        return view("senaapicola::{$scope}.hives.edit", [
            'hive' => Hive::findOrFail($id),
            'apiaries' => Apiary::orderBy('name')->get(),
        ]);
    }

    public function hivesUpdate(Request $request, int|string $id, string $scope)
    {
        $hive = Hive::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|max:50',
            'apiary_id' => 'required|exists:apiaries,id',
        ]);
        $hive->update($data);
        return redirect()->route("senaapicola.{$scope}.hives.index")->with('success', 'Colmena actualizada correctamente.');
    }

    public function hivesDestroy(int|string $id, string $scope)
    {
        Hive::findOrFail($id)->delete();
        return redirect()->route("senaapicola.{$scope}.hives.index")->with('success', 'Colmena eliminada correctamente.');
    }

    public function monitoringsIndex(Request $request, string $scope)
    {
        $query = ApiaryMonitoring::with('apiary')->orderByDesc('id');
        if ($request->filled('apiary_id')) {
            $query->where('apiary_id', $request->integer('apiary_id'));
        }
        $monitorings = $query->paginate(10);
        return view("senaapicola::{$scope}.monitorings.index", [
            'monitorings' => $monitorings,
            'apiaries' => Apiary::orderBy('name')->get(),
            'selectedApiary' => $request->integer('apiary_id'),
            'totalMonitorings' => ApiaryMonitoring::count(),
            'firstMonitoring' => ApiaryMonitoring::orderBy('date')->first(),
            'lastMonitoring' => ApiaryMonitoring::orderByDesc('date')->first(),
        ]);
    }

    public function monitoringsCreate(string $scope)
    {
        return view("senaapicola::{$scope}.monitorings.create", [
            'apiaries' => Apiary::orderBy('name')->get(),
            'users' => User::with('roles')->orderBy('nickname')->get(),
        ]);
    }

    public function monitoringsStore(Request $request, string $scope)
    {
        $data = $request->validate([
            'date' => 'required|date',
            'apiary_id' => 'required|exists:apiaries,id',
            'user_nickname' => 'nullable|string|max:100',
            'role' => 'required|string|max:20',
            'description' => 'nullable|string|max:255',
        ]);
        ApiaryMonitoring::create($data);
        return redirect()->route("senaapicola.{$scope}.monitorings.index")->with('success', 'Seguimiento creado correctamente.');
    }

    public function monitoringsEdit(int|string $id, string $scope)
    {
        return view("senaapicola::{$scope}.monitorings.edit", [
            'monitoring' => ApiaryMonitoring::findOrFail($id),
            'apiaries' => Apiary::orderBy('name')->get(),
            'users' => User::with('roles')->orderBy('nickname')->get(),
        ]);
    }

    public function monitoringsUpdate(Request $request, int|string $id, string $scope)
    {
        $monitoring = ApiaryMonitoring::findOrFail($id);
        $data = $request->validate([
            'date' => 'required|date',
            'apiary_id' => 'required|exists:apiaries,id',
            'user_nickname' => 'nullable|string|max:100',
            'role' => 'required|string|max:20',
            'description' => 'nullable|string|max:255',
        ]);
        $monitoring->update($data);
        return redirect()->route("senaapicola.{$scope}.monitorings.index")->with('success', 'Seguimiento actualizado correctamente.');
    }

    public function monitoringsDestroy(int|string $id, string $scope)
    {
        ApiaryMonitoring::findOrFail($id)->delete();
        return redirect()->route("senaapicola.{$scope}.monitorings.index")->with('success', 'Seguimiento eliminado correctamente.');
    }

    public function productionsIndex(Request $request, string $scope)
    {
        $query = ProductionMonitoring::with('apiary')->orderByDesc('id');
        if ($request->filled('apiary_id')) {
            $query->where('apiary_id', $request->integer('apiary_id'));
        }
        $productions = $query->paginate(10);
        return view("senaapicola::{$scope}.productions.index", [
            'productions' => $productions,
            'apiaries' => Apiary::orderBy('name')->get(),
            'total' => ProductionMonitoring::sum('quantity'),
            'firstProduction' => ProductionMonitoring::orderBy('date')->first(),
            'lastProduction' => ProductionMonitoring::orderByDesc('date')->first(),
        ]);
    }

    public function productionsCreate(string $scope)
    {
        return view("senaapicola::{$scope}.productions.create", ['apiaries' => Apiary::orderBy('name')->get()]);
    }

    public function productionsCreateExit(string $scope)
    {
        return view("senaapicola::{$scope}.productions.create_exit");
    }

    public function productionsStore(Request $request, string $scope)
    {
        $data = $request->validate([
            'date' => 'required|date',
            'apiary_id' => 'required|exists:apiaries,id',
            'product' => 'required|string|max:100',
            'quantity' => 'required|integer|min:1',
            'action' => 'required|in:entry,exit',
        ]);
        ProductionMonitoring::create($data);
        return redirect()->route("senaapicola.{$scope}.productions.index")->with('success', 'Produccion registrada correctamente.');
    }

    public function productionsStoreExit(Request $request, string $scope)
    {
        $data = $request->validate([
            'date' => 'required|date',
            'product' => 'required|string|max:100',
            'quantity' => 'required|integer|min:1',
        ]);
        $firstApiary = Apiary::orderBy('id')->first();
        if (!$firstApiary) {
            return back()->withErrors(['apiary_id' => 'Debe crear primero un apiario.'])->withInput();
        }
        ProductionMonitoring::create([
            'date' => $data['date'],
            'apiary_id' => $firstApiary->id,
            'product' => $data['product'],
            'quantity' => $data['quantity'],
            'action' => 'exit',
        ]);
        return redirect()->route("senaapicola.{$scope}.productions.index")->with('success', 'Salida de produccion registrada correctamente.');
    }

    public function productionsEdit(int|string $id, string $scope)
    {
        return view("senaapicola::{$scope}.productions.edit", [
            'production' => ProductionMonitoring::findOrFail($id),
            'apiaries' => Apiary::orderBy('name')->get(),
        ]);
    }

    public function productionsUpdate(Request $request, int|string $id, string $scope)
    {
        $production = ProductionMonitoring::findOrFail($id);
        $data = $request->validate([
            'date' => 'required|date',
            'apiary_id' => 'required|exists:apiaries,id',
            'product' => 'required|string|max:100',
            'quantity' => 'required|integer|min:1',
            'action' => 'required|in:entry,exit',
        ]);
        $production->update($data);
        return redirect()->route("senaapicola.{$scope}.productions.index")->with('success', 'Produccion actualizada correctamente.');
    }

    public function productionsDestroy(int|string $id, string $scope)
    {
        ProductionMonitoring::findOrFail($id)->delete();
        return redirect()->route("senaapicola.{$scope}.productions.index")->with('success', 'Produccion eliminada correctamente.');
    }

    public function usersIndex()
    {
        $users = User::with(['roles', 'person'])->orderByDesc('id')->paginate(10);
        return view('senaapicola::admin.users.index', compact('users'));
    }

    public function usersCreate()
    {
        $usedPersonIds = User::pluck('person_id');
        $person = Person::whereNotIn('id', $usedPersonIds)->orderBy('id')->first();
        $roles = Role::whereIn('slug', ['senaapicola.admin', 'senaapicola.intern'])->orderBy('name')->get();
        return view('senaapicola::admin.users.create', [
            'roles' => $roles,
            'suggestedPersonId' => $person?->id,
        ]);
    }

    public function usersStore(Request $request)
    {
        $data = $request->validate([
            'nickname' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'person_id' => 'required|exists:people,id|unique:users,person_id',
            'role_id' => 'required|exists:roles,id',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'nickname' => $data['nickname'],
            'email' => $data['email'],
            'person_id' => $data['person_id'],
            'password' => Hash::make($data['password']),
        ]);
        $user->roles()->sync([$data['role_id']]);
        return redirect()->route('senaapicola.admin.users.index')->with('success', 'Usuario creado correctamente.');
    }

    public function usersEdit(int|string $id)
    {
        $user = User::with('roles')->findOrFail($id);
        $roles = Role::whereIn('slug', ['senaapicola.admin', 'senaapicola.intern'])->orderBy('name')->get();
        return view('senaapicola::admin.users.edit', compact('user', 'roles'));
    }

    public function usersUpdate(Request $request, int|string $id)
    {
        $user = User::with('roles')->findOrFail($id);
        $data = $request->validate([
            'nickname' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'role_id' => 'required|exists:roles,id',
            'password' => 'nullable|string|min:6|confirmed',
        ]);
        $user->nickname = $data['nickname'];
        $user->email = $data['email'];
        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        $user->save();
        $user->roles()->sync([$data['role_id']]);
        return redirect()->route('senaapicola.admin.users.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function usersDestroy(int|string $id)
    {
        $user = User::findOrFail($id);
        if (auth()->id() === $user->id) {
            return redirect()->route('senaapicola.admin.users.index')->with('error', 'No puedes eliminar tu propio usuario.');
        }
        $user->roles()->detach();
        $user->delete();
        return redirect()->route('senaapicola.admin.users.index')->with('success', 'Usuario eliminado correctamente.');
    }

    public function profileEdit(string $scope)
    {
        $user = auth()->user()->load('person');

        return view('senaapicola::profile.edit', [
            'scope' => $scope,
            'user' => $user,
        ]);
    }

    public function profileUpdate(Request $request, string $scope)
    {
        $user = auth()->user();
        $person = $user->person;
        if (! $person) {
            return redirect()->back()->with('error', 'No hay datos de persona asociados a su usuario.');
        }

        $data = $request->validate([
            'nickname' => 'required|string|max:255|unique:users,nickname,' . $user->id,
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'first_name' => 'required|string|max:255',
            'first_last_name' => 'required|string|max:255',
            'second_last_name' => 'nullable|string|max:255',
            'personal_email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
            'telephone1' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6|confirmed',
            'avatar' => 'nullable|image|max:2048',
        ]);

        $user->nickname = $data['nickname'];
        $user->email = $data['email'];
        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        $user->save();

        $person->first_name = $data['first_name'];
        $person->first_last_name = $data['first_last_name'];
        $person->second_last_name = $data['second_last_name'] ?? null;
        $person->personal_email = $data['personal_email'] ?? null;
        $person->address = $data['address'] ?? null;
        if ($request->filled('telephone1')) {
            $person->telephone1 = (int) preg_replace('/\D/', '', $data['telephone1']);
        } else {
            $person->telephone1 = null;
        }

        if ($request->hasFile('avatar')) {
            if ($person->avatar && ! str_starts_with((string) $person->avatar, 'http')) {
                Storage::disk('public')->delete($person->avatar);
            }
            $person->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        $person->save();

        $profileRoute = $scope === 'admin'
            ? 'senaapicola.admin.profile.edit'
            : 'senaapicola.intern.profile.edit';

        return redirect()->route($profileRoute)->with('success', 'Perfil actualizado correctamente.');
    }

    public function reportView(string $view)
    {
        $generatedByRole = optional(auth()->user()?->roles?->first())->name ?? 'N/A';

        $data = [
            'generatedByRole' => $generatedByRole,
        ];

        switch ($view) {
            case 'apiaries':
                $data['apiaries'] = Apiary::orderBy('id')->get();
                break;

            case 'hives':
                $data['hives'] = Hive::with('apiary')->orderBy('id')->get();
                break;

            case 'monitorings':
                $data['monitorings'] = ApiaryMonitoring::with('apiary')->orderByDesc('date')->get();
                break;

            case 'productions':
                $data['productions'] = ProductionMonitoring::with('apiary')->orderByDesc('date')->get();
                break;

            case 'users':
                $data['users'] = User::with('roles')->orderBy('nickname')->get();
                break;

            case 'movements':
                $data['entries'] = ProductionMonitoring::with('apiary')
                    ->where('action', 'entry')
                    ->orderByDesc('date')
                    ->get();
                $data['exits'] = ProductionMonitoring::with('apiary')
                    ->where('action', 'exit')
                    ->orderByDesc('date')
                    ->get();
                break;

            default:
                abort(404);
        }

        // Genera el PDF descargable del reporte seleccionado
        $pdf = Pdf::loadView("senaapicola::reports.{$view}", $data);

        $fileName = 'reporte-' . str_replace('_', '-', $view) . '.pdf';

        return $pdf->download($fileName);
    }
}
