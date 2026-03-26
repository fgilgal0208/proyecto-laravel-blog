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
        $query = Post::orderBy('id', 'desc');

        // Si el rol NO es admin, solo ve los suyos
        if (auth()->user()->role !== 'admin') {
            $query->where('user_id', auth()->id());
        }

        $posts = $query->paginate();

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
        // 1. VALIDACIÓN DE DATOS
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'slug'        => 'required|string|max:255|unique:posts',
            'category_id' => 'required|exists:categories,id',
            'excerpt'     => 'nullable|string',
            'content'     => 'nullable|string',
            'image_path'  => 'nullable|string',
            'image'       => 'nullable|image|max:2048',
            'tags'        => 'nullable|array',
            'tags.*'      => 'exists:tags,id',
        ]);

        // 2. GESTIÓN DE LA IMAGEN
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('posts', 'public');
            $data['image_path'] = Storage::url($path);
        }

        // 3. LÓGICA DE PUBLICACIÓN Y AUTOR
        $data['user_id'] = auth()->id(); // Asignamos el creador
        $data['is_published'] = $request->has('is_published');

        if ($data['is_published']) {
            $data['published_at'] = now(); // Si se publica directo, ponemos fecha de hoy
        }

        // 4. CREAR EL POST
        $post = Post::create($data);

        // 5. VINCULAR ETIQUETAS (Si se seleccionaron)
        if ($request->has('tags')) {
            $post->tags()->attach($request->tags);
        }

        // 6. MENSAJE Y REDIRECCIÓN
        Session::flash('swal', [
            'icon'  => 'success',
            'title' => '¡Eureka!',
            'text'  => 'Post creado correctamente',
        ]);

        return redirect()->route('admin.posts.index');
    }

public function show(Post $post)
{
    //
}

public function edit(Post $post)
    {
        if ($post->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
                    abort(403, 'No tienes permiso para editar este post.');
                }


        $categories = Category::all();
        $tags = \App\Models\Tag::all(); 
        return view('admin.posts.edit', compact('post', 'categories', 'tags'));
    }



public function update(Request $request, Post $post)
    {
        // 1. SEGURIDAD: Comprobamos si es el dueño o es un administrador
        if ($post->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            abort(403, 'No tienes permiso para actualizar este post.');
        }

        // 2. VALIDACIÓN DE DATOS
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'slug'        => 'required|string|max:255|unique:posts,slug,' . $post->id,
            'category_id' => 'required|exists:categories,id',
            'excerpt'     => 'nullable|string',
            'content'     => 'nullable|string',
            'image_path'  => 'nullable|string',
            'image'       => 'nullable|image|max:2048',
            'tags'        => 'nullable|array',
            'tags.*'      => 'exists:tags,id',
        ]);

        // 3. GESTIÓN DE LA IMAGEN
        if ($request->hasFile('image')) {
            // Eliminamos la imagen anterior si era un archivo local
            if ($post->image_path && str_contains($post->image_path, '/storage/')) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $post->image_path));
            }
            
            // Guardamos la nueva y obtenemos la URL
            $path = $request->file('image')->store('posts', 'public');
            $data['image_path'] = Storage::url($path);
        }

        // 4. LÓGICA DE PUBLICACIÓN
        $data['is_published'] = $request->has('is_published');

        if ($data['is_published'] && !$post->published_at) {
            $data['published_at'] = now(); // Si se publica ahora, ponemos la fecha actual
        } elseif (!$data['is_published']) {
            $data['published_at'] = null;  // Si vuelve a borrador, quitamos la fecha
        }

        // 5. ACTUALIZAR EL POST Y SUS RELACIONES
        $post->update($data);

        // Sincronizar etiquetas (Si no hay tags en el request, se desmarcan todas)
        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        } else {
            $post->tags()->detach(); 
        }

        // 6. MENSAJE DE ÉXITO Y REDIRECCIÓN
        Session::flash('swal', [
            'icon'  => 'success',
            'title' => '¡Eureka!',
            'text'  => 'Post actualizado correctamente',
        ]);

        return redirect()->route('admin.posts.index');
    }






public function destroy(Post $post)
    {
        // 1. SEGURIDAD: Comprobamos si es el dueño o es un administrador
        if ($post->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            abort(403, 'No tienes permiso para eliminar este post.');
        }

        // 2. LIMPIEZA: Eliminar la imagen del servidor (si es un archivo local)
        if ($post->image_path && str_contains($post->image_path, '/storage/')) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $post->image_path));
        }

        // 3. ELIMINAR EL POST DE LA BASE DE DATOS
        $post->delete();

        // 4. MENSAJE DE ÉXITO Y REDIRECCIÓN
        Session::flash('swal', [
            'icon'  => 'success',
            'title' => 'Eliminado',
            'text'  => 'Post eliminado correctamente',
        ]);

        return redirect()->route('admin.posts.index');
    }
}