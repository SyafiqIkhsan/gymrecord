<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gym Record - Keuangan</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-neutral-950 text-neutral-100 font-sans min-h-screen">

    <nav class="bg-neutral-900 border-b border-neutral-800 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-dumbbell text-lime-400 text-2xl"></i>
                    <span class="text-xl font-black tracking-wider text-white">GYM<span class="text-lime-400">RECORD</span></span>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('dashboard') }}" class="text-neutral-400 hover:text-lime-400 px-3 py-2 rounded-md text-sm font-medium transition">Dashboard</a>
                    <a href="{{ route('payments.index') }}" class="text-lime-400 bg-neutral-800 px-3 py-2 rounded-md text-sm font-bold">Keuangan</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-black text-white uppercase tracking-tight">Manajemen Keuangan</h1>
                <p class="text-neutral-400 text-sm">Akumulasi pengeluaran membership gym & Personal Trainer Anda.</p>
            </div>

            <form method="GET" action="{{ route('payments.index') }}" class="flex gap-2 w-full sm:w-auto">
                <select name="month" class="bg-neutral-900 border border-neutral-800 text-white rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lime-400 flex-1 sm:flex-none">
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ date('F', mktime(0,0,0,$m,1)) }}</option>
                    @endforeach
                </select>
                <button type="submit" class="bg-neutral-800 hover:bg-neutral-700 text-white font-bold px-4 py-2 rounded-xl text-sm transition">
                    <i class="fa-solid fa-filter"></i>
                </button>
            </form>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="bg-neutral-900 border border-neutral-800 rounded-2xl p-6 h-fit">
                <h3 class="text-lg font-black text-white uppercase tracking-tight mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-plus-circle text-lime-400"></i> Tambah Pengeluaran
                </h3>

                <form action="{{ route('payments.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold uppercase text-neutral-400 mb-2">Jenis Pengeluaran</label>
                        <select name="type" required class="w-full bg-neutral-850 border border-neutral-800 text-white rounded-xl p-3 text-sm focus:outline-none focus:border-lime-400">
                            <option value="gym_membership" class="text-black">Masuk Gymnasium</option>
                            <option value="personal_trainer" class="text-black">Personal Trainer (PT)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-neutral-400 mb-2">Nominal (Rupiah)</label>
                        <input type="text" name="amount" id="amountInput" required placeholder="Contoh: 350.000" class="w-full bg-neutral-850 border border-neutral-800 text-white rounded-xl p-3 text-sm focus:outline-none focus:border-lime-400">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-neutral-400 mb-2">Tanggal Bayar</label>
                        <input type="date" name="payment_date" required value="{{ date('Y-m-d') }}" class="w-full bg-neutral-850 border border-neutral-800 text-white rounded-xl p-3 text-sm focus:outline-none focus:border-lime-400">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-neutral-400 mb-2">Catatan Tambahan (Opsional)</label>
                        <input type="text" name="notes" class="w-full bg-neutral-850 border border-neutral-800 text-white rounded-xl p-3 text-sm focus:outline-none focus:border-lime-400">
                    </div>

                    <button type="submit" class="w-full bg-lime-400 hover:bg-lime-500 text-neutral-950 font-black py-3 rounded-xl transition uppercase tracking-wider text-xs">
                        Simpan Catatan
                    </button>
                </form>
            </div>

            <div class="bg-neutral-900 border border-neutral-800 rounded-2xl p-6 lg:col-span-2">
                <h3 class="text-lg font-black text-white uppercase tracking-tight mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-history text-lime-400"></i> Riwayat Bulan Ini
                </h3>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-neutral-800 text-xs font-bold uppercase text-neutral-400">
                                <th class="pb-3">Jenis</th>
                                <th class="pb-3">Tanggal</th>
                                <th class="pb-3 text-right">Nominal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-800/50 text-sm">
                            @forelse($payments as $payment)
                                <tr>
                                    <td class="py-4">
                                        <div class="font-bold text-white">
                                            {{ $payment->type === 'gym_membership' ? 'Membership Gym' : 'Personal Trainer' }}
                                        </div>
                                        <span class="text-xs text-neutral-500">{{ $payment->notes ?? '-' }}</span>
                                    </td>
                                    <td class="py-4 text-neutral-400">{{ $payment->payment_date->format('d M Y') }}</td>
                                    <td class="py-4 text-right font-bold text-lime-400">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-8 text-center text-neutral-500">Belum ada catatan transaksi di bulan ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const input = document.getElementById('amountInput');
            const form = input.closest('form');

            function formatRupiah(value) {
                const num = value.replace(/\D/g, '');
                return num.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }

            input.addEventListener('input', function () {
                this.value = formatRupiah(this.value);
            });

            form.addEventListener('submit', function () {
                input.value = input.value.replace(/\./g, '');
            });
        });
    </script>
</body>
</html>
