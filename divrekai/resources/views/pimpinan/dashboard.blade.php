@extends('layouts.app')

@section('title', 'Dashboard KAI')

@section('content')
    <div class="p-10">
        <h1 class="text-2xl font-bold">
            Dashboard Pimpinan
        </h1>
    </div>

{{-- HERO --}}
<section>
    <img src="{{ asset('img/kereta.jpg') }}"
         class="w-full h-[450px] object-cover">
</section>

{{-- TENTANG KAMI --}}
<section class="text-center py-12">
    <h2 class="text-2xl font-bold text-blue-900 mb-4">
        TENTANG KAMI
    </h2>
    <p class="max-w-3xl mx-auto text-gray-700">
        PT Kereta Api Indonesia (Persero) adalah Badan Usaha Milik Negara
        yang menyelenggarakan jasa angkutan kereta api.
    </p>
</section>

{{-- DESKRIPSI --}}
<section class="py-12 px-20 grid grid-cols-2 gap-10">
    <img src="{{ asset('img/logo-kai.png') }}" class="w-40">

    <p class="text-gray-700 text-justify">
        Layanan PT KAI meliputi angkutan penumpang dan barang serta pengelolaan
        perkeretaapian di Indonesia.
    </p>
</section>

{{-- FOOTER --}}
<footer class="bg-orange-600 text-white px-20 py-10">
    <div class="grid grid-cols-2 gap-10">
        <div>
            <img src="{{ asset('img/logo-kai.png') }}" class="w-32 mb-3">
            <p class="text-sm">
                PT KAI Divre I Sumatera Utara
            </p>
        </div>

        <div>
            <h3 class="font-bold mb-3">KONTAK</h3>
            <ul class="space-y-2 text-sm">
                <li>📍 Medan, Sumatera Utara</li>
                <li>📞 021-121121</li>
                <li>✉️ kai.id</li>
            </ul>
        </div>
    </div>
</footer>

@endsection