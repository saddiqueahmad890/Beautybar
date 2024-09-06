<?php

namespace App\Models;

use App\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DdMedicine extends Model
{
    use HasFactory,Loggable;

    protected $fillable = ['name', 'status', 'dd_medicine_type',];

    public function type()
    {
        return $this->belongsTo(DdMedicineType::class);
    }

    public function medicineType()
    {
        return $this->belongsTo(DdMedicineType::class, 'dd_medicine_type', 'id');
    }
}
