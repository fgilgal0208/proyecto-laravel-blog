<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::orderBy('id', 'desc')->paginate();
        return view('admin.tags.index', compact('tags'));
    }

    public function create()
    {
        return view('admin.tags.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:tags',
        ]);

        Tag::create($data);

        Session::flash('swal', ['icon' => 'success', 'title' => '¡Genial!', 'text' => 'Etiqueta creada']);
        return redirect()->route('admin.tags.index');
    }

    public function edit(Tag $tag)
    {
        return view('admin.tags.edit', compact('tag'));
    }

    public function update(Request $request, Tag $tag)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:tags,slug,' . $tag->id,
        ]);

        $tag->update($data);

        Session::flash('swal', ['icon' => 'success', 'title' => 'Actualizado', 'text' => 'Etiqueta actualizada']);
        return redirect()->route('admin.tags.index');
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();
        Session::flash('swal', ['icon' => 'success', 'title' => 'Eliminada', 'text' => 'Etiqueta eliminada']);
        return redirect()->route('admin.tags.index');
    }
}