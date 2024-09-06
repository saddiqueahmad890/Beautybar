<?php

namespace App\Http\Controllers;

use App\Traits\Loggable;
use App\Models\UserLogs;
use App\Models\Inventory;
use App\Models\Item;
use App\Models\User;
use App\Models\Category;
use App\Models\InventoryUsage;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Exports\GenericExport;
use App\Http\Controllers\ArrayExport;
use Maatwebsite\Excel\Facades\Excel;
use SebastianBergmann\Type\NullType;

class InventoryController extends Controller
{
    use loggable;

    public function index(Request $request)
    {
        $type = $request->query('type');

      
        $query = $this->filter($request);

        if ($type) {
            // Filter inventories based on the associated item's type
            $query->whereHas('item', function ($query) use ($type) {
                $query->where('type', $type);
            });
        }

        if ($request->has('export') && $request->input('export') == 1) {
            return $this->doExport($query);
        }

        $inventories = $query->orderby('id', 'desc')->paginate(10);

        return view('inventory.index', compact('inventories', 'type'));
    }

    private function filter(Request $request)
    {
        $query = Inventory::query();

        // Filter by Category Title
        if ($request->has('category_title') && !empty($request->input('category_title'))) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->input('category_title') . '%');
            });
        }
        if ($request->has('supplier') && !empty($request->input('supplier'))) {
            $query->where('supplier', 'like', '%' . $request->input('supplier') . '%');
        }

        // Filter by Item Title
        if ($request->has('item_title') && !empty($request->input('item_title'))) {
            $query->whereHas('item', function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->input('item_title') . '%');
            });
        }

        // Filter by Date Range
        if (
            $request->has('start_date') && !empty($request->input('start_date')) &&
            $request->has('end_date') && !empty($request->input('end_date'))
        ) {
            $startDate = $request->input('start_date') . ' 00:00:00';
            $endDate = $request->input('end_date') . ' 23:59:59';
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        return $query;
    }


    private function doExport($query)
    {
        $inventories = $query->get();

        $data = $inventories->map(function ($inventory) {
            return [
                $inventory->id,
                $inventory->item->title ?? '-',
                $inventory->category->title ?? '-',
                $inventory->supplier ?? '-',
                $inventory->quantity,
                $inventory->unit_cost,
                $inventory->created_at,
                $inventory->updated_at,
            ];
        })->toArray();

        $headers = ['ID', 'Item Name', 'Category Name', 'supplier', 'Quantity', 'unit cost', 'Created At', 'Updated At'];

        return Excel::download(new GenericExport($data, $headers), 'inventories.xlsx');
    }

    public function create(Request $request)
    {
        $type = $request->query('type');
        $items = Item::all();
        $categories = Category::where('type', $type)->get();
        $inventoryUsages = InventoryUsage::with('inventory')->orderBy('created_at', 'desc')->get();

        return view('inventory.create', compact('items', 'categories', 'inventoryUsages','type'));
    }

    // public function update_quantity(Request $request, $id)
    // {
    //     $inventory = Inventory::findOrFail($id);

    //     $request->validate([
    //         'consume_quantity' => 'required|integer|min:1',
    //         'date' => 'required|date',
    //         'unit_sale_price' => 'nullable|numeric|min:0',
    //     ]);

    //     $consumeQuantity = $request->input('consume_quantity');
    //     $unitSalePrice = $request->input('unit_sale_price');
    //     $purchased_qty = $request->input('quantity');

    //     if ($consumeQuantity > $inventory->quantity) {
    //         return response()->json(['error' => 'Consume quantity cannot be greater than current quantity.'], 400);
    //     }

    //     // Adjust inventory quantity
    //     $inventory->quantity -= $consumeQuantity;
    //     $inventory->save();

    //     // Save consumed quantity or sold quantity based on unit_sale_price
    //     $inventoryData = [
    //         'inventory_id' => $id,
    //         'date' => $request->input('date'),
    //         'created_by' => auth()->id(),
    //     ];

    //     if ($unitSalePrice > 0) {
    //         $inventoryData['sold_qty'] = $consumeQuantity;
    //         $inventoryData['unit_sale_price'] = $unitSalePrice;
    //         $inventoryData['consumed_quantity'] = null;
    //     } else {
    //         $inventoryData['sold_qty'] = null;
    //         $inventoryData['consumed_quantity'] = $consumeQuantity;
    //         $inventoryData['unit_sale_price'] = null;
    //     }


    //     $usedInventory = InventoryUsage::create($inventoryData);

    //     $username = User::find(auth()->id())->name;

    //     return response()->json([
    //         'message' => 'Quantity updated successfully.',
    //         // 'purchased_qty' => $usedInventory->inventory->purchased_qty,
    //         'unit_cost' => $inventory->unit_cost,
    //         'new_quantity' => $inventory->quantity,
    //         'usedInventory' => $usedInventory,
    //         'unit_sale_price' => $unitSalePrice,
    //         'sold_qty' => $unitSalePrice > 0 ? $consumeQuantity : null,
    //         'username' => $username,
    //     ]);

    // }
    public function updateConsumed(Request $request, $id)
    {
        $type = request()->query('type');

       $inventory = Inventory::findOrFail($id);
        $consumedQuantity = $request->input('quantity');

        // Ensure the quantity does not go below zero
        if ($inventory->quantity < $consumedQuantity) {
            return redirect()->route('inventories.index', ['type' => $type])->withErrors(['quantity' => 'Consumed quantity exceeds available quantity.']);
        }
        InventoryUsage::create([
            'inventory_id' => $id,
            'consumed_quantity' => $consumedQuantity,
            'type' => $type,
            'description' => $request->description,
            'date' => $request->input('date'),
            'approved' => $request->input('approved', 'no'),
            'created_by' => auth()->id(), 
            'updated_by' => auth()->id()
        ]);
        $baseUrl = route('consume.index');

        // Append the query parameter to the URL
        $url = $baseUrl . '?type=' . (request()->query('type'));
        if ($type == 'sln_items') {
            $msg = "A new inventory salon items item has been consumed.";
        } elseif ($type == 'ofs_items') {
            $msg = "A new inventory office item has been consumed.";
        }
        send_Notification($url, $msg);

        if ($_SERVER['SERVER_NAME'] !== 'localhost') {

            $messageBodyForAdmin = "The quantity of $consumedQuantity units of " . $inventory->item->title . " from the inventory has been consumed.";
            $subjectForAdmin = "Approval Required: Inventory Consumption";

            $messageBodyForDoctor = "Hi,  Saddique Ahmad! Please review the consumed inventory item.";
            $subjectForDoctor = "Action Required: Inventory Consumption Approval";

            mail('saddique', $subjectForDoctor, $messageBodyForDoctor);
            mail("saddiqueahmad890@gmail.com", $subjectForAdmin, $messageBodyForAdmin);
        }


        return redirect()->route('inventories.index', ['type' => $type])->with('success', 'Item consumed successfully.');
    }




    public function getUsages()
    {
        $usages = InventoryUsage::with('createdBy')->get(); // Fetch usages with related user
        return response()->json($usages);
    }


    public function store(Request $request)
    {

        $type = $request->query('type');
        

        $validatedData = $request->validate([
            'item_id' => 'required|exists:items,id',
            'category_id' => 'required|exists:dd_categories,id',
            'supplier' => 'required',
            'quantity' => 'required|integer',
            'unit_cost' => 'required|numeric',
            'unit_sale' => 'numeric',
            'description' => 'nullable',
        ]);

        $attributes = [
            'item_id' => $validatedData['item_id'],
            'category_id' => $validatedData['category_id'],
        ];

        $inventory = Inventory::firstOrNew($attributes);
        $inventory->quantity = ($inventory->quantity ?? 0) + $validatedData['quantity'];
        $inventory->unit_cost = $validatedData['unit_cost'];
        $inventory->unit_sale = $validatedData['unit_sale']?? null;
        $inventory->supplier = $validatedData['supplier'];
        $inventory->description = $validatedData['description'];
        $inventory->purchased_qty = ($inventory->purchased_qty ?? 0) + $validatedData['quantity'];


        $inventory->save();

        return redirect()->route('inventories.index', ['type' => $type])
        ->with('success', 'Inventory created successfully.');
    }

    public function show($id, Request $request)
    {

        // Retrieve a single record by ID
        $inventory = Inventory::where('id', $id)->orderBy('id', 'desc')->first();


        // Retrieve and sort all records
        // $sortedInventories = Inventory::orderBy('id', 'desc')->get();
        if ($request->has('export') && $request->input('export') == 1) {
            return $this->doExportSingle($inventory);
        }

        return view('inventory.show', compact('inventory'));
    }
    private function doExportSingle($inventory)
    {
        $usages = $inventory->usages->sortByDesc('id');

        $data = $usages->map(function ($usage) {
            return [
                'Purchased Qty' => $usage->purchased_qty ?? ' ',
                'Unit Cost' => $usage->unit_cost ? $usage->unit_cost . ' PKR' : ' ',
                'Consumed Qty' => $usage->consumed_quantity ?? ' ',
                'Sold Qty' => $usage->sold_qty ?? ' ',
                'Unit Sale Price' => $usage->unit_sale_price ? $usage->unit_sale_price . ' PKR' : ' ',
                'Date' => \Carbon\Carbon::parse($usage->date)->format('d-M-Y'),
                'User' => optional($usage->createdBy)->name ?? ' ',
            ];
        })->toArray();

        $headers = ['Purchased Qty', 'Unit Cost', 'Consumed Qty', 'Sold Qty', 'Unit Sale Price', 'Date', 'User'];

        return Excel::download(new GenericExport($data, $headers), 'inventory_usage_' . $inventory->id . '.xlsx');
    }

    public function edit($id , Request $request)
    {
        $inventory = Inventory::find($id);
        $type = $request->query('type');


        $items = Item::all();
        $categories = Category::all();
        $subCategories = SubCategory::all();

        // start of log code
        $logs = UserLogs::where('table_name', 'inventories')->orderBy('id', 'desc')
            ->with('user')
            ->paginate(10);
        // end of log code

        return view('inventory.edit', compact('inventory', 'items', 'categories', 'subCategories', 'logs' , 'type'));
    }


    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'item_id' => 'required|integer',
            'category_id' => 'required|integer',
            'supplier' => 'required|string|max:255',
            'quantity' => 'required|integer',
            'unit_cost' => 'required|numeric',
        ]);

        $inventory = Inventory::findOrFail($id);
        $inventory->item_id = $request->item_id;
        $inventory->category_id = $request->category_id;
        $inventory->supplier = $request->supplier;
        $inventory->quantity = $request->quantity;
        $inventory->unit_cost = $request->unit_cost;
        $inventory->description = $request->description;
        $inventory->unit_sale = $request->unit_sale;
        $inventory->save();

        return redirect()->route('inventories.index')->with('success', 'Inventory updated successfully');
    }


    public function destroy($id)
    {
        $inventory = Inventory::find($id);
        $inventory->delete();
        return redirect()->route('inventories.index');
    }
}
