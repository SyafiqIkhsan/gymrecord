<?php

namespace App\Http\Controllers;

use App\Models\AttendanceLog;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceLogController extends Controller
{
    /**
     * Menyimpan data kehadiran (Check-In) baru ke database.
     */
    public function store(Request $request)
    {
        $today = Carbon::today()->toDateString();

        // VALIDASI OPTIONAL: Mencegah double check-in di hari yang sama
        $alreadyCheckedIn = AttendanceLog::whereDate('attended_at', $today)->exists();

        if ($alreadyCheckedIn) {
            return redirect()->route('dashboard')
                ->with('success', 'Kamu sudah melakukan check-in hari ini. Sampai jumpa di sesi latihan berikutnya!');
        }

        // Simpan data absensi hari ini ke database
        AttendanceLog::create([
            'attended_at' => Carbon::today(),
        ]);

        // Redirect kembali ke dashboard dengan alert sukses
        return redirect()->route('dashboard')
            ->with('success', 'Berhasil mencatat kehadiran hari ini! Selamat berlatih!');
    }
}
