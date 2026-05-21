<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateUserActivity
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user) {
            $now = Carbon::now();
            $shouldUpdate = !$user->last_active_at || $user->last_active_at->diffInMinutes($now) >= 1;

            if ($shouldUpdate) {
                $user->forceFill([
                    'last_active_at' => $now,
                    'is_online' => true,
                ])->save();
            }
        }

        return $next($request);
    }
}
