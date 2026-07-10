<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GymSchedule extends Model
{
    // Kolom yang boleh diisi secara massal
    protected $fillable = [
        'user_id',
        'target_per_week',
        'target_days',
        'reminder_time',
    ];

    // Mengubah string JSON dari database menjadi Array PHP secara otomatis
    protected $casts = [
        'target_days' => 'array',
    ];

    /**
     * Relasi balik ke User (Jadwal ini milik siapa)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
