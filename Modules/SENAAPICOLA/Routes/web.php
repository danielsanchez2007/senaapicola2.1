<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', 'lang', 'senaapicola.app_access'])->prefix('senaapicola')->group(function () {
    Route::get('/index', 'ModuleController@adminHome')->name('cefa.senaapicola.index');
    Route::get('/admin/welcome', 'ModuleController@adminHome')->name('senaapicola.admin.welcome');
    Route::get('/intern/welcome', 'ModuleController@internHome')->name('senaapicola.intern.panelpas');

    Route::get('/admin/profile', 'ModuleController@profileEdit')->defaults('scope', 'admin')->name('senaapicola.admin.profile.edit');
    Route::put('/admin/profile', 'ModuleController@profileUpdate')->defaults('scope', 'admin')->name('senaapicola.admin.profile.update');
    Route::get('/intern/profile', 'ModuleController@profileEdit')->defaults('scope', 'intern')->name('senaapicola.intern.profile.edit');
    Route::put('/intern/profile', 'ModuleController@profileUpdate')->defaults('scope', 'intern')->name('senaapicola.intern.profile.update');

    Route::get('/admin/apiaries', 'ModuleController@apiariesIndex')->defaults('scope', 'admin')->name('senaapicola.admin.apiaries.index');
    Route::get('/admin/apiaries/search', 'ModuleController@apiariesSearch')->name('senaapicola.admin.apiaries.search');
    Route::get('/admin/apiaries/create', 'ModuleController@apiariesCreate')->defaults('scope', 'admin')->name('senaapicola.admin.apiaries.create');
    Route::post('/admin/apiaries', 'ModuleController@apiariesStore')->defaults('scope', 'admin')->name('senaapicola.admin.apiaries.store');
    Route::get('/admin/apiaries/{id}/edit', 'ModuleController@apiariesEdit')->defaults('scope', 'admin')->name('senaapicola.admin.apiaries.edit');
    Route::put('/admin/apiaries/{id}', 'ModuleController@apiariesUpdate')->defaults('scope', 'admin')->name('senaapicola.admin.apiaries.update');
    Route::delete('/admin/apiaries/{id}', 'ModuleController@apiariesDestroy')->defaults('scope', 'admin')->name('senaapicola.admin.apiaries.destroy');
    Route::get('/admin/apiaries/report', 'ModuleController@reportView')->defaults('view', 'apiaries')->name('senaapicola.admin.apiaries.report');

    Route::get('/admin/hives', 'ModuleController@hivesIndex')->defaults('scope', 'admin')->name('senaapicola.admin.hives.index');
    Route::get('/admin/hives/create', 'ModuleController@hivesCreate')->defaults('scope', 'admin')->name('senaapicola.admin.hives.create');
    Route::post('/admin/hives', 'ModuleController@hivesStore')->defaults('scope', 'admin')->name('senaapicola.admin.hives.store');
    Route::get('/admin/hives/{id}/edit', 'ModuleController@hivesEdit')->defaults('scope', 'admin')->name('senaapicola.admin.hives.edit');
    Route::put('/admin/hives/{id}', 'ModuleController@hivesUpdate')->defaults('scope', 'admin')->name('senaapicola.admin.hives.update');
    Route::delete('/admin/hives/{id}', 'ModuleController@hivesDestroy')->defaults('scope', 'admin')->name('senaapicola.admin.hives.destroy');
    Route::get('/admin/hives/report', 'ModuleController@reportView')->defaults('view', 'hives')->name('senaapicola.admin.hives.report');

    Route::get('/admin/monitorings', 'ModuleController@monitoringsIndex')->defaults('scope', 'admin')->name('senaapicola.admin.monitorings.index');
    Route::get('/admin/monitorings/create', 'ModuleController@monitoringsCreate')->defaults('scope', 'admin')->name('senaapicola.admin.monitorings.create');
    Route::post('/admin/monitorings', 'ModuleController@monitoringsStore')->defaults('scope', 'admin')->name('senaapicola.admin.monitorings.store');
    Route::get('/admin/monitorings/{id}/edit', 'ModuleController@monitoringsEdit')->defaults('scope', 'admin')->name('senaapicola.admin.monitorings.edit');
    Route::put('/admin/monitorings/{id}', 'ModuleController@monitoringsUpdate')->defaults('scope', 'admin')->name('senaapicola.admin.monitorings.update');
    Route::delete('/admin/monitorings/{id}', 'ModuleController@monitoringsDestroy')->defaults('scope', 'admin')->name('senaapicola.admin.monitorings.destroy');
    Route::get('/admin/monitorings/report', 'ModuleController@reportView')->defaults('view', 'monitorings')->name('senaapicola.admin.monitorings.report');

    Route::get('/admin/productions', 'ModuleController@productionsIndex')->defaults('scope', 'admin')->name('senaapicola.admin.productions.index');
    Route::get('/admin/productions/create', 'ModuleController@productionsCreate')->defaults('scope', 'admin')->name('senaapicola.admin.productions.create');
    Route::get('/admin/productions/create-exit', 'ModuleController@productionsCreateExit')->defaults('scope', 'admin')->name('senaapicola.admin.productions.create_exit');
    Route::post('/admin/productions', 'ModuleController@productionsStore')->defaults('scope', 'admin')->name('senaapicola.admin.productions.store');
    Route::post('/admin/productions/store-exit', 'ModuleController@productionsStoreExit')->defaults('scope', 'admin')->name('senaapicola.admin.productions.store_exit');
    Route::get('/admin/productions/{id}/edit', 'ModuleController@productionsEdit')->defaults('scope', 'admin')->name('senaapicola.admin.productions.edit');
    Route::put('/admin/productions/{id}', 'ModuleController@productionsUpdate')->defaults('scope', 'admin')->name('senaapicola.admin.productions.update');
    Route::delete('/admin/productions/{id}', 'ModuleController@productionsDestroy')->defaults('scope', 'admin')->name('senaapicola.admin.productions.destroy');
    Route::get('/admin/productions/report', 'ModuleController@reportView')->defaults('view', 'productions')->name('senaapicola.admin.productions.report');

    Route::get('/admin/users', 'ModuleController@usersIndex')->name('senaapicola.admin.users.index');
    Route::get('/admin/users/create', 'ModuleController@usersCreate')->name('senaapicola.admin.users.create');
    Route::post('/admin/users', 'ModuleController@usersStore')->name('senaapicola.admin.users.store');
    Route::get('/admin/users/{id}/edit', 'ModuleController@usersEdit')->name('senaapicola.admin.users.edit');
    Route::put('/admin/users/{id}', 'ModuleController@usersUpdate')->name('senaapicola.admin.users.update');
    Route::delete('/admin/users/{id}', 'ModuleController@usersDestroy')->name('senaapicola.admin.users.destroy');
    Route::get('/admin/users/report', 'ModuleController@reportView')->defaults('view', 'users')->name('senaapicola.admin.users.report');

    Route::get('/admin/movements', 'ModuleController@adminMovementsIndex')->name('senaapicola.admin.movements.index');
    Route::get('/admin/movements/bodega', 'ModuleController@adminMovementsBodega')->name('senaapicola.admin.movements.bodega');
    Route::get('/admin/movements/agroindustria', 'ModuleController@adminMovementsAgroindustria')->name('senaapicola.admin.movements.agroindustria');
    Route::get('/admin/movements/report', 'ModuleController@reportView')->defaults('view', 'movements')->name('senaapicola.admin.movements.report.all');

    Route::get('/admin/activities/history', 'ModuleController@activitiesHistory')->name('senaapicola.admin.activities.history');
    Route::get('/admin/activities/tasks', 'ModuleController@tasksAdminIndex')->name('senaapicola.admin.tasks.index');
    Route::get('/admin/activities/tasks/create', 'ModuleController@tasksAdminCreate')->name('senaapicola.admin.tasks.create');
    Route::post('/admin/activities/tasks', 'ModuleController@tasksAdminStore')->name('senaapicola.admin.tasks.store');
    Route::put('/admin/activities/tasks/{id}/status', 'ModuleController@tasksAdminUpdateStatus')->name('senaapicola.admin.tasks.status');

    Route::get('/intern/apiaries', 'ModuleController@apiariesIndex')->defaults('scope', 'intern')->name('senaapicola.intern.apiaries.index');
    Route::get('/intern/apiaries/{id}', function ($id) { return redirect()->route('senaapicola.intern.apiaries.index'); })->name('senaapicola.intern.apiaries.show');
    Route::get('/intern/apiaries/report', 'ModuleController@reportView')->defaults('view', 'apiaries')->name('senaapicola.intern.apiaries.report');
    Route::get('/intern/hives', 'ModuleController@hivesIndex')->defaults('scope', 'intern')->name('senaapicola.intern.hives.index');
    Route::get('/intern/hives/create', 'ModuleController@hivesCreate')->defaults('scope', 'intern')->name('senaapicola.intern.hives.create');
    Route::post('/intern/hives', 'ModuleController@hivesStore')->defaults('scope', 'intern')->name('senaapicola.intern.hives.store');
    Route::get('/intern/hives/{id}/edit', 'ModuleController@hivesEdit')->defaults('scope', 'intern')->name('senaapicola.intern.hives.edit');
    Route::put('/intern/hives/{id}', 'ModuleController@hivesUpdate')->defaults('scope', 'intern')->name('senaapicola.intern.hives.update');
    Route::delete('/intern/hives/{id}', 'ModuleController@hivesDestroy')->defaults('scope', 'intern')->name('senaapicola.intern.hives.destroy');
    Route::get('/intern/hives/report', 'ModuleController@reportView')->defaults('view', 'hives')->name('senaapicola.intern.hives.report');
    Route::get('/intern/monitorings', 'ModuleController@monitoringsIndex')->defaults('scope', 'intern')->name('senaapicola.intern.monitorings.index');
    Route::get('/intern/monitorings/create', 'ModuleController@monitoringsCreate')->defaults('scope', 'intern')->name('senaapicola.intern.monitorings.create');
    Route::post('/intern/monitorings', 'ModuleController@monitoringsStore')->defaults('scope', 'intern')->name('senaapicola.intern.monitorings.store');
    Route::get('/intern/monitorings/{id}/edit', 'ModuleController@monitoringsEdit')->defaults('scope', 'intern')->name('senaapicola.intern.monitorings.edit');
    Route::put('/intern/monitorings/{id}', 'ModuleController@monitoringsUpdate')->defaults('scope', 'intern')->name('senaapicola.intern.monitorings.update');
    Route::delete('/intern/monitorings/{id}', 'ModuleController@monitoringsDestroy')->defaults('scope', 'intern')->name('senaapicola.intern.monitorings.destroy');
    Route::get('/intern/monitorings/report', 'ModuleController@reportView')->defaults('view', 'monitorings')->name('senaapicola.intern.monitorings.report');
    Route::get('/intern/productions', 'ModuleController@productionsIndex')->defaults('scope', 'intern')->name('senaapicola.intern.productions.index');
    Route::get('/intern/productions/create', 'ModuleController@productionsCreate')->defaults('scope', 'intern')->name('senaapicola.intern.productions.create');
    Route::get('/intern/productions/create-exit', 'ModuleController@productionsCreateExit')->defaults('scope', 'intern')->name('senaapicola.intern.productions.create_exit');
    Route::post('/intern/productions', 'ModuleController@productionsStore')->defaults('scope', 'intern')->name('senaapicola.intern.productions.store');
    Route::post('/intern/productions/store-exit', 'ModuleController@productionsStoreExit')->defaults('scope', 'intern')->name('senaapicola.intern.productions.store_exit');
    Route::get('/intern/productions/{id}/edit', 'ModuleController@productionsEdit')->defaults('scope', 'intern')->name('senaapicola.intern.productions.edit');
    Route::put('/intern/productions/{id}', 'ModuleController@productionsUpdate')->defaults('scope', 'intern')->name('senaapicola.intern.productions.update');
    Route::delete('/intern/productions/{id}', 'ModuleController@productionsDestroy')->defaults('scope', 'intern')->name('senaapicola.intern.productions.destroy');
    Route::get('/intern/movements', 'ModuleController@internMovementsIndex')->name('senaapicola.intern.movements.index');
    Route::get('/intern/movements/bodega', 'ModuleController@internMovementsBodega')->name('senaapicola.intern.movements.bodega');
    Route::get('/intern/movements/agroindustria', 'ModuleController@internMovementsAgroindustria')->name('senaapicola.intern.movements.agroindustria');
    Route::get('/intern/movements/report', 'ModuleController@reportView')->defaults('view', 'movements')->name('senaapicola.intern.movements.report');
    Route::get('/intern/activities/tasks', 'ModuleController@tasksInternIndex')->name('senaapicola.intern.tasks.index');
    Route::put('/intern/activities/tasks/{id}/complete', 'ModuleController@tasksInternComplete')->name('senaapicola.intern.tasks.complete');
});
