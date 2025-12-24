<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use App\Traits\TrackUser;

class Invoice extends Model implements Auditable
{
    use AuditableTrait, SoftDeletes, TrackUser;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'invoice_number',
        'customer_id',
        'package_id',
        'amount',
        'issue_date',
        'due_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'amount' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = (string) \Str::uuid();
            }
            if (!$model->invoice_number) {
                $model->invoice_number = self::generateInvoiceNumber();
            }
        });
    }

    public static function generateInvoiceNumber()
    {
        $prefix = 'INV-' . now()->format('Ym');
        $last = self::where('invoice_number', 'like', $prefix . '%')
            ->orderBy('created_at', 'desc')
            ->first();

        $number = $last ? (int) substr($last->invoice_number, -4) + 1 : 1;

        return $prefix . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    // Relasi
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    // Accessor: formatted amount
    public function getFormattedAmountAttribute()
    {
        return 'Rp' . number_format($this->amount, 0, ',', '.');
    }

    // Scope: belum dibayar & belum lewat jatuh tempo
    public function scopeUnpaid($query)
    {
        return $query->where('status', 'unpaid');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'unpaid')->where('due_date', '<', now());
    }
}