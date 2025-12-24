<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use App\Traits\TrackUser;
use Illuminate\Notifications\Notifiable; // ⬅️ Tambahkan ini

class Customer extends Model implements Auditable
{
    use HasFactory, AuditableTrait, SoftDeletes, TrackUser, Notifiable;

    protected $keyType = 'uuid';
    public $incrementing = false;
// 'billing_cycle_id' => $billingCycle->id,
//             'registration_date' => $registrationDate,
//             'next_invoice_date' => $nextInvoiceDate,
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'username',
        'password',
        'package_id',
        'ip_pool_id',
        'expired_at',
        'status',
        'user_id',
        'created_by',
        'modified_by',
        'notified_expiring_at',
        'mikrotik_id',
        'id_number',
        'billing_cycle_id',
        'registration_date',
        'next_invoice_date',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
        // 'is_active' => 'boolean',
        'notified_expiring_at' => 'datetime',
    ];

    public function getNotifiedExpiringAtHumanAttribute()
    {
        return $this->notified_expiring_at
            ? $this->notified_expiring_at->diffForHumans()
            : null;
    }


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string)\Illuminate\Support\Str::uuid();
            }
        });
    }

    // Relasi
    public function mikrotik()
    {
        return $this->belongsTo(Mikrotik::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function ipPool()
    {
        return $this->belongsTo(IpPool::class, 'ip_pool_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function hasUnpaidInvoices()
    {
        return $this->invoices()->whereIn('status', ['unpaid', 'overdue'])->exists();
    }

    public function odp()
    {
        return $this->belongsTo(Odp::class);
    }
    public function odc()
    {
        return $this->belongsTo(Odc::class);
    }
}