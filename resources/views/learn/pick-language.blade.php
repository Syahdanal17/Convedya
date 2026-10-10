@extends('layouts.learn')

@section('title', 'Convedya — Pilih Bahasa')

@push('styles')
@vite(['resources/css/learn-greetings.css'])
@endpush


@section('content')
<x-sidebar active="learn" />

<main>
<div class="flag-picker">
    <h1>Pilih bahasa</h1>
    <p class="sub">Pilih bahasa yang ingin kamu latih hari ini.</p>

    <div class="flag-grid">
    @forelse ($languages as $lang)
        @php
            // kode bahasa -> kode negara (flag-icons)
            $countryMap = [
                'en' => 'gb',
                'jp' => 'jp',
                'ja' => 'jp',
                'ko' => 'kr',
                'zh' => 'cn',
                'hi' => 'in',
                'ar' => 'sa',
                'vi' => 'vn',
                'sv' => 'se',
                'el' => 'gr',
                'ms' => 'my',
                'tl' => 'ph',
                'da' => 'dk',
                'cs' => 'cz',
                'uk' => 'ua',
                'he' => 'il',
                'fa' => 'ir',
                'bn' => 'bd',
                'ur' => 'pk',
                'sw' => 'ke',
            ];

            $code = strtolower(trim($lang->code));
            $base = preg_split('/[-_]/', $code)[0];   // zh-cn / zh_cn -> zh
            $country = $countryMap[$code] ?? $countryMap[$base] ?? $base;
        @endphp

        <a href="{{ route('learn.greetings.show', $lang->slug) }}"
           class="flag-card {{ $lang->id === $currentId ? 'is-current' : '' }}">
            <span class="flag-icon" aria-hidden="true">
                <span class="fi fi-{{ $country }}"></span>
            </span>
            <span class="flag-name">{{ $lang->name }}</span>

            {{-- DEBUG: hapus baris ini kalau semua bendera sudah muncul --}}
            {{-- <small>{{ $lang->code }} → fi-{{ $country }}</small> --}}

            @if ($lang->id === $currentId)
                <span class="flag-badge">Sedang dipelajari</span>
            @endif
        </a>
    @empty
        <p>Belum ada bahasa yang tersedia.</p>
    @endforelse
    </div>
</div>
</main>
@endsection
