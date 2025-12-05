<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{ BelongsTo, HasMany };

class Transaction extends Model
{
    protected $fillable = [
        'campaign_id',
        'phone',
        'status'
    ];

    protected function casts(): array
    {
        return [
            'campaign_id' => 'integer',
            'phone' => 'string',
            'status' => 'boolean'
        ];
    }

    public function campaign(): BelongsTo {
        return $this->belongsTo(Campaign::class);
    }
}
