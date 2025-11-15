<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SsoIdentity extends Model
{
    protected $fillable = [
        'provider_id',
        'provider_subject',
        'user_id',
        'raw',
    ];

    protected $casts = [
        'raw' => 'array',
    ];
}

