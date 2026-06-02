<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RetailCenter extends Model
{
    protected $fillable = ['unique_id', 'type', 'address'];

    // 1-to-N: A retail center processes many shipped items
    public function shippedItems()
    {
        return $this->hasMany(ShippedItem::class);
    }
}