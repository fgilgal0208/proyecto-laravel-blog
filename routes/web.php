<?php

use App\Models\Post;
use Illuminate\Support\Facades\Route;

// Ruta principal (Landing page) se queda en la raíz ( / )
Route::view('/', 'welcome')->name('home');

// Rutas protegidas con el prefijo 'practicablog'
Route::middleware(['auth', 'verified'])->prefix('PracticaBlog')->group(function () {
    
    // 1. Dashboard (Lista de posts publicados)
    Route::get('dashboard', function () {
        // Cargamos 'tags' y 'user' de golpe para optimizar la base de datos
        $posts = Post::with(['tags', 'user'])
            ->where('is_published', true)
            ->orderBy('published_at', 'desc')
            ->get();
                     
        return view('dashboard', compact('posts'));
    })->name('dashboard');

    // 2. Leer un post completo
    Route::get('posts/{post}', function (Post $post) {
        // Cargamos las relaciones para la vista de lectura
        $post->load(['tags', 'user']); 
        
        return view('posts.show', compact('post'));
    })->name('posts.show');

    // 3. Convertir un post a borrador (Despublicar)
    Route::patch('posts/{post}/unpublish', function (Post $post) {
        // SEGURIDAD: Solo el dueño o el admin pueden despublicar
        if ($post->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            abort(403, 'No tienes permiso para despublicar este post.');
        }

        // Lo pasamos a borrador y limpiamos la fecha
        $post->update([
            'is_published' => false,
            'published_at' => null 
        ]);
        
        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'A borrador',
            'text'  => 'El post ha dejado de estar visible al público.'
        ]);
        
        return redirect()->route('dashboard');
    })->name('posts.unpublish');

});

// Rutas de configuración de usuario
require __DIR__.'/settings.php';