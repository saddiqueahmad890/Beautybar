<?php

namespace App\Http\Controllers;

use App\Models\UserLogs;
use App\Models\Item;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{

    public function index(Request $request)
    {
        $type = $request->query('type');

        // Start with the query for filtering items
        $query = $this->filter($request);

        // Apply type filtering if type is provided
        if ($type) {
            $query->where('type', $type);
        }

        // Order by 'id' in descending order and paginate results
        $items = $query->orderBy('id', 'desc')->paginate(10);

        // Fetch categories if needed
        $categories = Category::when($type, function ($query, $type) {
            return $query->where('type', $type);
        })->get();

        // Pass the filtered items and categories to the view
        return view('items.index', compact('items', 'categories'));
    }

    private function filter(Request $request)
    {
        $query = Item::query();

        // Filter by category title using a subquery
        if ($request->filled('category')) {
            $query->whereHas('category', function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->input('category') . '%');
            });
        }

        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->input('title') . '%');
        }

        if ($request->filled('description')) {
            $query->where('description', 'like', '%' . $request->input('description') . '%');
        }

        return $query;
    }




    public function create(Request $request)
    {
        $type = $request->query('type');

        $categories = Category::where('type', $type)->get();
        $subcategories = SubCategory::all();

        return view('items.create', compact('categories', 'subcategories', 'type'));
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'category_id' => 'required',
            'title' => 'required',
        ]);

        // Create a new Item instance and set its properties
        $item = new Item();
        $item->title = $request->input('title');
        $item->description = $request->input('description');
        $item->category_id = $request->input('category_id');
        $item->type = $request->input('type');
        $item->created_by = Auth::id();
        $item->item_date = $request->input('item_date');
        $item->status = $request->input('status');
        $item->save();

        // Redirect to the edit page with the appropriate type parameter
        return redirect()->route('items.edit', ['item' => $item->id, 'type' => $item->type])
            ->with('success', 'Item created successfully.');
    }

    // In your ItemController.php
    public function getItems($category_id)
    {
        $items = Item::where('category_id', $category_id)->get();
        return response()->json($items);
    }

    public function show(Item $item)
    {
        return view('items.show', compact('item'));
    }

    public function edit(Item $item,Request $request)
    {
        $subcategories = SubCategory::get();
        $type = $request->query('type');

        $categories = Category::where('type', $type)->get();
        return view('items.edit', compact('item', 'subcategories', 'categories'));
    }

 

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'category_id' => 'required',
            'title' => 'required',
            'description' => 'nullable',
            'item_date' => 'date',
        ]);

      
        $item = Item::findOrFail($id); // Retrieve the item
        $item->update($validatedData);

        return redirect()->route('items.edit', ['item' => $item->id, 'type' => $item->type])
            ->with('success', 'Item updated successfully.');
    }

    

   public function destroy(Item $item)
{
    // Retrieve the type before deleting the item
    $type = $item->type;

    // Delete the item
    $item->delete();

    // Redirect to the items index with the type as a query parameter
    return redirect()->route('items.index', ['type' => $type])
        ->with('success', 'Item deleted successfully');
}

    public function getsubcategories(Request $request)
    {
        $categoryId = $request->get('category_id');
        $subcategories = SubCategory::get('title')->where('category_id', $categoryId)->get();
        $abc = 22;
        // return response()->json($subcategories);
        return response()->json($abc);
    }
}
