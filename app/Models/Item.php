<?php

namespace App\Models;

use App\services\UserLogServices;
use Illuminate\Support\Facades\Auth;
use App\Models\UserLogs;
use App\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory, Loggable;

    protected $fillable = [
        'category_id',
        'title',
        'type',
        'description',
        'quantity',
        'created_by',
        'updated_by',
        'item_date'
    ];


    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function inventory()
    {
        return $this->hasMany(Inventory::class);
    }
    public function stockInventoryPurchases()
    {
        return $this->hasMany(StockInventoryPurchase::class);
    }
    // In Item model
    public function invoice()
    {
        return $this->belongsTo(Invoice::class); // Replace 'foreign_key' with the actual column name
    }
}
