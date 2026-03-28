<?php

namespace App\Listeners;

use App\Models\ActivityLog;
use Illuminate\Auth\Events\Login;

class LogSuccessfulLogin
{
    public function handle(Login $event): void
    {
        $user = $event->user;

        ActivityLog::create([
            'user_id' => $user->id,
            'user_nickname' => $user->nickname,
            'role_name' => optional($user->roles()->first())->name ?? 'Sin rol',
            'action' => 'login',
            'description' => 'Inicio de sesion en el sistema.',
            'ip_address' => request()?->ip(),
            'logged_at' => now(),
        ]);
    }
}
