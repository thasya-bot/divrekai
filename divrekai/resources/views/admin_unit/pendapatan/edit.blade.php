@extends('layouts.app')

@section('title', 'Edit Pendapatan')

@section('content')
<div class="max-w-2xl mx-auto mt-10 px-4">

    <h2 class="text-2xl font-bold text-center text-[#231f5c] mb-8">
        Edit Pendapatan
    </h2>

    <div class="bg-white rounded-xl shadow overflow-hidden">

        {{-- HEADER --}}
        <div class="bg-[#231f5c] text-white px-6 py-4">
            <h3 class="font-semibold">Form Edit Pendapatan</h3>
        </div>

        {{-- FORM --}}
        <form id="editForm"
              method="POST"
              action="{{ route('pendapatan.update', $pendapatan->id) }}"
              class="p-6 space-y-5">

            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium mb-2">Tanggal</label>
                <input type="date" name="tanggal"
                    value="{{ $pendapatan->tanggal }}"
                    class="w-full border rounded-lg px-4 py-2 focus:ring focus:ring-indigo-200"
                    required>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">
                    Sumber / Keterangan
                </label>
                <input type="text" name="keterangan"
                    value="{{ $pendapatan->keterangan }}"
                    class="w-full border rounded-lg px-4 py-2 focus:ring focus:ring-indigo-200">
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">
                    Jumlah (Rp)
                </label>
                <input type="number" name="jumlah"
                    value="{{ $pendapatan->jumlah }}"
                    class="w-full border rounded-lg px-4 py-2 focus:ring focus:ring-indigo-200"
                    required>
            </div>

            {{-- BUTTON --}}
            <div class="flex gap-4">
                <a href="{{ route('pendapatan.index') }}"
                   class="w-1/2 text-center border py-2 rounded-lg hover:bg-gray-100">
                    Batal
                </a>

                <button type="button"
                        onclick="openModal()"
                        class="w-1/2 bg-orange-600 hover:bg-orange-700 text-white py-2 rounded-lg font-semibold">
                    Simpan Perubahan
                </button>
            </div>

        </form>
    </div>
</div>

{{-- MODAL KONFIRMASI --}}
<div id="confirmModal"
     class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">

    <div class="bg-white rounded-xl w-full max-w-md p-6 text-center">

        <h3 class="text-lg font-bold text-[#231f5c] mb-4">
            Konfirmasi Perubahan
        </h3>

        <p class="text-gray-600 mb-6">
            Apakah Anda yakin ingin menyimpan perubahan data pendapatan ini?
        </p>

        <div class="flex gap-4">
            <button onclick="closeModal()"
                    class="w-1/2 border py-2 rounded-lg hover:bg-gray-100">
                Batal
            </button>

            <button onclick="submitForm()"
                    class="w-1/2 bg-orange-600 hover:bg-orange-700 text-white py-2 rounded-lg">
                Ya, Simpan
            </button>
        </div>
    </div>
</div>

{{-- SCRIPT MODAL --}}
<script>
    function openModal() {
        document.getElementById('confirmModal').classList.remove('hidden');
        document.getElementById('confirmModal').classList.add('flex');
    }

    function closeModal() {
        document.getElementById('confirmModal').classList.add('hidden');
    }

    function submitForm() {
        document.getElementById('editForm').submit();
    }
</script>
@endsection