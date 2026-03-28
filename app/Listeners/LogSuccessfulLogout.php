<?php

namespace App\Listeners;

use App\Models\ActivityLog;
use Illuminate\Auth\Events\Logout;

class LogSuccessfulLogout
{
    public function handle(Logout $event): void
    {
        $user = $event->user;

        if (! $user) {
            return;
        }

        ActivityLog::create([
            'user_id' => $user->id,
            'user_nickname' => $user->nickname,
            'role_name' => optional($user->roles()->first())->name ?? 'Sin rol',
            'action' => 'logout',
            'description' => 'Cierre de sesion en el sistema.',
            'ip_address' => request()?->ip(),
            'logged_at' => now(),
        ]);
    }
}
