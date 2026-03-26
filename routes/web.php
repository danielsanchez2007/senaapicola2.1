<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use Modules\SICA\Http\Controllers\security\UserController;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Support\SenaApicolaAuthRedirect;
use App\Http\Controllers\PublicRegistrationController;
use Laravel\Socialite\Facades\Socialite;

Route::post('/register/usergoogle/', [UserController::class, 'storer_usergoogle'])->name('register.usergoogle'); /* Registrar usuarion por google */

Route::get('/user/register/', [UserController::class, 'user_register'])->name('cefa.user.register.index');
Route::get('/user/register/searchperson', [UserController::class, 'user_search_person'])->name('cefa.user.register.searchperson');
Route::post('/user/register/store', [UserController::class, 'user_register_store'])->name('cefa.user.register.store');

Route::middleware(['lang'])->group(function(){

    Route::get('login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
    Route::post('logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

    Route::get('register', function () {
        return redirect()->route('senaapicola.register');
    })->middleware('guest')->name('register');

    Route::middleware('guest')->group(function () {
        Route::get('/registro', [PublicRegistrationController::class, 'create'])->name('senaapicola.register');
        Route::post('/registro', [PublicRegistrationController::class, 'store'])->name('senaapicola.register.store');
    });

    Route::get('password/reset', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');

    Route::get('lang/{lang}', function($lang) {
        session(['lang'=>$lang]);
        return Redirect::back();
    })->where(['lang'=>'es|en']);

    Route::get('/', [HomeController::class, 'welcome'])->name('cefa.welcome');
    Route::get('/home', [HomeController::class, 'index'])->name('cefa.home');

    // --------------  Ruta Cambio de Contraseña ---------------------------------
    Route::get('/password/change/index', [UserController::class, 'change'])->name('cefa.password.change.index'); /* Vista Cambio de Contraseña */
    Route::post('/password/change/', [UserController::class, 'changesave'])->name('cefa.password.change'); /* Cambio de Contraseña */


    Route::get('/login_google', function () {
        return Socialite::driver('google')->redirect();
    })->name('logingoogle');
     
    Route::get('/google-callback', function () {
        $user = Socialite::driver('google')->user();
        $userexist = User::where('email',$user->email)->first();
            if ($userexist) {
                Auth::login($userexist);
                return SenaApicolaAuthRedirect::forUser($userexist);
            } else {
                $data=['user'=>$user];
                return view('auth.logingoogle',$data);
            }
    });

    // UniSharp package was removed during Laravel 12 migration.
    // Add replacement file manager routes here if needed.

    Route::get('/check-session', function () {
        if (auth()->check()) {
            return response()->json(['status' => 'active'], 200);
        }
    
        return response()->json(['status' => 'inactive'], 403);
    });
    

});

