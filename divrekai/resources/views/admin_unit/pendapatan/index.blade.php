@extends('layouts.app')

@section('title', 'Dashboard Pendapatan')

@section('content')
<div class="max-w-8xl mx-auto px-8 py-8 bg-white min-h-screen">

    {{-- JUDUL --}}
    <div class="mb-8 text-center">
        <h1 class="text-2xl font-bold text-[#231f5c]">
            Laporan Pendapatan Divisi {{ auth()->user()->unit->nama_unit ?? '-' }}
        </h1>
    </div>

{{-- ===================== --}}
{{-- SECTION RINGKASAN --}}
{{-- ===================== --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

    {{-- HARI INI --}}
    <div class="bg-[#231f5c] text-white rounded-xl p-6 shadow">
        <p class="text-sm">Pendapatan Hari Ini</p>
        <p class="text-3xl font-bold mt-3">
            Rp {{ number_format($hariIni ?? 0, 0, ',', '.') }}
        </p>
        <p class="text-xs mt-2">
            {{ \Carbon\Carbon::today()->translatedFormat('d F Y') }}
        </p>
    </div>

    {{-- BULAN INI --}}
    <div class="bg-orange-600 text-white rounded-xl p-6 shadow">
        <p class="text-sm">Pendapatan Bulan Ini</p>
        <p class="text-3xl font-bold mt-3">
            Rp {{ number_format($bulanIni ?? 0, 0, ',', '.') }}
        </p>
        <p class="text-xs mt-2">
            {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}
        </p>
    </div>

</div>
@if (!is_null($statusTargetHarian) && $statusTargetHarian === 'kurang')

    <div class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        ❌ Target harian belum tercapai.
        Kurang Rp {{ number_format($targetHarian - $hariIni, 0, ',', '.') }}
    </div>

@elseif (!is_null($statusTargetHarian) && $statusTargetHarian === 'pas')

    <div class="mt-4 bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
        ⚠️ Pendapatan hari ini tepat sesuai target.
    </div>

@elseif (!is_null($statusTargetHarian) && $statusTargetHarian === 'lebih')
    <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        ✅ Target harian tercapai!
        Lebih Rp {{ number_format($hariIni - $targetHarian, 0, ',', '.') }}
    </div>
@endif

{{-- ===================== --}}
{{-- SECTION GRAFIK --}}
{{-- ===================== --}}
<div class="my-10">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- PIE --}}

        <div class="bg-white rounded-xl shadow p-4 h-[260px] flex items-center justify-center">
        <canvas id="pieChart" style="max-width:320px; max-height:260px;"></canvas>
        </div>


        {{-- BAR --}}
        <div class="bg-white rounded-xl shadow p-4 h-[260px] flex items-center justify-center">
            <canvas id="barChart" class="w-full h-full"></canvas>
        </div>

        {{-- TARGET --}}
        <div class="bg-gray-400 text-white rounded-xl p-6 shadow h-[260px] flex flex-col justify-center">
            <p class="text-sm">Target Per Hari</p>
            <p class="text-xl font-bold mb-3">Rp 3.200.000</p>

            <p class="text-sm">Target Per Bulan</p>
            <p class="text-xl font-bold mb-3">Rp 3.200.000</p>

            <p class="text-sm">Target Per Tahun</p>
            <p class="text-xl font-bold">Rp 3.200.000</p>
        </div>

    </div>
