<?php

namespace App\Http\Controllers;

use App\Models\InventoryUsage;
use App\Models\Category; // Import Category model if needed for filtering
use App\Models\Item; // Import Item model if needed for filtering
use Illuminate\Http\Request;
use App\Exports\GenericExport;
use Maatwebsite\Excel\Facades\Excel;

class StockInventoryPurchasesReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Initialize the query for InventoryUsage
        $query = InventoryUsage::query();

        // Apply filters if any
        $filtersApplied = $this->applyFilters($request, $query);

        // Exclude records where purchased_qty, unit_sale_price, and total_cost are all zero
        $query->where(function ($query) {
            $query->where('purchased_qty', '>', 0);
        });

        // Fetch the filtered data or all data if no filters applied
        $inventoryUsages = $query->orderBy('id', 'desc')
            ->get();
        if ($request->has('export') && $request->input('export') == 1) {
            return $this->doExport($inventoryUsages);
        }

        // Fetch categories and items for filters (if needed)
        $categories = Category::all(); // Fetch categories if filtering by category
        $items = Item::all(); // Fetch items if filtering by item

        return view('stock-inventory-purchased-reports.index', compact('inventoryUsages', 'categories', 'items'));
    }

    private function applyFilters(Request $request, $query)
    {
        $filtersApplied = false;

        $filters = [
            'date_from' => function ($query, $value) {
                $query->whereDate('date', '>=', $value);
            },
            'date_to' => function ($query, $value) {
                $query->whereDate('date', '<=', $value);
            },
            'category_id' => function ($query, $value) {
                $query->whereHas('inventory.item', function ($q) use ($value) {
                    $q->where('category_id', $value);
                });
            },
            'item_id' => function ($query, $value) {
                $query->whereHas('inventory', function ($q) use ($value) {
                    $q->where('item_id', $value);
                });
            },
        ];

        foreach ($filters as $field => $filter) {
            if ($request->filled($field)) {
                $filter($query, $request->input($field));
                $filtersApplied = true;
            }
        }

        return $filtersApplied;
    }
    private function doExport($inventoryUsages)
    {
        $data = $inventoryUsages->map(function ($usage) {
            return [
                $usage->supplier ?? '-',
                $usage->inventory->item->category->title ?? '-',
                $usage->inventory->item->title ?? '-',
                $usage->purchased_qty,
                $usage->unit_cost,
                $usage->purchased_qty * $usage->unit_cost,
                \Carbon\Carbon::parse($usage->date)->format('d-M-Y'),
            ];
        })->toArray();


        $headers = [
            'Supplier',
            'Category',
            'Item',
            'Purchased Quantity',
            'Unit Cost',
            'Total Cost',
            'Date'
        ];

        return Excel::download(new GenericExport($data, $headers), 'inventory_Purchased_report.xlsx');
    }
}
