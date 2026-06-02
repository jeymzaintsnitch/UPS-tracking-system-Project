<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'user_id', 'action', 'entity', 'entity_id', 'old_values', 'new_values'
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    // N-to-1: An audit log belongs to the user who triggered it
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
