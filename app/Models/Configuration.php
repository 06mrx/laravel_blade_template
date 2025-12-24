<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use App\Traits\TrackUser;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
class Configuration extends Model implements Auditable
{
    use AuditableTrait, SoftDeletes, TrackUser;

    protected $keyType = 'uuid';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'business_name',
        'business_logo',
        'midtrans_client_key',
        'midtrans_server_key',
        'payment_type_id',
        'created_by',
        'modified_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Mutator: Auto-generate UUID
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = (string) \Str::uuid();
            }
            if (auth()->check()) {
                $model->created_by = auth()->id();
            }
        });

        static::saving(function ($model) {
            if (auth()->check()) {
                $model->modified_by = auth()->id();
            }
        });
    }

    // Accessor: decrypt server key
    // public function getMidtransServerKeyAttribute($value)
    // {
    //     return $value ? Crypt::decryptString($value) : null;
    // }

    // Mutator: encrypt server key
    // public function setMidtransServerKeyAttribute($value)
    // {
    //     $this->attributes['midtrans_server_key'] = $value ? Crypt::encryptString($value) : null;
    // }

    // Accessor: full URL logo
    public function getBusinessLogoUrlAttribute()
    {
        return $this->business_logo ? Storage::url($this->business_logo) : null;
    }

    // Relasi (opsional): siapa yang buat/ubah
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function modifier()
    {
        return $this->belongsTo(User::class, 'modified_by', 'id');
    }
}
