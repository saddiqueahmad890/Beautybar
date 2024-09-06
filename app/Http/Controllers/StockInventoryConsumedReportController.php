<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InventoryUsage;
use App\Models\Category;
use App\Models\Item;
use App\Exports\GenericExport;
use Maatwebsite\Excel\Facades\Excel;

class StockInventoryConsumedReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        
        $query = InventoryUsage::query();
        // Filter by start date if provided
        if ($request->has('start_date') && $request->start_date) {
            $query->where('date', '>=', $request->start_date);
        }

        // Filter by end date if provided
        if ($request->has('end_date') && $request->end_date) {
            $query->where('date', '<=', $request->end_date);
        }

        // Filter by category if provided
        if ($request->has('category_id') && $request->category_id) {
            $query->whereHas('inventory.item', function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });

            // Filter the items by the selected category
            $items = Item::where('category_id', $request->category_id)->get();
        } else {
            // If no category is selected, do not filter the items
            $items = Item::all();
        }

        // Filter by item if provided
        if ($request->has('item_id') && $request->item_id) {
            $query->whereHas('inventory.item', function ($q) use ($request) {
                $q->where('id', $request->item_id);
            });
        }

        // Exclude rows where consumed_quantity is 0 or null
        $query->where(function ($q) {
            $q->where('consumed_quantity', '>', 0);
        });

        // Order by date in descending order
        $query->orderBy('id', 'desc');

        // Fetch the filtered data
        $inventoryUsages = $query->get();

        // Check if export is requested
        if ($request->has('export') && $request->input('export') == 1) {
            return $this->doExport($inventoryUsages); // Pass only the filtered data
        }

        return view('reports.stock_inventory_consumed', [
            'inventoryUsages' => $inventoryUsages,
            'categories' => Category::all(),
            'items' => $items // Pass the filtered items to the view
        ]);
    }


    private function doExport($inventoryUsages)
    {
        $data = $inventoryUsages->map(function ($usage) {
            return [
                $usage->inventory->item->category->title ?? '-',
                $usage->inventory->item->title ?? 'N/A',
                $usage->consumed_quantity ?? '-',
                \Carbon\Carbon::parse($usage->date)->format('d-M-Y'),
            ];
        })->toArray();

        $headers = ['Category', 'Item', 'Consumed Quantity', 'Date'];

        return Excel::download(new GenericExport($data, $headers), 'inventory_Consumed_report.xlsx');
    }
}
