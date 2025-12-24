<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use App\Traits\TrackUser;

class Mikrotik extends Model implements Auditable
{
    use HasFactory, AuditableTrait, SoftDeletes, TrackUser;

    protected $keyType = 'uuid';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'ip_address',
        'port',
        'username',
        'password',
        'status',
        'description',
        'last_checked_at',
        'created_by',
        'modified_by',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'last_checked_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string)\Illuminate\Support\Str::uuid();
            }
        });
    }

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    // Relasi ke IP Pool
    public function ipPools()
    {
        return $this->hasMany(IpPool::class, 'mikrotik_id');    
    }
    // Relasi ke Package
    public function packages()
    {
        return $this->hasMany(Package::class, 'mikrotik_id');
    }

    public function odc ()
    {
        return $this->hasMany(Odc::class);
    }
}