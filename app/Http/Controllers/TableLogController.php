<?php

namespace App\Http\Controllers;

use App\Models\TableLog;
use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;

class TableLogController extends Controller
{
    public function index(Request $request)
    {
        // Initialize query builder for logs
        $query = TableLog::orderBy('id', 'desc');

        // Apply filters if they exist
        if ($request->has('category_id') && $request->category_id != '') {
            $query->whereHas('item', function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        if ($request->has('item_id') && $request->item_id != '') {
            $query->where('item_id', $request->item_id);
        }

        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('action_timestamp', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('action_timestamp', '<=', $request->end_date);
        }

        $logs = $query->get();
        $items = Item::all();
        $categories = Category::all();

        if ($request->has('export')) {
            return $this->exportLogs($logs);
        }

        // Pass logs data to the view
        return view('inventories_purchased.index', compact('logs', 'categories', 'items'));
    }

    private function exportLogs($logs)
    {
        $filename = 'Inventories_Purchased_' . now()->format('Y-m-d_H-i-s') . '.csv';
        $handle = fopen($filename, 'w+');
        fputcsv($handle, ['ID', 'Supplier Name', 'Category', 'Item', 'Quantity', 'Purchased Qty', 'Unit Cost', 'Unit Sale', 'Description', 'Timestamp']);

        foreach ($logs as $log) {
            fputcsv($handle, [
                $log->id,
                $log->supplier,
                $log->item->category->title ?? ' ',
                $log->item->title ?? '',
                $log->quantity,
                $log->purchased_qty,
                $log->unit_cost,
                $log->unit_sale,
                $log->description,
                $log->action_timestamp
            ]);
        }

        fclose($handle);

        return response()->download($filename)->deleteFileAfterSend(true);
    }
}
