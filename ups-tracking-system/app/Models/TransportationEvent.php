<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransportationEvent extends Model
{
    protected $fillable = ['schedule_number', 'type', 'delivery_route'];

    // M-to-N: A transportation event carries many shipped items
    public function shippedItems()
    {
        return $this->belongsToMany(ShippedItem::class, 'shipped_item_transportation_event');
    }
}