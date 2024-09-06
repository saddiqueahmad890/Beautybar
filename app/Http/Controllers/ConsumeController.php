<?php

namespace App\Http\Controllers;

use App\Models\Consume;
use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Exports\GenericExport;
use Maatwebsite\Excel\Facades\Excel;

class ConsumeController extends Controller
{
    public function index(Request $request)
    {
        $query = Consume::with('inventory');

        // Apply filters
        if ($request->filled('item')) {
            $query->whereHas('inventory.item', function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->item . '%');
            });
        }

        if ($request->filled('type')) {
            $query->whereHas('inventory.item', function ($q) use ($request) {
                $q->where('type', $request->type);
            });
        }

        if ($request->filled('stock_quantity')) {
            $query->whereHas('inventory', function ($q) use ($request) {
                $q->where('quantity', $request->stock_quantity);
            });
        }

        if ($request->filled('receptionist_quantity')) {
            $query->where('receptionist_quantity', $request->receptionist_quantity);
        }

        if ($request->filled('consumed_quantity')) {
            $query->where('consumed_quantity', $request->consumed_quantity);
        }

        if ($request->filled('approval_date')) {
            $query->whereDate('approval_date', $request->approval_date);
        }

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        if ($request->filled('approved_status')) {
            $query->where('approved', $request->approved_status);
        }

        // Export data if requested
        if ($request->export) {
            return $this->doExport($request);
        }

        // Paginate results
        $records = $query->orderBy('id', 'desc')->paginate(10);

        return view('consume.index', compact('records'));
    }

    public function updateApproval(Consume $record)
    {
        // Update the 'approved' status
        $record->approved = 'yes';
        $record->approval_date = now();
        $record->approved_quantity = $record->consumed_quantity;

        // Find the associated inventory
        $inventory = Inventory::find($record->inventory_id);

        if ($inventory) {
            // Deduct the consumed quantity from the inventory
            $inventory->quantity -= $record->consumed_quantity;

            // Save the updated inventory record
            $inventory->save();
        }

        // Reset consumed quantity
        $record->consumed_quantity = 0;
        $record->save();

        return redirect()->back()->with('success', 'Record approved and inventory updated successfully.');
    }

    private function doExport(Request $request)
    {
        $query = Consume::with('inventory');

        // Apply filters for export
        if ($request->filled('item')) {
            $query->whereHas('inventory.item', function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->item . '%');
            });
        }

        if ($request->filled('type')) {
            $query->whereHas('inventory.item', function ($q) use ($request) {
                $q->where('type', $request->type);
            });
        }

        if ($request->filled('stock_quantity')) {
            $query->whereHas('inventory', function ($q) use ($request) {
                $q->where('quantity', $request->stock_quantity);
            });
        }

        if ($request->filled('receptionist_quantity')) {
            $query->where('receptionist_quantity', $request->receptionist_quantity);
        }

        if ($request->filled('consumed_quantity')) {
            $query->where('consumed_quantity', $request->consumed_quantity);
        }

        if ($request->filled('approval_date')) {
            $query->whereDate('approval_date', $request->approval_date);
        }

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        if ($request->filled('approved_status')) {
            $query->where('approved', $request->approved_status);
        }

        $records = $query->get();

        $data = $records->map(function ($record) {
            return [
                $record->inventory->item->title ?? '',
                $record->inventory->quantity ?? '',
                $record->consumed_quantity,
                $record->approved_quantity,
                \Carbon\Carbon::parse($record->approval_date)->format('Y-m-d'),
                \Carbon\Carbon::parse($record->approval_date)->format('H:i:s'),
            ];
        })->toArray();

        $headers = [
            'Items',
            'Stock Quantity',
            'Receptionist Qty',
            'Consumed Qty',
            'Approval Date',
            'Date'
        ];

        return Excel::download(new GenericExport($data, $headers), 'consumes.xlsx');
    }
}
