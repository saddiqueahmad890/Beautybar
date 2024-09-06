<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TableLog extends Model
{
    use HasFactory;
    protected $table = 'table_logs';
    protected $fillable = [
        'item_id',
        'supplier',
        'category_id',
        'quantity',
        'description',
        'purchased_qty',
        'unit_cost',
        'unit_sale',
        'action',
        'action_timestamp',
        'action_by'
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
}
