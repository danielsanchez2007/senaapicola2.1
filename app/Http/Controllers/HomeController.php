<?php

namespace App\Http\Controllers;

use App\Support\SenaApicolaAuthRedirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public function welcome()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $user_id = $user->person->id;

            if (Session::has('passwords.' . $user_id)) {
                $session_password = Session::get('passwords.' . $user_id);

                if ($user->password === $session_password) {
                    return redirect(route('cefa.password.change.index'));
                }
            }

            if ($user->isSenaApicolaInfoViewer()) {
                return view('welcome-senaapicola', ['viewerIsInfoOnly' => true]);
            }

            return SenaApicolaAuthRedirect::forUser($user);
        }

        return view('welcome-senaapicola', ['viewerIsInfoOnly' => false]);
    }

    public function index()
    {
        if (! Auth::check()) {
            return redirect()->route('login', ['redirect' => url()->previous() ?: route('cefa.welcome')]);
        }

        $user = Auth::user();
        $user_id = $user->person->id;

        if (Session::has('passwords.' . $user_id)) {
            $session_password = Session::get('passwords.' . $user_id);

            if ($user->password === $session_password) {
                return redirect(route('cefa.password.change.index'));
            }
        }

        if ($user->isSenaApicolaInfoViewer()) {
            return view('welcome-senaapicola', ['viewerIsInfoOnly' => true]);
        }

        return SenaApicolaAuthRedirect::forUser($user);
    }
}
