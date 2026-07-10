<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceLog extends Model
{
    protected $fillable = [
        'attended_at', // Cukup simpan tanggal kedatangan saja
    ];

    protected $casts = [
        'attended_at' => 'date',
    ];
}
