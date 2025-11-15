<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $primaryKey = 'permission_id';

    protected $fillable = [
        'name',
        'module',
        'description',
    ];
}

