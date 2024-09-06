<?php

namespace App\Models;

use App\services\UserLogServices;
use App\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use Loggable;
    protected $fillable = [
        'company_id',
        'invoice_id',
        'procedure_id',
        'title',
        'account_name',
        'account_type',
        'quantity',
        'price',
        'inventory_id',
        'discount_pct',
        'doctor_id',
        'commission'

    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
    public function ddprocedure()
    {
        return $this->belongsTo(DdProcedure::class, 'procedure_id');
    }
    // public function patientTreatmentPlanProcedures()
    // {
    //     return $this->belongsTo(PatientTreatmentPlanProcedure::class, 'procedure_id');
    // }
    
}
