<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use App\Traits\TrackUser;
class BillingCycle extends Model implements Auditable
{
    use HasUuids, AuditableTrait, TrackUser, SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'due_days',
        'mikrotik_id',
        'user_id',
        'is_default',
    ];

    protected $casts = [
        'due_days' => 'array',
        'is_default' => 'boolean',
    ];

    // Relasi
    public function mikrotik(): BelongsTo
    {
        return $this->belongsTo(Mikrotik::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class, 'billing_cycle_id');
    }

    // Scope: default per mikrotik
    public function scopeDefaultForMikrotik($query, $mikrotikId)
    {
        return $query->where('mikrotik_id', $mikrotikId)->where('is_default', true);
    }

    // Helper: dapatkan jatuh tempo berikutnya
    public function getNextDueDate($registrationDate): \Carbon\Carbon
    {
        $regDate = \Carbon\Carbon::parse($registrationDate);

        if ($this->type === 'anniversary') {
            $next = $regDate->copy()->addMonth();
            // Jika 31 Februari → auto ke 28/29
            if ($next->day !== $regDate->day) {
                $next = $next->endOfMonth();
            }
            return $next;
        }

        if ($this->type === 'fixed') {
            $day = $this->due_days[0];
            $next = $regDate->copy()->startOfMonth()->addDays($day - 1);
            if ($next->lt($regDate)) {
                $next->addMonth();
            }
            return $next;
        }

        // if ($this->type === 'segmented') {
        //     $regDay = $regDate->day;
        //     $dueDays = collect($this->due_days)->sort()->values();

        //     $targetDay = $dueDays->first(function ($day) use ($regDay) {
        //         return $day >= $regDay;
        //     });

        //     if (!$targetDay) {
        //         // Ambil yang pertama bulan depan
        //         $next = $regDate->copy()->addMonth()->startOfMonth();
        //         $targetDay = $dueDays->first();
        //     } else {
        //         $next = $regDate->copy()->startOfMonth();
        //     }

        //     return $next->addDays($targetDay - 1);
        // }

        if ($this->type === 'segmented') {
            $regDay = $regDate->day;
            $daysInMonth = $regDate->daysInMonth;
            $dueDays = collect($this->due_days)->sort()->values();

            // Step 1–2: tambah 30 hari
            $baseDate = $regDate->copy()->addDays(30);
            $baseDay = $baseDate->day;

            $nearest = null;
            $minDistance = $daysInMonth + 1;

            foreach ($dueDays as $day) {
                $forward = ($day - $baseDay + $daysInMonth) % $daysInMonth;
                $backward = ($baseDay - $day + $daysInMonth) % $daysInMonth;
                $distance = min($forward, $backward);

                if ($distance < $minDistance) {
                    $minDistance = $distance;
                    $nearest = $day;
                }
            }

            // tentukan bulan target
            $target = $baseDate->copy()->startOfMonth()->addDays($nearest - 1);

            return $target;
        }


        return $regDate->addMonth(); // fallback
    }
}