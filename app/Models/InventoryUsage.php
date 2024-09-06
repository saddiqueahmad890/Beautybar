<?php

// InventoryUsage.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryUsage extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_id',
        'consumed_quantity',
        'unit_sale_price',
        'date',
        'type',
        'description',
        'sold_qty',
        'purchased_qty',
        'unit_cost',
        'supplier',
        'created_by',
        'updated_by',
    ];

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }


    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}

