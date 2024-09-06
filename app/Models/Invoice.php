<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Models\UserLogs;
use App\services\UserLogServices;
use App\Traits\Loggable;
use Illuminate\Support\Facades\Auth;



class Invoice extends Model
{
    use Loggable;
    protected $fillable = [
        'company_id',
        'user_id',
        'patient_treatment_plan_id',
        'invoice_date',
        'total',
        'vat_percentage',
        'total_vat',
        'discount_percentage',
        'total_discount',
        'grand_total',
        'paid',
        'due',
        'invoice_type',
        'invoice_approved',
        'approval_date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function insurance()
    {
        return $this->belongsTo(Insurance::class);
    }

    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function patienttreatmentplan()
    {
        return $this->belongsTo(PatientTreatmentPlan::class,'patient_treatment_plan_id');
    }

    public function invoicePayments()
    {
        return $this->hasMany(InvoicePayment::class);
    }
    public function doctor()
    {
        return $this->belongsTo(DoctorDetail::class);
    }
    // In Invoice model

    // In Invoice model
    public function items()
    {
        return $this->hasMany(Item::class, ); // Replace 'foreign_key' with the actual column name
    }

}
