<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = auth()->id();
        $categories = \App\Models\Category::where('user_id', $userId)->get();
        return view('editCategory', ['categories' => $categories]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = new \App\Models\Category();
        $category->name = $request->input('name');
        $category['user_id'] = auth()->id();
        $category->save();
        if ($request->ajax()) {
            return response()->json(['message' => 'Category created successfully.',200]);
        }

        return redirect()->back()->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
    
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = \App\Models\Category::findOrFail($id);
        $category->name = $request->input('name');
        $category->save();

        if ($request->ajax()) {
            return response()->json(['message' => 'Category updated successfully.', 200]);
        }

        return redirect()->back()->with('success', 'Category updated successfully.');     
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = \App\Models\Category::findOrFail($id);
        $category->delete();
        return redirect()->back()->with('success', 'Category deleted successfully.');
    }
}
