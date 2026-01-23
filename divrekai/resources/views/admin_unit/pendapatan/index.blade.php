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

    {{-- RINGKASAN --}}
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

    {{-- TABEL HARIAN --}}
    <div class="bg-white rounded-xl shadow p-6 mb-10">

        <div class="flex justify-between items-center mb-4">
            <h2 class="font-semibold bg-orange-600 text-white px-4 py-2 text-sm">
                Laporan Pendapatan Harian
            </h2>

            <a href="{{ route('pendapatan.input') }}"
               class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm">
                + Tambah Data
            </a>
        </div>

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
        </div>
    </div>

    {{-- REKAP BULANAN --}}
    <div class="bg-white rounded-xl shadow p-6 mt-10">
        <h2 class="font-semibold bg-[#231f5c] text-white px-4 py-2 text-sm mb-4 inline-block">
            Rekapan Pendapatan Bulanan
        </h2>

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
                    <tr class="border-t text-center">
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
                        <td colspan="3" class="py-4 text-center text-gray-500">
                            Belum ada data
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
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

            <div class="flex justify-end gap-3">
                <button onclick="closeDeleteModal()"
                    class="px-4 py-0 rounded-lg bg-gray-200 hover:bg-gray-300  text-sm">
                    Batal
                </button>

                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white text-sm">
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>


</div>
@endsection
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
