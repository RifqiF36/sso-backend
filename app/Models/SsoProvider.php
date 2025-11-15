<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SsoProvider extends Model
{
    protected $primaryKey = 'provider_id';

    protected $casts = [
        'scopes' => 'array',
        'enabled' => 'boolean',
    ];

    protected $fillable = [
        'provider_id',
        'name',
        'authorize_url',
        'token_url',
        'userinfo_url',
        'redirect_uri',
        'client_id',
        'client_secret',
        'scopes',
        'enabled',
    ];
}

