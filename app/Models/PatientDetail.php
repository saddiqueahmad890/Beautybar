<?php

namespace App\Models;
use App\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;
use App\Models\UserLogs;
use App\services\UserLogServices;
use Illuminate\Support\Facades\Auth;

class PatientDetail extends Model
{ 
    use Loggable;
    protected $fillable = [
        "user_id",
        "mrn_number",
        "marital_status",
        "insurance_number",
        "insurance_provider",
        "cnic",
        'credit_balance',
        'area',
        'enquirysource',
        'city',
        "cnic_file",
        "insurance_card",
        "other_files",
        'other_details',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function enquirysource()
    {
        return $this->belongsTo(EnquirySource::class, 'enquirysource' );
    }
    public function maritalStatus()
    {
        return $this->belongsTo(MaritalStatus::class, 'marital_status');
    }


}
