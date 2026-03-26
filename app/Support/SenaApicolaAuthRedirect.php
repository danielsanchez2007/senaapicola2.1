<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Http\RedirectResponse;

class SenaApicolaAuthRedirect
{
    /**
     * Redirige al panel correspondiente según el rol SENA APICOLA.
     */
    public static function forUser(User $user): RedirectResponse
    {
        $roleSlugs = $user->roles()->pluck('slug')->toArray();

        if (in_array('senaapicola.admin', $roleSlugs, true)) {
            return redirect()->route('senaapicola.admin.welcome');
        }

        if (in_array('senaapicola.intern', $roleSlugs, true)) {
            return redirect()->route('senaapicola.intern.panelpas');
        }

        if (in_array('senaapicola.usuarioinfo', $roleSlugs, true)) {
            return redirect()->route('cefa.welcome');
        }

        return redirect()->route('cefa.senaapicola.index');
    }
}
