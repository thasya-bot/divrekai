@extends('layouts.app')

@section('title', 'Edit Pendapatan')

@section('content')
<div class="max-w-2xl mx-auto mt-10">
    <h2 class="text-2xl text-[#231f5c] font-bold text-center mb-8">
        Edit Pendapatan Unit
    </h2>
    {{-- CARD --}}
    <div class="bg-white rounded-xl shadow overflow-hidden">

        {{-- HEADER --}}
        <div class="bg-[#231f5c] px-6 py-4">
            <h2 class="text-white font-semibold text-base">
                Form Edit Pendapatan
            </h2>
        </div>

        {{-- FORM --}}
        <form id="formEditPendapatan"
              method="POST"
              action="{{ route('admin.pic.pendapatan.update', $pendapatan->id) }}"
              onsubmit="return confirmEdit()"
              class="p-6 space-y-4">
            @csrf
            @method('PUT')

            {{-- TANGGAL --}}
            <div>
                <label class="block text-sm font-semibold mb-1">
                    Tanggal
                </label>
                <input type="date"
                       name="tanggal"
                       value="{{ $pendapatan->tanggal }}"
                       class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#231f5c]"
                       required>
            </div>

            {{-- SUMBER --}}
            <div>
                <label class="block text-sm font-semibold mb-1">
                    Sumber / Keterangan
                </label>
                <input type="text"
                       name="keterangan"
                       value="{{ $pendapatan->keterangan }}"
                       class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#231f5c]"
                       required>
            </div>

            {{-- JUMLAH --}}
            <div>
                <label class="block text-sm font-semibold mb-1">
                    Jumlah (Rp)
                </label>
                <input type="number"
                       name="jumlah"
                       value="{{ $pendapatan->jumlah }}"
                       class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#231f5c]"
                       required>
            </div>

            {{-- BUTTON --}}
            <div class="pt-2">
                <button type="submit"
                        class="w-full bg-orange-600 hover:bg-orange-700 text-white font-semibold py-2 rounded-lg transition">
                    Simpan Pendapatan
                </button>
            </div>

        </form>

    </div>
</div>
<div id="editConfirmModal"
     class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">

    <div class="bg-white rounded-xl w-full max-w-md p-6 text-center">

        <h3 class="text-lg font-bold text-[#231f5c] mb-4">
            Konfirmasi Perubahan
        </h3>

        <p class="text-gray-600 mb-6">
            Apakah Anda yakin ingin menyimpan perubahan data pendapatan ini?
        </p>

        <div class="flex gap-4">
            <button onclick="closeEditModal()"
                    class="w-1/2 border py-2 rounded-lg hover:bg-gray-100">
                Batal
            </button>

            <button onclick="submitEditForm()"
                    class="w-1/2 bg-orange-600 hover:bg-orange-700 text-white py-2 rounded-lg">
                Ya, Simpan
            </button>
        </div>
    </div>
</div>


{{-- KONFIRMASI EDIT  --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function confirmEdit() {
    Swal.fire({
        title: 'Konfirmasi Perubahan',
        html: `
            <p class="text-gray-700 text-sm leading-relaxed">
                Apakah Anda yakin ingin menyimpan perubahan data pendapatan ini?
            </p>
        `,
        showCancelButton: true,
        confirmButtonText: 'Ya, Simpan',
        cancelButtonText: 'Batal',
        icon: undefined,

        customClass: {
            popup: 'rounded-2xl px-6 py-6',
            title: 'text-[#231f5c] font-bold text-lg',
            actions: 'flex gap-4 justify-center mt-6',
            confirmButton: 'w-40 py-2 rounded-lg text-white font-semibold bg-orange-600 hover:bg-orange-700',
            cancelButton: 'w-40 py-2 rounded-lg border border-gray-300 text-gray-700 font-semibold hover:bg-gray-100'
        },

        buttonsStyling: false
        
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('formEditPendapatan').submit();
        }
    });

    return false;
}
</script>
@endsection
