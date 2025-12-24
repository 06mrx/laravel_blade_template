<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationLog extends Model
{
    protected $keyType = 'uuid';
    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = [
        'customer_id',
        'mikrotik_id',
        'type',
        'subject',
        'message',
        'success',
        'error',
        'sent_at'
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'success' => 'boolean',
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

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function mikrotik()
    {
        return $this->belongsTo(Mikrotik::class);
    }
}
