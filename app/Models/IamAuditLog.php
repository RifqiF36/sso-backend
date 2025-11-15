<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IamAuditLog extends Model
{
    protected $fillable = [
        'actor_id',
        'action',
        'payload',
        'ip',
    ];
}