</div>

    {{-- TABEL HARIAN --}}
    <form method="GET" id="filterForm"
        class="flex flex-wrap items-center justify-between gap-4 mb-4">

        {{-- KIRI --}}
        <div class="flex items-center gap-3">

            {{-- KOTAK ORANYE + KALENDER --}}
            <div onclick="openDatePicker()"
                class="flex items-center gap-2 bg-orange-600 text-white px-4 py-2 rounded text-sm font-semibold cursor-pointer hover:bg-orange-700">

                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>

                <span>Laporan Pendapatan Harian</span>
            </div>

            {{-- INPUT DATE (DISIMPAN) --}}
            <input id="filterTanggal"
                type="date"
                name="tanggal"
                value="{{ request('tanggal') }}"
                class="hidden">
        </div>

        {{-- KANAN --}}
        <div class="flex items-center gap-4 text-sm">

            {{-- SHOW --}}
            <div class="flex items-center gap-2">
                <span>Show</span>
                <select name="limit_harian"
                        onchange="filterForm.submit()"
                        class="border rounded px-2 py-1">
                    @foreach([4,10,25,50] as $l)
                        <option value="{{ $l }}" {{ request('limit_harian',4)==$l?'selected':'' }}>
                            {{ $l }}
                        </option>
                    @endforeach
                </select>
                <span>Entries</span>
            </div>

            {{-- SEARCH --}}
            <div class="flex items-center gap-2">
                <span>Search:</span>
                <input type="text"
                    name="search"
                    value="{{ request('search') }}"
                    class="border rounded px-2 py-1 text-sm">
            </div>
        </div>
    </form>
        <script>
            function openDatePicker() {
                document.getElementById('filterTanggal').showPicker();
            }

            document.getElementById('filterTanggal')
                .addEventListener('change', function () {
                    document.getElementById('filterForm').submit();
                });
        </script>

        <div class="overflow-x-auto">
            <table class="w-full text-sm border rounded-lg">
                <thead class="bg-[#231f5c] text-white">
                    <tr>
                        <th class="py-3 px-4 text-center">No</th>
                        <th class="py-3 px-4 text-center">Tanggal</th>
                        <th class="py-3 px-4 text-center">Sumber</th>
                        <th class="py-3 px-4 text-center">Jumlah</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($pendapatan as $item)
                        <tr class="border-t hover:bg-gray-50 text-center">
                            <td class="py-3 px-4">{{ $loop->iteration }}</td>
                            <td class="py-3 px-4">
                                {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}
                            </td>
                            <td class="py-3 px-4">
                                {{ $item->keterangan ?? '-' }}
                            </td>
                            <td class="py-3 px-4 font-semibold">
                                Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-4 space-x-2">

                                {{-- EDIT --}}
                                <a href="{{ route('pendapatan.edit', $item->id) }}"
                                   class="bg-indigo-900 hover:bg-indigo-800 text-white px-3 py-1 rounded text-xs">
                                    Edit
                                </a>

                                {{-- HAPUS --}}
                                <form action="{{ route('pendapatan.destroy', $item->id) }}"
                                      method="POST"
                                      class="inline"
                                      onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                        onclick="openDeleteModal('{{ route('pendapatan.destroy', $item->id) }}')"
                                        class="bg-red-700 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">
                                        Hapus
                                    </button>

                                </form>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-4 text-center text-gray-500">
                                Belum ada data pendapatan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            
{{-- INFO + PAGINATION (DATATABLES STYLE) --}}
<div class="flex justify-between items-center mt-4 text-sm text-gray-600">

    {{-- KIRI --}}
    <div>
        Showing {{ $pendapatan->firstItem() ?? 0 }}
        to {{ $pendapatan->lastItem() ?? 0 }}
        of {{ $pendapatan->total() }} Entries
    </div>

    {{-- KANAN --}}
    <div class="flex items-center border rounded overflow-hidden">

        {{-- PREV --}}
        @if ($pendapatan->onFirstPage())
            <span class="px-3 py-1 text-gray-400 border-r cursor-not-allowed">‹</span>
        @else
            <a href="{{ $pendapatan->previousPageUrl() }}"
               class="px-3 py-1 border-r hover:bg-gray-100">‹</a>
        @endif

        {{-- PAGE --}}
        @foreach ($pendapatan->getUrlRange(1, $pendapatan->lastPage()) as $page => $url)
            @if ($page == $pendapatan->currentPage())
                <span class="px-3 py-1 bg-white border-r font-semibold text-[#231f5c]">
                    {{ $page }}
                </span>
            @else
                <a href="{{ $url }}"
                   class="px-3 py-1 border-r hover:bg-gray-100">
                    {{ $page }}
                </a>
            @endif
        @endforeach

        {{-- NEXT --}}
        @if ($pendapatan->hasMorePages())
            <a href="{{ $pendapatan->nextPageUrl() }}"
               class="px-3 py-1 hover:bg-gray-100">›</a>
        @else
            <span class="px-3 py-1 text-gray-400 cursor-not-allowed">›</span>
        @endif

    </div>
