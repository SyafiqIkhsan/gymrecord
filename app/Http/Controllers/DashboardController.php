<?php

namespace App\Http\Controllers;

use App\Models\AttendanceLog;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // 1. Ambil semua log absensi bulan ini (Tanpa Filter User)
        $logs = AttendanceLog::whereMonth('attended_at', $currentMonth)
            ->whereYear('attended_at', $currentYear)
            ->orderBy('attended_at', 'asc')
            ->get()
            ->groupBy(function($date) {
                return 'Minggu ke-' . Carbon::parse($date->attended_at)->format('W');
            });

        $stats = [];
        foreach ($logs as $weekLabel => $weekLogs) {
            $stats[$weekLabel] = $weekLogs->count();
        }

        // 2. Ambil semua pengeluaran bulan ini (Tanpa Filter User)
        $payments = Payment::whereMonth('payment_date', $currentMonth)
            ->whereYear('payment_date', $currentYear)
            ->get();

        $totalGym = $payments->where('type', 'gym_membership')->sum('amount');
        $totalPT = $payments->where('type', 'personal_trainer')->sum('amount');

        // 3. Ambil absensi minggu ini untuk jadwal harian
        $today = Carbon::today();
        $startOfWeek = $today->copy()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = $today->copy()->endOfWeek(Carbon::SUNDAY);

        $weeklyAttendance = AttendanceLog::whereBetween('attended_at', [$startOfWeek, $endOfWeek])
            ->pluck('attended_at')
            ->map(fn($date) => Carbon::parse($date)->format('Y-m-d'))
            ->toArray();

        $dayNames = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        $daysOfWeek = [];
        for ($i = 0; $i < 7; $i++) {
            $date = $startOfWeek->copy()->addDays($i);
            $dateStr = $date->format('Y-m-d');
            $daysOfWeek[] = [
                'name' => $dayNames[$i],
                'date' => $dateStr,
                'attended' => in_array($dateStr, $weeklyAttendance),
                'is_today' => $dateStr === $today->format('Y-m-d'),
            ];
        }

        return view('dashboard', compact('stats', 'totalGym', 'totalPT', 'daysOfWeek'));
    }
}
