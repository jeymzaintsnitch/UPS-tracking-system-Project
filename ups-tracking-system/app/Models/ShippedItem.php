<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippedItem extends Model
{
    protected $fillable = [
        'item_number', 'weight', 'dimensions', 'insurance_amount', 
        'destination', 'final_delivery_date', 'retail_center_id'
    ];

    // N-to-1: A shipped item is received at one retail center
    public function retailCenter()
    {
        return $this->belongsTo(RetailCenter::class);
    }

    // M-to-N: A shipped item travels via multiple transportation events
    public function transportationEvents()
    {
        return $this->belongsToMany(TransportationEvent::class, 'shipped_item_transportation_event');
    }
}