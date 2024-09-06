<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DdProcedure;
use App\Models\Invoice;
use App\Exports\GenericExport;
use Maatwebsite\Excel\Facades\Excel;

class NewReportController extends Controller
{
    public function index(Request $request)
    {
        $procedures = DdProcedure::all();
        $invoices = $this->filterInvoices($request);

        if ($request->has('export') && $request->input('export') == 1) {
            return $this->doExport($invoices);
        }

        return view('new-reports.index', compact('invoices', 'procedures'));
    }

    private function filterInvoices(Request $request)
    {
        $query = Invoice::query();

        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->has('mrn_number') && $request->mrn_number) {
            $query->whereHas('user.patientDetails', function ($q) use ($request) {
                $q->where('mrn_number', 'like', '%' . $request->mrn_number . '%');
            });
        }

        if ($request->has('invoice_number') && $request->invoice_number) {
            $query->where('invoice_number', 'like', '%' . $request->invoice_number . '%');
        }

        if ($request->has('doctor') && $request->doctor) {
            $query->whereHas('invoiceItems.doctor', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->doctor . '%');
            });
        }

        if ($request->has('procedure') && $request->procedure) {
            $query->whereHas('invoiceItems.ddprocedure', function ($q) use ($request) {
                $q->where('procedure_id', $request->procedure);
            });
        }

        if ($request->has('patient') && $request->patient) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->patient . '%');
            });
        }

        return $query->with(['user.patientDetails', 'invoiceItems.doctor.doctorDetails', 'invoiceItems.ddprocedure.ddprocedurecategory'])->get()
            ->groupBy(function ($invoice) {
                $user = optional($invoice->user);
                $patientDetails = optional($user->patientDetails);
                return $user->name . '-' . $invoice->invoice_number . '-' . $patientDetails->mrn_number;
            });
    }

    private function doExport($invoices)
    {
        $data = [];
        foreach ($invoices as $groupKey => $groupedInvoices) {
            $invoice = $groupedInvoices->first();
            foreach ($invoice->invoiceItems as $item) {
                $data[] = [
                    $invoice->user->patientDetails->mrn_number ?? '-',
                    $invoice->user->name ?? '',
                    $invoice->invoice_number,
                    $item->doctor->name ?? '-',
                    $item->ddprocedure->ddprocedurecategory->title ?? '-',
                    $item->ddprocedure->title ?? '-',
                    $item->price ?? '-',
                    $item->price - ($item->price * ($item->doctor->doctorDetails->commission ?? 0) / 100) ?? '-',
                    $item->doctor->doctorDetails->commission ?? '-',
                    $item->price * ($item->doctor->doctorDetails->commission ?? 0) / 100 ?? '-',
                ];
            }
        }

        $headers = ['CR NO#', 'Customer', 'Invoice #', 'Employee', 'Service Category', 'Service', 'Total Amount', 'Saloon Amount', 'Commission %', 'Total Commission Value'];

        return Excel::download(new GenericExport($data, $headers), 'invoices.xlsx');
    }
}
