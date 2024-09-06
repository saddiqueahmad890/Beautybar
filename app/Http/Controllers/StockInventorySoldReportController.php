<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InventoryUsage;
use App\Models\Category;
use App\Models\Item;
use App\Exports\GenericExport;
use Maatwebsite\Excel\Facades\Excel;

class StockInventorySoldReportController extends Controller
{
    public function index(Request $request)
    {
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $categoryId = $request->input('category_id');
        $itemId = $request->input('item_id');

        $query = InventoryUsage::query();

        // Filter by date range if provided
        if ($dateFrom) {
            $query->where('date', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->where('date', '<=', $dateTo);
        }

        // Filter by category if provided
        if ($categoryId) {
            $query->whereHas('inventory.item', function ($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            });
            $items = Item::where('category_id', $categoryId)->get();
        } else {
            $items = Item::all();
        }

        // Filter by item if provided
        if ($itemId) {
            $query->whereHas('inventory.item', function ($q) use ($itemId) {
                $q->where('id', $itemId);
            });
        }

        // Exclude rows where sold_qty is 0
        $query->where('sold_qty', '>', 0);

        // Order by date in descending order
        $query->orderBy('id', 'desc');

        // Get the filtered inventory usages
        $inventoryUsages = $query->get();

        // Export functionality
        if ($request->has('export') && $request->input('export') == 1) {
            return $this->doExport($inventoryUsages);
        }

        // Fetch categories and items for the form filters
        $categories = Category::all();
        $items = Item::all();

        // Return the view with the filtered data
        return view('reports.index', compact('inventoryUsages', 'categories', 'items'));
    }

    private function doExport($inventoryUsages)
    {
        $data = $inventoryUsages->map(function ($usage) {
            return [
                $usage->inventory->item->category->title ?? 'N/A',
                $usage->inventory->item->title ?? 'N/A',
                $usage->sold_qty,
                $usage->unit_sale_price,
                $usage->sold_qty * $usage->unit_sale_price,
                \Carbon\Carbon::parse($usage->date)->format('d-M-Y'),
            ];
        })->toArray();

        $headers = ['Category', 'Item', 'Sold Quantity', 'Unit Sale Price', 'Total Cost', 'Date'];

        return Excel::download(new GenericExport($data, $headers), 'inventory_usage_report.xlsx');
    }
}
