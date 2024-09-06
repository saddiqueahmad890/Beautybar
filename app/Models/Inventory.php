<?php

namespace App\Models;

use App\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory, Loggable;
    protected $fillable = [
        'item_id',
        'category_id',
        'quantity',
        'purchased_qty',
        'unit_cost',
        'unit_sale',
        'description',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function subcategory()
    {
        return $this->belongsTo(SubCategory::class, 'subcategory_id');
    }

    public function usages()
    {
        return $this->hasMany(InventoryUsage::class, 'inventory_id');
    }
    public function invoiceitem()
    {
        return $this->hasMany(InvoiceItem::class, 'inventory_id');
    }
    protected $table = 'inventories';

    // Boot method to log actions
    public static function boot()
    {
        parent::boot();

        // Log creation
        static::created(function ($model) {
            $model->logAction('created');
        });

        // Log update
        static::updated(function ($model) {
            $model->logAction('updated');
        });

        // Log deletion
        static::deleted(function ($model) {
            $model->logAction('deleted');
        });
    }

    // Method to log actions
    public function logAction($action)
    {
        // dd($this->unit_sale);
        TableLog::create([
            'item_id' => $this->id,
            'supplier' => $this->supplier,
            'category_id' => $this->category_id,
            'quantity' => $this->quantity,
            'description' => $this->description,
            'purchased_qty' => $this->purchased_qty,
            'unit_cost' => $this->unit_cost,
            'unit_sale' => $this->unit_sale,
            'action' => $action,
            'action_timestamp' => now(),
            'action_by' => auth()->user()->id ?? null,
        ]);
    }
}

