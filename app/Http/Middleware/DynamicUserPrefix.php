<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class DynamicUserPrefix
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        
        // Obtenemos el prefijo que se está intentando visitar en la URL
        $requestedPrefix = $request->route('user_prefix');

        // Determinamos cuál DEBERÍA ser el prefijo real (admin o el nombre de usuario)
        // Nota: Asumo que usas el campo 'name', lo pasamos por Str::slug() por si tiene espacios. 
        // Si en tu BD tienes un campo 'username', cambia Str::slug($user->name) por $user->username
        $expectedPrefix = $user->role === 'admin' ? 'admin' : Str::slug($user->name);

        // 1. Seguridad: Si el usuario intenta acceder al panel de otro, lo redirigimos al suyo
        if ($requestedPrefix !== $expectedPrefix) {
            return redirect()->route($request->route()->getName(), array_merge(
                $request->route()->parameters(),
                ['user_prefix' => $expectedPrefix]
            ));
        }

        // 2. Comodidad: Establecemos el valor por defecto para la función route()
        // Así no tienes que cambiar todos los route('admin.posts.index') de tus vistas blade
        URL::defaults(['user_prefix' => $expectedPrefix]);

        // 3. Magia: Ocultamos el parámetro de la ruta para que no se inyecte en tus Controladores.
        // Esto evita que tengas que añadir $user_prefix como parámetro en todos tus métodos de controlador.
        $request->route()->forgetParameter('user_prefix');

        return $next($request);
    }
}