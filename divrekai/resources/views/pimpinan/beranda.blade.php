@extends('layouts.app')

@section('title', 'Dashboard Pimpinan')

@section('content')
<div class="max-w-8xl mx-auto px-6 py-8 bg-white min-h-screen">

    {{-- ===================== --}}
    {{-- JUDUL --}}
    {{-- ===================== --}}
    <h1 class="text-2xl font-bold text-[#231f5c] text-center mb-6">
        Laporan Pendapatan
        {{ request('unit_id')
            ? 'Unit ' . optional($navbarUnits->firstWhere('id', request('unit_id')))->nama_unit
            : 'Keseluruhan Unit'
        }}
    </h1>

    {{-- ===================== --}}
    {{-- FILTER TANGGAL --}}
    {{-- ===================== --}}
    <form method="GET" action="{{ route('pimpinan.beranda') }}" class="mb-6">
        <div class="flex items-end gap-4 text-sm">
            <div class="flex flex-col">
                <label class="mb-1">Tanggal</label>
                <input type="date"
                       name="tanggal"
                       value="{{ request('tanggal') }}"
                       onchange="this.form.submit()"
                       class="border rounded px-3 py-2 w-48">
            </div>
        </div>
    </form>

    {{-- ===================== --}}
    {{-- RINGKASAN --}}
    {{-- ===================== --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-[#231f5c] text-white rounded-xl p-6 shadow">
            <p class="text-sm">Total Pendapatan Hari Ini</p>
            <p class="text-3xl font-bold mt-3">
                Rp {{ number_format($hariIni ?? 0, 0, ',', '.') }}
            </p>
            <p class="text-xs mt-2">{{ now()->translatedFormat('d F Y') }}</p>
        </div>

        <div class="bg-orange-600 text-white rounded-xl p-6 shadow">
            <p class="text-sm">Total Pendapatan Bulan Ini</p>
            <p class="text-3xl font-bold mt-3">
                Rp {{ number_format($bulanIni ?? 0, 0, ',', '.') }}
            </p>
            <p class="text-xs mt-2">{{ now()->translatedFormat('F Y') }}</p>
        </div>
    </div>

    {{-- ===================== --}}
    {{-- SECTION GRAFIK --}}
    {{-- ===================== --}}
    <div class="my-10">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- PIE CHART --}}
            <div class="bg-white rounded-xl shadow p-6 h-[320px] flex items-center justify-center">
                <div class="w-[400px] h-[300px]">
                    <canvas id="pieChart"></canvas>
                </div>
            </div>

            {{-- BAR CHART --}}
            <div class="bg-white rounded-xl shadow p-4 h-[320px]">
                <canvas id="barChart"></canvas>
            </div>

        </div>
    </div>

    {{-- ===================== --}}
    {{-- FILTER + TABEL HARIAN --}}
    {{-- ===================== --}}
    <form method="GET" id="filterFormHarian" class="mb-4">
        <div class="flex justify-between items-center mb-4">

            <div onclick="openDatePicker()"
                 class="flex items-center gap-2 bg-orange-600 text-white px-4 py-2 rounded cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>Laporan Pendapatan Harian</span>
            </div>

            <div class="flex items-center gap-4 text-sm">
                <div>
                    Show
                    <select name="limit_harian" onchange="this.form.submit()" class="border rounded px-2 py-1">
                        @foreach([4,10,25,50] as $l)
                            <option value="{{ $l }}" {{ request('limit_harian',4)==$l?'selected':'' }}>
                                {{ $l }}
                            </option>
                        @endforeach
                    </select>
                    Entries
                </div>

                <div>
                    Search:
                    <input type="text" name="search_harian"
                           value="{{ request('search_harian') }}"
                            onkeydown="if(event.key==='Enter') document.getElementById('filterFormHarian').submit()"
                           class="border rounded px-2 py-1">
                </div>
            </div>
        </div>

        <input type="date" id="filterTanggal" name="tanggal" value="{{ request('tanggal') }}" class="hidden">
    </form>

    <div class="overflow-x-auto">
        <table class="w-full border text-sm rounded-lg">
            <thead class="bg-[#231f5c] text-white">
                <tr>
                    <th class="px-4 py-3">No</th>
                    <th class="px-4 py-3">Tanggal</th>
                    <th class="px-4 py-3">Unit</th>
                    <th class="px-4 py-3">Sumber</th>
                    <th class="px-4 py-3">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendapatan as $item)
                    <tr class="border-t text-center hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">{{ $item->unit->nama_unit ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $item->keterangan ?? '-' }}</td>
                        <td class="px-4 py-3 font-semibold">
                            Rp {{ number_format($item->jumlah,0,',','.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-4 text-center text-gray-500">Belum ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="flex justify-between items-center mt-4 text-sm text-gray-600">
        <div>
            Showing {{ $pendapatan->firstItem() }} to {{ $pendapatan->lastItem() }}
            of {{ $pendapatan->total() }} Entries
        </div>
        <div>{{ $pendapatan->withQueryString()->links() }}</div>
    </div>

    {{-- ===================== --}}
    {{-- REKAP BULANAN --}}
    {{-- ===================== --}}
    <div class=" mt-12">

        <form method="GET" id="filterFormBulanan" class="mb-4">
            <div class="flex justify-between items-center">

            <div onclick="openMonthPicker()"
                class="flex items-center gap-2 bg-[#231f5c] text-white px-4 py-2 rounded cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14
                        a2 2 0 002-2V7
                        a2 2 0 00-2-2H5
                        a2 2 0 00-2 2v12
                        a2 2 0 002 2z"/>
                </svg>
                    <span>Rekapan Pendapatan Bulanan</span>
                </div>

                <div class="flex items-center gap-4 text-sm">
                    <div>
                        Show
                        <select name="limit_bulanan" onchange="this.form.submit()" class="border rounded px-2 py-1">
                            @foreach([4,10,25,50] as $l)
                                <option value="{{ $l }}" {{ request('limit_bulanan',4)==$l?'selected':'' }}>
                                    {{ $l }}
                                </option>
                            @endforeach
                        </select>
                        Entries
                    </div>

                    <div>
                        Search:
                        <input type="text" name="search_bulanan"
                               value="{{ request('search_bulanan') }}"
                               class="border rounded px-2 py-1">
                    </div>
                </div>
            </div>

            <input type="month" id="filterBulan" name="bulan" value="{{ request('bulan') }}" class="hidden">
            <input type="hidden" name="tanggal" value="{{ request('tanggal') }}">
            <input type="hidden" name="search_harian" value="{{ request('search_harian') }}">
            <input type="hidden" name="limit_harian" value="{{ request('limit_harian') }}">
        </form>

        <div class="overflow-x-auto">
            <table class="w-full border text-sm rounded-lg">
                <thead class="bg-orange-600 text-white">
                    <tr>
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3">Bulan</th>
                        <th class="px-4 py-3">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rekapBulanan as $row)
                        <tr class="border-t text-center hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3">
                                {{ \Carbon\Carbon::create($row->tahun, $row->bulan)->translatedFormat('F Y') }}
                            </td>
                            <td class="px-4 py-3 font-semibold">
                                Rp {{ number_format($row->total,0,',','.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-4 text-center text-gray-500">Belum ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex justify-between items-center mt-4 text-sm text-gray-600">
            <div>
                Showing {{ $rekapBulanan->firstItem() }} to {{ $rekapBulanan->lastItem() }}
                of {{ $rekapBulanan->total() }} Entries
            </div>
            <div>{{ $rekapBulanan->withQueryString()->links() }}</div>
        </div>
    </div>

</div>

{{-- ===================== --}}
{{-- SCRIPT --}}
{{-- ===================== --}}
<script>
function openDatePicker() {
    const input = document.getElementById('filterTanggal');
    if (!input) return;

    if (input.showPicker) {
        input.showPicker();
    } else {
        input.focus();
    }
}

// INI YANG BIKIN FILTER JALAN
document.getElementById('filterTanggal')?.addEventListener('change', function () {
    document.getElementById('filterFormHarian').submit();
});

function openMonthPicker() {
    const input = document.getElementById('filterBulan');

    if (input.showPicker) {
        input.showPicker(); // Chrome, Edge
    } else {
        input.focus(); // fallback
    }
}
document.getElementById('filterBulan')
    ?.addEventListener('change', function () {
        document.getElementById('filterFormBulanan').submit();
    });
</script>

<style>
nav[role="navigation"] > div > div:first-child {
    display: none !important;
}
</style>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>


<script>
document.addEventListener('DOMContentLoaded', function () {


    const pieCtx = document.getElementById('pieChart');
    const barCtx = document.getElementById('barChart');

    //pie chart
    if (!pieCtx) return;
        const pieLabels = @json($pieData->pluck('label'));
        const pieValues = @json($pieData->pluck('total'));
        const totalPie = pieValues.reduce((a, b) => a + b, 0);
    new Chart(pieCtx, {
        type: 'pie',
        data: {
            labels: @json($pieData->pluck('label')),
            datasets: [{
                data: @json($pieData->pluck('total')),
                backgroundColor: [
                    '#1e1b4b',
                    '#f97316',
                    '#22c55e',
                    '#64748b',
                    '#ef4444',
                    '#0ea5e9',
                    '#a855f7',
                    '#14b8a6'
                ],
                borderWidth: 1
            }]
        },
options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: 'left',
            labels: {
                boxWidth: 14,
                padding: 12,
                font: { size: 12 }
            }
        },
        tooltip: {
            callbacks: {
                label: function (ctx) {
                    return ctx.label + ': Rp ' +
                        ctx.raw.toLocaleString('id-ID');
                }
            }
        },

        // 🔥 INI YANG BIKIN JADI PERSEN
        datalabels: {
            color: '#fff',
            font: {
                weight: 'bold',
                size: 14
            },
            formatter: (value, ctx) => {
                const data = ctx.chart.data.datasets[0].data.map(Number);
                const total = data.reduce((a, b) => a + b, 0);

                if (!total || isNaN(total)) return '0%';

                const percent = ((Number(value) / total) * 100).toFixed(0);
                return percent + '%';
            }

        }
    }
}
    });

    // =====================
    // BAR CHART (HARIAN)
    // =====================
    new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(
                $barData->pluck('tanggal')->map(fn($t) =>
                    \Carbon\Carbon::parse($t)->format('d M')
                )
            ) !!},
            datasets: [{
                label: 'Pendapatan',
                data: {!! json_encode($barData->pluck('total')) !!},
                backgroundColor: '#1e1b4b'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            },
            
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: val => 'Rp ' + val.toLocaleString('id-ID')
                    }
                }
            }
        }
    });

});
</script 

@endsection