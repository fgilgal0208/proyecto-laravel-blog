<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $categories = Category::orderBy('id', 'desc')->paginate();

    return view('admin.categories.index', compact('categories'));
}
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $data = $request->validate([
        'name' => 'required|string|max:255',
        'slug' => 'required|string|max:255|unique:categories', // Asumiendo que requieres un slug
    ]);

    Category::create($data);

    Session::flash('swal', [
        'icon' => 'success',
        'title' => 'Eureka!',
        'text' => 'Categoría creada correctamente',
    ]);

    return redirect()->route('admin.categories.index');
}
    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category->update($data);

        Session::flash('swal', [
            'icon' => 'success',
            'title' => 'Eureka!',
            'text' => 'Categoria actualizada correctamente',

        ]);

        return redirect()->route('admin.categories.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        Session::flash('swal', [
            'icon' => 'success',
            'title' => 'Eureka!',
            'text' => 'Categoria eliminada correctamente',

        ]);

        return redirect()->route('admin.categories.index');
    }
}
