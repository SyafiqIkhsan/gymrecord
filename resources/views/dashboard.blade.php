<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gym Record - Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                    <a href="{{ route('dashboard') }}" class="text-lime-400 bg-neutral-800 px-3 py-2 rounded-md text-sm font-bold">Dashboard</a>
                    <a href="{{ route('payments.index') }}" class="text-neutral-400 hover:text-lime-400 px-3 py-2 rounded-md text-sm font-medium transition">Keuangan</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        @if(session('success'))
            <div class="mb-6 p-4 bg-lime-500/20 border border-lime-500 text-lime-400 rounded-xl flex items-center gap-2">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif

        <div class="bg-gradient-to-r from-neutral-900 to-neutral-850 border border-neutral-800 rounded-3xl p-6 mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <h1 class="text-2xl md:text-3xl font-black text-white uppercase tracking-tight">SELAMAT DATANG</h1>
                <p class="text-neutral-400 mt-1">GOWWWW KE GYM</p>
            </div>
            <form action="{{ route('attendance.checkin') }}" method="POST" class="w-full md:w-auto">
                @csrf
                <button type="submit" class="w-full md:w-auto bg-lime-400 hover:bg-lime-500 text-neutral-950 font-black px-6 py-4 rounded-2xl shadow-lg shadow-lime-400/20 transition transform active:scale-95 flex items-center justify-center gap-2 uppercase tracking-wider text-sm">
                    <i class="fa-solid fa-calendar-check text-base"></i> Check-In Gym Sekarang
                </button>
            </form>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <div class="bg-neutral-900 border border-neutral-800 rounded-2xl p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-bold text-neutral-400 uppercase tracking-wider">Target Mingguan</p>
                        <h3 class="text-3xl font-black text-white mt-2">3x <span class="text-xs font-normal text-neutral-500">Seminggu</span></h3>
                    </div>
                    <div class="p-3 bg-neutral-800 text-lime-400 rounded-xl"><i class="fa-solid fa-bullseye text-xl"></i></div>
                </div>
            </div>

            <div class="bg-neutral-900 border border-neutral-800 rounded-2xl p-6 sm:col-span-1 lg:col-span-2">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-bold text-neutral-400 uppercase tracking-wider">Total Biaya Bulan Ini</p>
                        <h3 class="text-3xl font-black text-lime-400 mt-2">Rp {{ number_format($totalGym + $totalPT, 0, ',', '.') }}</h3>
                        <p class="text-xs text-neutral-500 mt-1">Gym: Rp {{ number_format($totalGym, 0, ',', '.') }} | PT: Rp {{ number_format($totalPT, 0, ',', '.') }}</p>
                    </div>
                    <div class="p-3 bg-neutral-800 text-neutral-400 rounded-xl"><i class="fa-solid fa-wallet text-xl"></i></div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="bg-neutral-900 border border-neutral-800 rounded-2xl p-6 lg:col-span-2">
                <h3 class="text-lg font-black text-white uppercase tracking-tight mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-chart-bar text-lime-400"></i> Statistik Kehadiran Per Minggu
                </h3>
                <div class="h-64 relative">
                    <canvas id="attendanceChart"></canvas>
                </div>
            </div>

            <div class="bg-neutral-900 border border-neutral-800 rounded-2xl p-6">
                <h3 class="text-lg font-black text-white uppercase tracking-tight mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-clock text-lime-400"></i> Jadwal Latihanmu
                </h3>
                <div class="space-y-2">
                    @foreach($daysOfWeek as $day)
                        <div class="flex items-center justify-between p-3 rounded-xl border {{ $day['is_today'] ? 'bg-lime-400/10 border-lime-400/50' : 'bg-neutral-850 border-neutral-800' }}">
                            <div class="flex items-center gap-3">
                                @if($day['is_today'])
                                    <span class="text-xs font-bold text-lime-400">HARI INI</span>
                                @endif
                                <span class="font-bold text-sm {{ $day['is_today'] ? 'text-white' : 'text-neutral-300' }}">{{ $day['name'] }}</span>
                                <span class="text-xs text-neutral-500">{{ \Carbon\Carbon::parse($day['date'])->format('d/m') }}</span>
                            </div>
                            @if($day['attended'])
                                <span class="px-3 py-1 bg-lime-400/10 text-lime-400 font-extrabold text-xs rounded-md flex items-center gap-1">
                                    <i class="fa-solid fa-check"></i> HADIR
                                </span>
                            @else
                                <span class="px-3 py-1 bg-red-400/10 text-red-400 font-extrabold text-xs rounded-md flex items-center gap-1">
                                    <i class="fa-solid fa-xmark"></i> TIDAK
                                </span>
                            @endif
                        </div>
                    @endforeach
                    <div class="flex items-center justify-between p-3 bg-neutral-850 rounded-xl border border-neutral-800 mt-3">
                        <span class="text-neutral-400 text-sm">Target Mingguan:</span>
                        <span class="font-bold text-lime-400 text-sm">{{ collect($daysOfWeek)->where('attended', true)->count() }}/3x</span>
                    </div>
                    @if(collect($daysOfWeek)->where('attended', true)->count() >= 3)
                        <div class="p-3 bg-lime-500/10 border border-lime-500/30 rounded-xl flex items-center gap-2 mt-2">
                            <i class="fa-solid fa-trophy text-lime-400"></i>
                            <span class="text-lime-400 font-bold text-xs">Kamu sudah mencapai target minggu ini!</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </main>

    <script>
        const ctx = document.getElementById('attendanceChart').getContext('2d');

        // Membaca data dinamis dari backend Laravel
        const rawStats = @json($stats);
        const labels = Object.keys(rawStats).length ? Object.keys(rawStats) : ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'];
        const dataValues = Object.keys(rawStats).length ? Object.values(rawStats) : [0, 0, 0, 0];

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Datang Gym',
                    data: dataValues,
                    backgroundColor: '#a3e635', // lime-400
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { color: '#a3a3a3' } },
                    y: {
                        grid: { color: '#262626' },
                        ticks: { color: '#a3a3a3', stepSize: 1 },
                        suggestedMax: 4
                    }
                }
            }
        });
    </script>
</body>
</html>
