<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\UserProfile;

class User extends Authenticatable
{
    use HasApiTokens;
    use Notifiable;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'nip',
        'jenis_kelamin',
        'phone',
        'role_id',
        'dinas_id',
        'unit_kerja_id',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class);
    }

        public function role()
        {
            return $this->belongsTo(Role::class, 'role_id');
        }

        public function dinas()
        {
            return $this->belongsTo(Dinas::class, 'dinas_id');
        }

        public function unitkerja()
        {
            return $this->belongsTo(Unitkerja::class, 'unit_kerja_id');
        }
}

