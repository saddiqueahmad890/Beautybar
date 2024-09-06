<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consume extends Model
{
    use HasFactory;

    // Specify the table name
    protected $table = 'inventory_usages'; // replace with your actual table name

    protected $fillable = [
        'inventory_id',
        'consumed_quantity',
        'date',
        'approved',
        'created_by',
        'updated_by',
    ];

    // Relationships
    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }
}