</div>
        </div>
 
        </div>
    </div>
    
{{-- REKAP BULANAN --}}
<div class="bg-white rounded-xl shadow p-6 mt-10">

    {{-- FILTER BAR --}}
   <form method="GET"
      action="{{ route('pendapatan.index') }}"
      id="filterFormBulanan"
      class="flex flex-wrap items-center justify-between gap-4 mb-4">


        {{-- KIRI --}}
        <div class="flex items-center gap-3">

            {{-- KOTAK BIRU + KALENDER --}}
            <div onclick="openMonthPicker()"
                 class="flex items-center gap-2 bg-[#231f5c] text-white px-4 py-2 rounded text-sm font-semibold cursor-pointer hover:opacity-90">

                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>

                <span>Rekapan Pendapatan Bulanan</span>
            </div>

            {{-- INPUT MONTH (HIDDEN) --}}
            <input id="filterBulan"
                   type="month"
                   name="bulan"
                   value="{{ request('bulan') }}"
                   class="hidden">
        </div>

        {{-- KANAN (SAMA KAYAK HARIAN) --}}
        <div class="flex items-center gap-4 text-sm">

            {{-- SHOW --}}
            <div class="flex items-center gap-2">
                <span>Show</span>
                <select name="limit_bulanan"
                        onchange="filterFormBulanan.submit()"
                        class="border rounded px-2 py-1">
                    @foreach([4,10,25,50] as $l)
                        <option value="{{ $l }}" {{ request('limit_bulanan',4)==$l?'selected':'' }}>
                            {{ $l }}
                        </option>
                    @endforeach
                </select>
                <span>Entries</span>
            </div>

            {{-- SEARCH --}}
            <div class="flex items-center gap-2">
                <span>Search:</span>
                <input type="text"
                       name="search_bulanan"
                       value="{{ request('search_bulanan') }}"
                       class="border rounded px-2 py-1 text-sm">
            </div>
        </div>

        {{-- JAGA FILTER HARIAN --}}
        <input type="hidden" name="tanggal" value="{{ request('tanggal') }}">
        <input type="hidden" name="search" value="{{ request('search') }}">
    </form>

    {{-- SCRIPT --}}
    <script>
        function openMonthPicker() {
            document.getElementById('filterBulan').showPicker();
        }

        document.getElementById('filterBulan')
            .addEventListener('change', function () {
                document.getElementById('filterFormBulanan').submit();
            });
    </script>

    {{-- TABEL --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm border rounded-lg">
            <thead class="bg-orange-600 text-white">
                <tr>
                    <th class="py-3 px-4 text-center">No</th>
                    <th class="py-3 px-4 text-center">Bulan</th>
                    <th class="py-3 px-4 text-center">Total</th>
                </tr>
            </thead>

            <tbody>
                @forelse($rekapBulanan as $row)
                    <tr class="border-t hover:bg-gray-50 text-center">
                        <td class="py-3 px-4">{{ $loop->iteration }}</td>
                        <td class="py-3 px-4">
                            {{ \Carbon\Carbon::create($row->tahun, $row->bulan)->translatedFormat('F Y') }}
                        </td>
                        <td class="py-3 px-4 font-semibold">
                            Rp {{ number_format($row->total, 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-4 text-center text-gray-500">
                            Belum ada data
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
            {{-- INFO + PAGINATION (DATATABLES STYLE) --}}
    <div class="flex justify-between items-center mt-4 text-sm text-gray-600">

        {{-- KIRI --}}
        <div>
            Showing {{ $rekapBulanan->firstItem() ?? 0 }}
            to {{ $rekapBulanan->lastItem() ?? 0 }}
            of {{ $rekapBulanan->total() }} Entries
        </div>

        {{-- KANAN --}}
        <div class="flex items-center border rounded overflow-hidden">

        {{-- PREV --}}
        @if ($rekapBulanan->onFirstPage())
            <span class="px-3 py-1 text-gray-400 border-r cursor-not-allowed">‹</span>
        @else
            <a href="{{ $rekapBulanan->previousPageUrl() }}"
            class="px-3 py-1 border-r hover:bg-gray-100">‹</a>
        @endif

        {{-- PAGE --}}
        @foreach ($rekapBulanan->getUrlRange(1, $rekapBulanan->lastPage()) as $page => $url)
            @if ($page == $rekapBulanan->currentPage())
                <span class="px-3 py-1 bg-white border-r font-semibold text-[#231f5c]">
                    {{ $page }}
                </span>
            @else
                <a href="{{ $url }}"
                class="px-3 py-1 border-r hover:bg-gray-100">
                    {{ $page }}
                </a>
            @endif
        @endforeach

        {{-- NEXT --}}
        @if ($rekapBulanan->hasMorePages())
            <a href="{{ $rekapBulanan->nextPageUrl() }}"
            class="px-3 py-1 hover:bg-gray-100">›</a>
        @else
            <span class="px-3 py-1 text-gray-400 cursor-not-allowed">›</span>
        @endif

        </div>
    </div>
    </div>

</div>

    {{-- MODAL KONFIRMASI HAPUS --}}
    <div id="deleteModal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50">

        <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 animate-fadeIn text-center">
            <h3 class="text-lg font-bold text-[#231f5c] mb-3">
                Konfirmasi Hapus Data
            </h3>

            <p class="text-sm text-gray-600 mb-6 text-center">
                Apakah kamu yakin ingin menghapus data pendapatan ini?
                <br>
                <span class="text-red-600 font-semibold">Tindakan ini tidak dapat dibatalkan.</span>
            </p>

            <div class="grid grid-cols-2 gap-3 items-stretch">

                {{-- BATAL --}}
                <button
                    type="button"
                    onclick="closeDeleteModal()"
                    class="w-full h-full px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-sm font-medium">
                    Batal
                </button>

                {{-- YA, HAPUS --}}
                <form id="deleteForm" method="POST" class="w-full h-full">
                    @csrf
                    @method('DELETE')
                    <button
                        type="submit"
                        class="w-full h-full px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white text-sm font-medium">
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
<script>
    function openDeleteModal(actionUrl) {
        const modal = document.getElementById('deleteModal');
        const form  = document.getElementById('deleteForm');

        form.action = actionUrl;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>

    {{-- LOAD CHART.JS DULU --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1"></script>

    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

    {{-- SCRIPT GRAFIK --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    Chart.register(ChartDataLabels);
 
    const pieCtx = document.getElementById('pieChart');
    const barCtx = document.getElementById('barChart');

    if (!pieCtx || !barCtx) return;

    // =====================
    // PIE CHART + PERSEN
    // =====================
new Chart(pieCtx, {
    type: 'pie',

    // 🔥 TAMBAHKAN BARIS INI
    plugins: [ChartDataLabels],

    data: {
        labels: @json($pieData->pluck('keterangan')),
        datasets: [{
            data: @json($pieData->pluck('total')),
            backgroundColor: [
                '#1e1b4b',
                '#f97316',
                '#22c55e',
                '#64748b',
                '#ef4444'
            ]
        }]
    },
    options: {
        plugins: {
            legend: {
                position: 'left'
            },
            datalabels: {
                display: true,
                color: '#fff',
                font: {
                    weight: 'bold',
                    size: 14
                },
                anchor: 'center',   // 🔥 WAJIB BIAR KELIATAN
                align: 'center',    // 🔥 WAJIB BIAR KELIATAN
                formatter: (value, ctx) => {
                    const data = ctx.chart.data.datasets[0].data.map(Number);
                    const total = data.reduce((a, b) => a + b, 0);
                    if (!total) return '0%';
                    return ((value / total) * 100).toFixed(0) + '%';
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
            datasets: [
                {
                    label: 'Pendapatan',
                    data: {!! json_encode($barData->pluck('total')) !!},
                    backgroundColor: '#1e1b4b'
                },
                {
                    label: 'Target',
                    data: {!! json_encode(
                        $barData->map(fn() => $targetHarian)
                    ) !!},
                    backgroundColor: '#ef4444'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                datalabels: {
                    display: false
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

}); // 🔥 INI YANG TADI HILANG
</script>
@endsection