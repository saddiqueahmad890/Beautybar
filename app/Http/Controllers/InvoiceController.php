<?php

namespace App\Http\Controllers;

use App\Models\AccountHeader;
use App\Models\Company;
use App\Models\Insurance;
use App\Models\Invoice;
use App\Models\Category;
use App\Models\Item;
use App\Models\DoctorDetail;
use App\Models\InvoiceItem;
use App\Models\DdProcedureCategory;
use App\Models\DdProcedure;
use App\Models\PatientDetail;
use App\Models\Inventory;
use App\Models\InvoicePayment;
use App\Models\PatientTreatmentPlan;
use App\Models\PatientTreatmentPlanProcedure;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use App\Models\PatientTeeth;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */



    public function index(Request $request)
    {

        // Check user role and fetch patients accordingly
        if (auth()->user()->hasRole('Patient')) {
            $patients = User::role('Patient')->where('id', auth()->id())->where('status', '1')->get(['id', 'name']);
        } else {
            $patients = User::role('Patient')->where('status', '1')->get(['id', 'name']);
        }

        // Get the 'type' query parameter
        $type = $request->query('type');
        $approve = $request->query('approve') ?? null;
        $report = $request->query('report') ?? null;

        // Apply filters
        $query = $this->filter($request);

        // If 'type' is present, filter invoices by 'invoice_type'
        if ($type) {
            $query->where('invoice_type', $type);
        }

        // Fetch invoices with their items and order by ID descending
        $invoices = $query->with('invoiceItems', 'user')
            ->orderBy('id', 'desc') // Order by ID in descending order
            ->get();

        // Group and sum data by invoice number
        $groupedInvoices = [];
        foreach ($invoices as $invoice) {
            if (!isset($groupedInvoices[$invoice->invoice_number])) {
                $groupedInvoices[$invoice->invoice_number] = [
                    'id' => $invoice->id,
                    'invoice_number' => $invoice->invoice_number,
                    'user_name' => $invoice->user->name ?? " ",
                    'paid' => $invoice->paid,
                    'due' => $invoice->due,
                    'grand_total' => $invoice->grand_total,
                    'invoice_date' => $invoice->invoice_date,
                    'approval_date' => $invoice->approval_date,
                    'invoice_approved' => $invoice->invoice_approved,
                    // 'approved_quantity' => $invoice->approved_quantity,
                    'items' => []
                ];
            }

            foreach ($invoice->invoiceItems as $item) {
                $groupedInvoices[$invoice->invoice_number]['items'][] = $item;
            }
        }

        // Convert the grouped invoices array to a collection and order by ID descending
        $groupedInvoices = collect($groupedInvoices)->sortByDesc('id');

        // Paginate the collection
        $perPage = 10; // Number of items per page
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentPageItems = $groupedInvoices->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $paginatedInvoices = new LengthAwarePaginator($currentPageItems, $groupedInvoices->count(), $perPage, $currentPage, [
            'path' => $request->url(),
            'query' => $request->query()
        ]);
        if ($type == 'sale') {
            if ($approve === null) {
                return view('invoices.indexsale', compact('patients', 'type', 'paginatedInvoices'), ['invoices' => $paginatedInvoices]);
            } else {
                if($report=== null){
                return view('invoices.indexsaleapproval', compact('patients', 'type', 'paginatedInvoices'), ['invoices' => $paginatedInvoices]);
            }
            else{
                    return view('invoices.indexreport', compact('patients', 'type', 'paginatedInvoices'), ['invoices' => $paginatedInvoices]);

            }
        }
        } else {
            return view('invoices.index', compact('patients', 'type', 'paginatedInvoices'), ['invoices' => $paginatedInvoices]);
        }

    }




    /**
     * Filter function
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function filter(Request $request)
    {
        $query = Invoice::where('company_id', session('company_id'));

        if (auth()->user()->hasRole('Patient')) {
            $query->where('user_id', auth()->id());
        } elseif ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->invoice_date) {
            $query->where('invoice_date', $request->invoice_date);
        }

        if ($request->invoice_number) {
            $query->where('invoice_number', 'like', '%' . $request->invoice_number . '%');
        }
        if ($request->has('doctor') && $request->doctor) {
            $query->whereHas('invoiceItems.doctor', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->doctor . '%');
            });
        } 

        return $query;
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        $patients = User::role('Patient')->where('company_id', session('company_id'))->where('status', '1')->get(['id', 'name']);
        $doctors = User::role('Doctor')->where('company_id', session('company_id'))->where('status', '1')->get(['id', 'name']);
        $insurances = Insurance::where('status', '1')->get();
        $accountHeader = AccountHeader::where('type', 'Debit')->where('status', '1')->first();
        $type = $request->query('type');
        $patientTreatmentPlan = null;
        $patientTreatmentPlanProcedures = collect(); // Initialize as an empty collection
        $teeth = collect(); // Initialize as an empty collection

        if ($request->treatment_plan_id) {
            $patientTreatmentPlan = PatientTreatmentPlan::find($request->treatment_plan_id);
            $teeth = PatientTeeth::with('toothIssues')->where('examination_id', $patientTreatmentPlan->examination_id)->get();

            $patientTreatmentPlanProcedures = PatientTreatmentPlanProcedure::where('patient_treatment_plan_id', $patientTreatmentPlan->id)
                ->whereIn('tooth_number', $teeth->pluck('tooth_number')->toArray())
                ->where('status', 'activated')
                ->where('is_procedure_started', 'yes')
                ->where('is_procedure_finished', 'yes')
                ->whereDoesntHave('invoiceItems') // Ensure procedures not already invoiced
                ->get();
        }
        $procedureCategories = DdProcedureCategory::get(['id', 'title']);
        $ddProcedures = DdProcedure::get(['id', 'title']);

        if ($type == 'sale') {
            return view('invoices.createsale', compact('type'), [
                'doctors' => $doctors,
                'procedureCategories' => $procedureCategories,
                'ddProcedures' => $ddProcedures,
                'patients' => $patients,
                'patientTreatmentPlan' => $patientTreatmentPlan,
                'insurances' => $insurances,
                'accountHeader' => $accountHeader,
                'patientTreatmentPlanProcedures' => $patientTreatmentPlanProcedures,
                'teeth' => $teeth,
                'categories' => Category::all(),
                'items' => Item::all(),

            ]);
        } else {
            return view('invoices.create', compact('type'), [
                'doctors' => $doctors,
                'procedureCategories' => $procedureCategories,
                'ddProcedures' => $ddProcedures,
                'patients' => $patients,
                'patientTreatmentPlan' => $patientTreatmentPlan,
                'insurances' => $insurances,
                'accountHeader' => $accountHeader,
                'patientTreatmentPlanProcedures' => $patientTreatmentPlanProcedures,
                'teeth' => $teeth,
                'categories' => Category::all(),
                'items' => Item::all(),

            ]);
        }
    }
    public function getProceduresByCategory(Request $request)
    {
        $categoryId = $request->category_id;
        $procedures = DdProcedure::where('dd_procedure_id', $categoryId)->get(['id', 'title', 'price']);
        // dd($procedures);

        return response()->json($procedures);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // exit;
        $type = $request->query('type');

        // Validate the request
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'invoice_date' => 'required|date',
            // Add other validation rules as needed
        ]);

        // Start a database transaction
        $invoice = DB::transaction(function () use ($request, $type) {
            // Create a new invoice
            $invoice = Invoice::create([
                'company_id' => session('company_id'),
                'user_id' => $request->user_id,
                'invoice_date' => Carbon::parse($request->invoice_date),
                'invoice_type' => $type == 'services' ? 'services' : 'sale',
            ]);

            // Generate the invoice number and save the invoice
            $invoice->invoice_number = getDocNumber($invoice->id, 'INV');
            $invoice->save();

            // Store the invoice details
            $this->storeInvoice($request, $invoice);

            return $invoice;
        });

        // Ensure the invoice was created successfully
        if ($invoice) {


            // Send emails if the environment is not localhost
            if ($_SERVER['SERVER_NAME'] !== 'localhost') {
                $userData = User::find($request->user_id);
                $patientDetails = PatientDetail::where('user_id', $request->user_id)->first();

                // Prepare email contents
                $messageBodyForAdmin = "A new customer " . $userData->name . " with invoice number " . $invoice->invoice_number . " has been created.";
                $subjectForAdmin = "A new invoice has been created. Invoice Number " . $invoice->invoice_number;
                $messageBodyForPatient = "Hi " . $userData->name . ", your registration process has been completed successfully. Please note down your MRN number " . ($patientDetails->mrn_number ?? 'N/A');
                $subjectForPatient = "Registration Successful - Welcome to Blanche Beauty Bar";

                // Send the emails
                mail($userData->email, $subjectForPatient, $messageBodyForPatient);
                mail("saddiqueahmad890@gmail.com", $subjectForAdmin, $messageBodyForAdmin);
            }

            // Construct the URL based on the invoice type
            if ($invoice->invoice_type === 'services') {
                
                $redirectUrl = route('invoices.show',$invoice) . '?type=' . $invoice->invoice_type;
            } else {
                $redirectUrl = route('invoices.show', $invoice) . '?type=' . $invoice->invoice_type; // Adjust if you have specific query parameters
            }

            // $msg = "New invoice has been created";
            // sendNotification($request->user_id, $redirectUrl, $msg);

            // Perform the redirection
            return redirect($redirectUrl)
                ->with('success', trans('Invoice Added Successfully'));
        } else {
            // Handle case where invoice was not created successfully
            return redirect()->back()->withErrors('Failed to create invoice');
        }
    }




    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show(Invoice $invoice, Request $request)
    {
        if (auth()->user()->hasRole('Patient') && auth()->id() != $invoice->user_id)
            return redirect()->route('dashboard');

        $company = Company::find($invoice->company_id);
        $company->setSettings();
        $type = $request->query('type');

        if ($type == 'sale') {

            return view('invoices.showsale', compact('company', 'invoice', 'type'));
        } else {
            return view('invoices.show', compact('company', 'invoice', 'type'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function edit(Invoice $invoice, Request $request)
    {
        $type = $request->query('type');
        $accountHeader = AccountHeader::where('type', 'Debit')->where('status', '1')->first();
        $insurances = Insurance::where('status', '1')->get();
        $patients = User::role('Patient')->where('company_id', session('company_id'))->where('status', '1')->get(['id', 'name']);
        $doctors = User::role('Doctor')->where('company_id', session('company_id'))->where('status', '1')->get(['id', 'name']);
        $invoicePayments = InvoicePayment::where('invoice_id', $invoice->id)->get();
        $totalPaidAmount = $invoicePayments->sum('paid_amount');
        $grandTotal = $invoice->grand_total;
        $procedureCategories = DdProcedureCategory::get(['id', 'title']);
        $ddProcedures = DdProcedure::get(['id', 'title']);
        if ($type == 'sale') {
            return view('invoices.editsale', [
                'invoice' => $invoice,
                'patients' => $patients,
                'categories' => Category::all(),
                'items' => Item::all(),
            ], compact('type', 'doctors', 'procedureCategories', 'ddProcedures', 'accountHeader', 'insurances', 'invoice', 'patients', 'invoicePayments', 'totalPaidAmount', 'grandTotal'));
        } else {
            return view('invoices.edit', [
                'invoice' => $invoice,
                'patients' => $patients,
                'categories' => Category::all(),
                'items' => Item::all(),
            ], compact('type', 'doctors', 'procedureCategories', 'ddProcedures', 'accountHeader', 'insurances', 'invoice', 'patients', 'invoicePayments', 'totalPaidAmount', 'grandTotal'));
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invoice $invoice)
    {

        $this->validation($request);
        $invoice->invoiceItems()->delete();
        $this->storeInvoice($request, $invoice);
        return redirect()->route('invoices.index', ['type' => request()->query('type')])->with('success', trans('Invoice Updated Successfully'));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invoice $invoice)
    {
        if ($invoice->invoiceItems()->exists())
            return redirect()->route('invoices.index')->with('error', trans('Invoice Cannot be deleted'));

        $invoice->invoiceItems()->delete();
        $invoice->delete();

        return redirect()->route('invoices.index')->with('success', trans('Invoice Removed Successfully'));
    }

    /**
     * Stores invoce data
     *
     * @param Request $request
     * @param Invoice $invoice
     * @return void
     */
    private function storeInvoice(Request $request, Invoice $invoice)
    {
        // dd($request);

        DB::transaction(function () use ($request, $invoice) {
            $invoiceItems = [];
            $total = 0;

            // Ensure all expected request data are arrays
            $procedures = $request->procedure ?? [];
            $quantities = $request->quantity ?? [];
            $prices = $request->price ?? [];
            $doctors = $request->doctors ?? [];
            $accountNames = $request->account_name ?? [];
            $discountPcts = $request->discount_pct ?? [];
            $inventory_id = $request->inventory_id ?? [];

            // Retrieve commissions as an associative array
            $doctorIds = array_filter($doctors); // Filter out empty values
            $commissions = DoctorDetail::whereIn('user_id', $doctorIds)
                ->pluck('commission', 'user_id')
                ->toArray();

            if (request()->query('type') == 'services') {
                // if service
                foreach ($procedures as $key => $value) {
                    $quantity = $quantities[$key] ?? 1; // Default to 1 if not set

                    $price = $prices[$key] ?? 0; // Default to 0 if not set
                    $itemTotal = round(($quantity * $price), 2);

                    $doctorId = $doctors[$key] ?? null;
                    $commission = $commissions[$doctorId] ?? 0;

                    $invoiceItems[] = [
                        'company_id' => session('company_id'),
                        'invoice_id' => $invoice->id,
                        'commission' => $commission,
                        'procedure_id' => empty($value) ? null : $value,
                        'doctor_id' => empty($doctorId) ? null : $doctorId,
                        'account_name' => $accountNames[$key] ?? null,
                        'account_type' => 'Debit',
                        'quantity' => round($quantity, 2),
                        'price' => round($price, 2),
                        'discount_pct' => round($discountPcts[$key] ?? 0, 2),
                        'total' => round($itemTotal, 2),
                        'created_at' => now(),
                        'updated_at' => now()
                    ];


                    $total += $itemTotal;
                }
            } else {
                // for sale
                $key = 0;
                $quantity = $quantities[$key] ?? 1; // Default to 1 if not set
                $inventory = Inventory::find($inventory_id);



                $price = $prices[$key] ?? 0; // Default to 0 if not set
                $itemTotal = round(($quantity * $price), 2);

                $doctorId = $doctors[$key] ?? null;
                $commission = $commissions[$doctorId] ?? 0;

                $invoiceItems[] = [
                    'company_id' => session('company_id'),
                    'invoice_id' => $invoice->id,
                    'commission' => $commission,
                    'inventory_id' => $inventory_id,
                    'doctor_id' => empty($doctorId) ? null : $doctorId,
                    'account_name' => $accountNames[$key] ?? null,
                    'account_type' => 'Debit',
                    'quantity' => round($quantity, 2),
                    'price' => round($price, 2),
                    'discount_pct' => round($discountPcts[$key] ?? 0, 2),
                    'total' => round($itemTotal, 2),
                    'created_at' => now(),
                    'updated_at' => now()
                ];


                $total += $itemTotal;
            }

            // dd($invoiceItems);
            InvoiceItem::insert($invoiceItems);

            $grandTotal = round($total, 2);
            $totalDiscount = round($request->total_discount ?? 0, 2);
            if ($request->discount_percentage > 0) {
                $totalDiscount = ($request->discount_percentage / 100) * $total;
            }
            $grandTotal -= round($totalDiscount, 2);

            $totalVat = round($request->total_vat ?? 0, 2);
            if ($request->vat_percentage > 0) {
                $totalVat = ($request->vat_percentage / 100) * $grandTotal;
            }
            $grandTotal += round($totalVat, 2);

            $invoice->update([
                'insurance_id' => $request->insurance_id ?? null,
                'invoice_date' => now(),
                'vat_percentage' => $request->vat_percentage ?? 0,
                'total_vat' => $totalVat,
                'total' => $total,
                'total_discount' => $totalDiscount,
                'grand_total' => $grandTotal,
                'paid' => $request->paid ?? 0,
                'due' => $grandTotal - ($request->paid ?? 0)
            ]);
        });
    }



    public function approve(Request $request, Invoice $invoice)
    {
        if ($invoice) {
            foreach ($invoice->invoiceItems as $item) {
                \Log::info('Processing item with inventory_id: ' . $item->inventory_id);

                $inventory = Inventory::find($item->inventory_id);

                if ($inventory) {
                    if ($inventory->quantity >= $item->quantity) {
                        $inventory->quantity -= $item->quantity;
                        $inventory->save();
                    } else {
                        throw new \Exception('Insufficient inventory for item ID: ' . $inventory->id);
                    }
                } else {
                    \Log::error('Inventory item not found for ID: ' . $item->inventory_id);
                    throw new \Exception('Inventory item not found for ID: ' . $item->inventory_id);
                }
            }

            $invoice->invoice_approved = 'yes';
            $invoice->approval_date = now();
            if ($invoice->save()) {

                $invoice_item = InvoiceItem::where('invoice_id', $invoice->id)->first();
                if ($invoice_item) {
                    $invoice_item->approved_quantity = $item->quantity;
                    $invoice_item->quantity = 0;
                    $invoice_item->save();
                }
            }
            // Redirect to the invoices index page with type 'sale'
            $type = 'sale';
            
            return redirect()->route('invoice.index', ['type' => $type, 'approve' => 'saleapproval'])
                ->with('success', 'Invoice approved successfully.');
        }

        // Handle case where invoice is not found (optional)
        return redirect()->route('invoices.index')
            ->with('error', 'Invoice not found.');
    }





    /**
     * Validation function
     *
     * @param Request $request
     * @return void
     */
    private function validation(Request $request)
    {
        $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'invoice_date' => ['required', 'date'],
            'vat_percentage' => ['required', 'numeric'],
            'total_vat' => ['required', 'numeric'],
            'discount_pct' => ['array'],
            'total_discount' => ['numeric'],
            'paid' => ['required', 'numeric'],
            'quantity' => ['required', 'array'],
            'price' => ['required', 'array'],
            'total' => ['required', 'numeric']
        ]);
    }
}
