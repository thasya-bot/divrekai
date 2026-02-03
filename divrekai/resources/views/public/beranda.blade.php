@extends('layouts.public')

@section('title', 'Beranda | DIVRE KAI')

@section('content')

{{-- HERO --}}
<section class="w-full h-[370px] overflow-hidden">
    <img
        src="{{ asset('img/beranda-kai.jpeg') }}"
        alt="KAI"
        class="w-full h-full object-cover object-[60%_75%]"
        data-aos="zoom-out"
    >
</section>

{{-- TENTANG KAMI --}}
<section class="py-20 text-center max-w-5xl mx-auto px-6"
         data-aos="fade-up">
    <h2 class="text-2xl font-bold text-[#231f5c]">
        TENTANG <span class="text-orange-600">KAMI</span>
    </h2>

    <p class="mt-6 text-[#231f5c] leading-relaxed">
        <span class="font-semibold text-orange-600">
            PT Kereta Api Indonesia (Persero)
        </span>
        (disingkat KAI atau PT KAI) adalah Badan Usaha Milik Negara Indonesia yang menyelenggarakan jasa angkutan kereta api.
    </p>
</section>

{{-- DESKRIPSI --}}
<section class="max-w-6xl mx-auto px-6 grid md:grid-cols-2 gap-14 items-center pb-24">

    <div class="flex justify-center md:justify-start"
         data-aos="fade-right">
        <img src="{{ asset('img/logo-kai.png') }}" class="h-40" alt="KAI">
    </div>

    <p class="text-[#231f5c] text-justify leading-relaxed"
       data-aos="fade-left">
        Layanan PT KAI meliputi angkutan penumpang dan barang. Pada akhir Maret 2007,
        DPR mengesahkan revisi Undang-Undang Nomor 13 Tahun 1992, yaitu Undang-Undang
        Nomor 23 Tahun 2007, yang menegaskan bahwa investor swasta maupun pemerintah
        daerah diberi kesempatan untuk mengelola jasa angkutan kereta api di Indonesia.
        Dengan demikian, pemberlakuan undang-undang tersebut secara hukum mengakhiri
        monopoli PT KAI dalam mengoperasikan kereta api di Indonesia.
    </p>
</section>

{{-- FOOTER --}}
<footer id="kontak" class="bg-orange-600 text-white">
    <div class="max-w-7xl mx-auto px-6 py-20 grid md:grid-cols-2 gap-12 items-start">

        {{-- LEFT --}}
        <div data-aos="fade-up">
            <img
                src="{{ asset('img/kai-abuu.png') }}"
                class="h-28 mb-6"
                alt="KAI"
            >
            <p class="text-sm text-justify leading-relaxed max-w-md">
                PT Kereta Api Indonesia (Persero) <br>
                Divisi Regional I Sumatera Utara merupakan perusahaan yang memiliki
                sumber daya manusia profesional dan berpengalaman.
            </p>
        </div>

        {{-- RIGHT --}}
        <div data-aos="fade-up" data-aos-delay="150">
            <h3 class="font-bold text-xl mb-6">KONTAK</h3>

            <ul class="space-y-4 text-sm">
                <li class="flex items-start gap-3">
                    <span class="mt-1">📍</span>
                    <span class="leading-relaxed">
                        Jl. Prof. H. M. Yamin No.13, Gg. Buntu,
                        Kec. Medan Timur, Kota Medan,
                        Sumatera Utara 20236
                    </span>
                </li>

                <li class="flex items-center gap-3">
                    <span>📞</span>
                    <span>021 02121</span>
                </li>

                <li class="flex items-center gap-3">
                    <span>✉️</span>
                    <span>info@kai.id</span>
                </li>

                <li class="flex items-center gap-3">
                    <span>📸</span>
                    <span>@medankeretaapi</span>
                </li>
            </ul>
        </div>

    </div>
</footer>

@endsection
