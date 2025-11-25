<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProfile extends Model
{
    protected $fillable = [
        'user_id',
        'nip',
        'gender',
        'unit_kerja',
        'asal_dinas',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

