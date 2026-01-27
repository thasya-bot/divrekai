@extends('layouts.app')

@section('title', 'Input Pendapatan Harian')

@section('content')

<div class="max-w-2xl mx-auto mt-8 px-4">

    <h2 class="text-2xl text-[#231f5c] font-bold text-center mb-8">
        Input Pendapatan Harian
    </h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- FORM CARD --}}
    <div class="bg-white rounded-xl shadow overflow-hidden">

        {{-- HEADER FORM --}}
        <div class="bg-[#231f5c] text-white px-6 py-4">
            <h3 class="font-semibold text-base">
                Form Input Pendapatan
            </h3>
        </div>

        {{-- BODY FORM --}}
        <form method="POST" action="{{ route('pendapatan.store') }}" class="p-6 space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium mb-2">
                    Tanggal
                </label>
                <input type="date" name="tanggal"
                    class="w-full border rounded-lg px-4 py-2 focus:ring focus:ring-indigo-200"
                    required>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">
                    Sumber / Keterangan
                </label>
                <input type="text" name="keterangan"
                    class="w-full border rounded-lg px-4 py-2 focus:ring focus:ring-indigo-200">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">
                    Jumlah (Rp)
                </label>
                <input type="number" name="jumlah"
                    class="w-full border rounded-lg px-4 py-2 focus:ring focus:ring-indigo-200"
                    required>
            </div>

            <button type="submit"
                class="w-full bg-orange-600 hover:bg-orange-700 text-white py-3 rounded-lg font-semibold">
                Simpan Pendapatan
            </button>
        </form>
    </div>

</div>


{{-- TOTAL PENDAPATAN HARI INI --}}
<div class="max-w-2xl mx-auto mt-8 px-4 mb-10">
    <div class="border-2 border-indigo-900 rounded-xl p-6 text-center bg-white">
        <p class="text-sm font-semibold text-[#231f5c]">
            Total Pendapatan Hari Ini
        </p>

        <p class="text-3xl font-bold text-orange-600 my-2">
            Rp {{ number_format($totalHariIni ?? 0, 0, ',', '.') }}
        </p>

        <p class="text-sm text-gray-500">
            {{ \Carbon\Carbon::today()->translatedFormat('d F Y') }}
        </p>
    </div>
</div>

@endsection
