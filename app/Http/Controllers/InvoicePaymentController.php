<?php

namespace App\Http\Controllers;

use App\Models\InvoicePayment;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoicePaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'paid_amount' => 'required|numeric|min:0',
        ]);

        $invoiceId = $request->input('invoice_id');
        $paidAmount = $request->input('paid_amount');
        $remainingBalance = $this->getRemainingBalance($invoiceId);

        if ($paidAmount > $remainingBalance) {
            return response()->json(['error' => 'Paid amount exceeds remaining balance.'], 400);
        }

        $invoicePayment = new InvoicePayment([
            'invoice_id' => $invoiceId,
            'paid_amount' => $paidAmount,
            'payment_type' => $request->input('payment_type'),
            'insurance_id' => $request->input('insurance_id'),
            'comments' => $request->input('comments'),
        ]);

        $invoicePayment->save();


        $invoice = Invoice::find($invoicePayment->invoice_id);
        $invoice->paid += $invoicePayment->paid_amount;
        $invoice->due -= $invoicePayment->paid_amount;
        $invoice->save();

        return response()->json(['success' => 'Payment recorded successfully.', 'invoicePayment'=>$invoicePayment]);
    }


    private function getRemainingBalance($invoiceId)
    {
        $invoice = Invoice::find($invoiceId);
        $totalPaid = $invoice->invoicePayments()->sum('paid_amount');
        return $invoice->grand_total - $totalPaid;
    }

    public function remainingBalance($invoiceId)
    {
        $remainingBalance = $this->getRemainingBalance($invoiceId);
        return response()->json(['remaining_balance' => $remainingBalance]);
    }

    public function fetchPaidAmount($id)
    {   $invoice=Invoice::find($id);
        $paidAmount = InvoicePayment::where('invoice_id', $id)->sum('paid_amount');
        $due_amount=$invoice->grand_total-$paidAmount;
        return response()->json(['paid_amount' => $paidAmount,'due_amount'=>$due_amount]);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\InvoicePayment  $invoicePayment
     * @return \Illuminate\Http\Response
     */
    public function show(InvoicePayment $invoicePayment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\InvoicePayment  $invoicePayment
     * @return \Illuminate\Http\Response
     */
    public function edit(InvoicePayment $invoicePayment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\InvoicePayment  $invoicePayment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InvoicePayment $invoicePayment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InvoicePayment  $invoicePayment
     * @return \Illuminate\Http\Response
     */
    public function destroy(InvoicePayment $invoicePayment)
    {
        //
    }
}
