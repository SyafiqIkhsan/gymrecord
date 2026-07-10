<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\GymSchedule;
use App\Models\AttendanceLog;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Hubungan ke Jadwal Gym (Satu user punya 1 pengaturan jadwal aktif)
     */
    public function gymSchedule(): HasOne
    {
        return $this->hasOne(GymSchedule::class);
    }

    /**
     * Hubungan ke Catatan Kehadiran (Satu user punya banyak riwayat absen)
     */
    public function attendanceLogs(): HasMany
    {
        return $this->hasMany(AttendanceLog::class);
    }

    /**
     * Hubungan ke Catatan Pengeluaran (Satu user punya banyak riwayat bayar)
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
