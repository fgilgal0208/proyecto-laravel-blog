<?php

use Illuminate\Support\Facades\Route;
use App\Models\Post;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    // 1. Dashboard (Lista de publicados)
    Route::get('dashboard', function () {
            // Añadimos with('tags') para cargar las etiquetas de golpe y optimizar la carga
            $posts = Post::with('tags')
                        ->where('is_published', true)
                        ->orderBy('published_at', 'desc')
                        ->get();
                        
        return view('dashboard', compact('posts'));
    })->name('dashboard');

    // 2. Leer un post completo
    Route::get('posts/{post}', function (Post $post) {
        return view('posts.show', compact('post'));
    })->name('posts.show');

    // 3. Convertir un post a borrador (Despublicar)
    Route::patch('posts/{post}/unpublish', function (Post $post) {
        $post->update([
            'is_published' => false,
            'published_at' => null // Borramos la fecha de publicación
        ]);
        
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'A borrador',
            'text' => 'El post ha dejado de estar visible al público.'
        ]);
        
        return redirect()->route('dashboard');
    })->name('posts.unpublish');
});

require __DIR__.'/settings.php';