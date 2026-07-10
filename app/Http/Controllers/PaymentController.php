<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);

        // Mengambil semua data tanpa filter user_id
        $payments = Payment::whereMonth('payment_date', $month)
            ->whereYear('payment_date', $year)
            ->orderBy('payment_date', 'desc')
            ->get();

        $totalGym = $payments->where('type', 'gym_membership')->sum('amount');
        $totalPT = $payments->where('type', 'personal_trainer')->sum('amount');
        $grandTotal = $totalGym + $totalPT;

        return view('payments.index', compact('payments', 'totalGym', 'totalPT', 'grandTotal', 'month', 'year'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:gym_membership,personal_trainer',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string|max:255',
        ]);

        // Simpan langsung tanpa user_id
        Payment::create([
            'type' => $request->type,
            'amount' => $request->amount,
            'payment_date' => $request->payment_date,
            'notes' => $request->notes,
        ]);

        return redirect()->route('payments.index')->with('success', 'Catatan pengeluaran disimpan!');
    }
}
