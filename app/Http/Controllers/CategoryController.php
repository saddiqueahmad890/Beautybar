<?php

namespace App\Http\Controllers;

use App\Models\UserLogs;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



class CategoryController extends Controller
{

    public function index(Request $request)
    {
        $type = $request->query('type');

        // Start with the Category query
        $query = Category::query();

        // Apply type filtering if type is provided
        if ($type) {
            $query->where('type', $type);
        }

        // Apply additional filters using the filter method
        $query = $this->filter($query, $request);

        // Order by 'id' in descending order and paginate results
        $categories = $query->orderBy('id', 'desc')->paginate(10);

        // Pass the filtered categories and the type to the view
        return view('categories.index', compact('categories', 'type'));
    }




    private function filter($query, Request $request)
    {
        // Filter by Title
        if ($request->has('title') && !empty($request->input('title'))) {
            $query->where('title', 'like', $request->input('title') . '%');
        }

        // Filter by Description
        if ($request->has('description') && !empty($request->input('description'))) {
            $query->where('description', 'like', $request->input('description') . '%');
        }

        return $query;
    }



    public function create(Request $request)
    {
        $type = $request->query('type');
        return view('categories.create',compact('type'));
    }



    public function store(Request $request)
    {
        // Validate the incoming request data
        $this->validation($request);

        // Create a new Category instance and set its properties
        $category = new Category();
        $category->title = $request->input('title');
        $category->description = $request->input('description');
        $category->type = $request->input('type');
        $category->created_by = Auth::id();
        $category->save();

        // Redirect to the edit page with the appropriate type parameter
        return redirect()->route('categories.edit', ['category' => $category->id, 'type' => $category->type])
            ->with('success', 'Category created successfully.');
    }



    public function show(Category $category)
    {
        return view('categories.show', compact('category'));
    }


    public function edit(Category $category)

    {
        return view('categories.edit', compact('category'));
    }


    public function update(Request $request, Category $category)
    {
        $this->validation($request);
      
        $data = $request->all();
 


        $category->update($data);

        return redirect()->route('categories.edit', ['category'=>$category->id, 'type'=>$category->type])
            ->with('success', 'Category updated successfully.');
    }


    public function destroy(Category $category)
    {
        $type = $category->type; // Retrieve the category type before deletion
        $category->delete();

        return redirect()->route('categories.index', ['type' => $type])
            ->with('success', 'Category deleted successfully.');
    }


    private function validation(Request $request, $id = 0)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',

        ]);
    }
}
