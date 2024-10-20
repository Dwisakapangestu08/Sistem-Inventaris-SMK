<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsGuest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->cookie('token');
        if (!empty($token)) {
            $data = User::findToken($token);
            if ($data->role == '1') {
                return redirect('admin');
            } else if ($data->role == '2') {
                return redirect('user');
            }
        }
        return $next($request);
    }
}
