<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Models\Audit as BaseAudit;

class Audit extends BaseAudit
{
    use HasFactory;

    protected $table = 'audits';

    protected $casts = [
    'user_id' => 'string',
    'old_values' => 'array',
    'new_values' => 'array',
];


    // Bisa tambahkan relasi atau scope custom jika perlu
}