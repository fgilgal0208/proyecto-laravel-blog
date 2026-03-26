<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage; // <-- Importante: El Storage correcto

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::orderBy('id', 'desc')->paginate();

        return view('admin.posts.index', compact('posts'));
    }

public function create()
{
    $categories = Category::all();
    $tags = \App\Models\Tag::all(); 
    return view('admin.posts.create', compact('categories', 'tags'));
}

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:posts',
            'category_id' => 'required|exists:categories,id',
            'excerpt' => 'nullable|string',
            'content' => 'nullable|string',
            'image_path' => 'nullable|string', // Acepta URLs
            'image' => 'nullable|image|max:2048', // Acepta archivos físicos
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        // Si se subió un archivo, lo guardamos y generamos su URL pública
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('posts', 'public');
            $data['image_path'] = Storage::url($path);
        }

        $data['user_id'] = auth()->id();
        $post = Post::create($data);

        if ($request->has('tags')) {
             $post->tags()->attach($request->tags);
        }

        Session::flash('swal', [
            'icon' => 'success',
            'title' => 'Eureka!',
            'text' => 'Post creado correctamente',
        ]);

        return redirect()->route('admin.posts.index');
    }

    public function show(Post $post)
    {
        //
    }

    public function edit(Post $post)
        {
            $categories = Category::all();
            $tags = \App\Models\Tag::all(); 
            return view('admin.posts.edit', compact('post', 'categories', 'tags'));
        }

    public function update(Request $request, Post $post)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:posts,slug,'.$post->id,
            'category_id' => 'required|exists:categories,id',
            'excerpt' => 'nullable|string',
            'content' => 'nullable|string',
            'image_path' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        // Si se sube una nueva imagen, reemplazamos la anterior
        if ($request->hasFile('image')) {
            // Opcional: Eliminar la imagen anterior si era un archivo local
            if ($post->image_path && str_contains($post->image_path, '/storage/')) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $post->image_path));
            }
            
            $path = $request->file('image')->store('posts', 'public');
            $data['image_path'] = Storage::url($path);
        }

        $post->update($data);
        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        } else {
            $post->tags()->detach(); // Por si el usuario desmarca todas
        }
        Session::flash('swal', [
            'icon' => 'success',
            'title' => 'Eureka!',
            'text' => 'Post actualizado correctamente',
        ]);

        return redirect()->route('admin.posts.index');
    }

    public function destroy(Post $post)
    {
        $post->delete();

        Session::flash('swal', [
            'icon' => 'success',
            'title' => 'Eliminado',
            'text' => 'Post eliminado correctamente',
        ]);

        return redirect()->route('admin.posts.index');
    }
}