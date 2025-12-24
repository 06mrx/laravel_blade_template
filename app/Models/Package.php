<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use App\Traits\TrackUser;

class Package extends Model implements Auditable
{
    use HasFactory, AuditableTrait, SoftDeletes, TrackUser;

    protected $keyType = 'uuid';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'type',
        'speed_up',
        'speed_down',
        'duration_days',
        'quota',
        'price',
        'description',
        'user_id',
        'created_by',
        'modified_by',
        'mikrotik_id'
    ];

    protected $casts = [
        'duration_days' => 'integer',
        'quota' => 'integer',
        'price' => 'decimal:2',
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
    public function mikrotik()
    {
        return $this->belongsTo(Mikrotik::class);
    }
}