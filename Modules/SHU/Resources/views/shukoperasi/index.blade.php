@extends('adminlte::page')

@section('title', 'Data SHU Koperasi')

@section('content_header')
    <h1 class="m-0 text-dark">
        <i class="fas fa-coins text-warning"></i>
        Data SHU Koperasi
    </h1>
@stop

@section('content')

<div class="container-fluid">

    {{-- ============================
        ALERT
    ============================= --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <button type="button" class="close" data-dismiss="alert">

                &times;

            </button>

            <i class="fas fa-check-circle"></i>

            {{ session('success') }}

        </div>

    @endif

    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show">

            <button type="button" class="close" data-dismiss="alert">

                &times;

            </button>

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- ============================
        FILTER TAHUN
    ============================= --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-primary">

            <h3 class="card-title text-white">

                <i class="fas fa-calendar-alt"></i>

                Rekapitulasi SHU Berdasarkan Tahun

            </h3>

        </div>

        <div class="card-body">

            <form method="GET">

                <div class="row align-items-end">

                    <div class="col-md-3">

                        <label>Pilih Tahun</label>

                        <select
                            name="tahun"
                            class="form-control">

                            @for($i = date('Y'); $i >= 2020; $i--)

                                <option
                                    value="{{ $i }}"
                                    {{ $tahun == $i ? 'selected' : '' }}>

                                    {{ $i }}

                                </option>

                            @endfor

                        </select>

                    </div>

                    <div class="col-md-2">

                        <button
                            class="btn btn-primary btn-block">

                            <i class="fas fa-search"></i>

                            Tampilkan

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- ============================
        DASHBOARD SUMMARY
    ============================= --}}

    @if($summary)

    <div class="row">

        <div class="col-lg-12">

            <div class="small-box bg-success shadow">

                <div class="inner">

                    <h2>

                        Rp {{ number_format($summary->total_shu,0,',','.') }}

                    </h2>

                    <p>

                        Total SHU Tahun {{ $tahun }}

                    </p>

                    <small>

                        Periode

                        {{ \Carbon\Carbon::parse($summary->periode_awal)->format('d M Y') }}

                        -

                        {{ \Carbon\Carbon::parse($summary->periode_akhir)->format('d M Y') }}

                    </small>

                </div>

                <div class="icon">

                    <i class="fas fa-wallet"></i>

                </div>

            </div>

        </div>

    </div>

    <div class="row">

        <div class="col-lg-4 col-md-6">

            <div class="small-box bg-info">

                <div class="inner">

                    <h3>

                        Rp {{ number_format($summary->jasa_simpanan,0,',','.') }}

                    </h3>

                    <p>

                        Jasa Simpanan

                    </p>

                </div>

                <div class="icon">

                    <i class="fas fa-piggy-bank"></i>

                </div>

            </div>

        </div>

        <div class="col-lg-4 col-md-6">

            <div class="small-box bg-primary">

                <div class="inner">

                    <h3>

                        Rp {{ number_format($summary->jasa_pinjaman,0,',','.') }}

                    </h3>

                    <p>

                        Jasa Pinjaman

                    </p>

                </div>

                <div class="icon">

                    <i class="fas fa-hand-holding-usd"></i>

                </div>

            </div>

        </div>

        <div class="col-lg-4 col-md-6">

            <div class="small-box bg-warning">

                <div class="inner">

                    <h3>

                        Rp {{ number_format($summary->dana_cadangan,0,',','.') }}

                    </h3>

                    <p>

                        Dana Cadangan

                    </p>

                </div>

                <div class="icon">

                    <i class="fas fa-shield-alt"></i>

                </div>

            </div>

        </div>

    </div>

    <div class="row">

        <div class="col-lg-6">

            <div class="small-box bg-danger">

                <div class="inner">

                    <h3>

                        Rp {{ number_format($summary->jasa_pengurus,0,',','.') }}

                    </h3>

                    <p>

                        Jasa Pengurus

                    </p>

                </div>

                <div class="icon">

                    <i class="fas fa-users"></i>

                </div>

            </div>

        </div>

        <div class="col-lg-6">

            <div class="small-box bg-secondary">

                <div class="inner">

                    <h3>

                        Rp {{ number_format($summary->dana_sosial,0,',','.') }}

                    </h3>

                    <p>

                        Dana Sosial

                    </p>

                </div>

                <div class="icon">

                    <i class="fas fa-hand-holding-heart"></i>

                </div>

            </div>

        </div>

    </div>

    @endif

<style>

.card{
    border-radius:12px;
}

.small-box{
    border-radius:12px;
    box-shadow:0 4px 12px rgba(0,0,0,.15);
}

.small-box .inner{
    padding:20px;
}

.small-box h2{
    font-size:34px;
    font-weight:bold;
}

.small-box h3{
    font-size:28px;
    font-weight:bold;
}

.small-box p{
    font-size:16px;
    margin-bottom:4px;
}

.small-box small{
    color:white;
    opacity:.9;
}

.small-box .icon{

    top:18px;
    right:18px;

}

.small-box .icon i{

    font-size:65px;
    opacity:.18;

}

</style>

{{-- ==========================================
        KOMPOSISI PEMBAGIAN SHU
========================================== --}}

@if($summary)

@php

    $total = $summary->total_shu > 0 ? $summary->total_shu : 1;

    $persenSimpanan = ($summary->jasa_simpanan / $total) * 100;
    $persenPinjaman = ($summary->jasa_pinjaman / $total) * 100;
    $persenCadangan = ($summary->dana_cadangan / $total) * 100;
    $persenPengurus = ($summary->jasa_pengurus / $total) * 100;
    $persenSosial = ($summary->dana_sosial / $total) * 100;

@endphp

<div class="card shadow mb-4">

    <div class="card-header bg-info">

        <h3 class="card-title text-white">

            <i class="fas fa-chart-pie"></i>

            Komposisi Pembagian SHU Tahun {{ $tahun }}

        </h3>

    </div>

    <div class="card-body">

        <div class="progress-group">

            <strong>Jasa Simpanan</strong>

            <span class="float-right">

                {{ number_format($persenSimpanan,2) }} %

            </span>

            <div class="progress progress-sm">

                <div
                    class="progress-bar bg-info"
                    style="width: {{ $persenSimpanan }}%">

                </div>

            </div>

        </div>

        <hr>

        <div class="progress-group">

            <strong>Jasa Pinjaman</strong>

            <span class="float-right">

                {{ number_format($persenPinjaman,2) }} %

            </span>

            <div class="progress progress-sm">

                <div
                    class="progress-bar bg-primary"
                    style="width: {{ $persenPinjaman }}%">

                </div>

            </div>

        </div>

        <hr>

        <div class="progress-group">

            <strong>Dana Cadangan</strong>

            <span class="float-right">

                {{ number_format($persenCadangan,2) }} %

            </span>

            <div class="progress progress-sm">

                <div
                    class="progress-bar bg-warning"
                    style="width: {{ $persenCadangan }}%">

                </div>

            </div>

        </div>

        <hr>

        <div class="progress-group">

            <strong>Jasa Pengurus</strong>

            <span class="float-right">

                {{ number_format($persenPengurus,2) }} %

            </span>

            <div class="progress progress-sm">

                <div
                    class="progress-bar bg-danger"
                    style="width: {{ $persenPengurus }}%">

                </div>

            </div>

        </div>

        <hr>

        <div class="progress-group">

            <strong>Dana Sosial</strong>

            <span class="float-right">

                {{ number_format($persenSosial,2) }} %

            </span>

            <div class="progress progress-sm">

                <div
                    class="progress-bar bg-secondary"
                    style="width: {{ $persenSosial }}%">

                </div>

            </div>

        </div>

    </div>

</div>

@endif


{{-- ==========================================
            TOMBOL TAMBAH
========================================== --}}

<div class="mb-3">

    <a href="{{ route('shu-koperasi.create') }}"
        class="btn btn-primary">

        <i class="fas fa-plus"></i>

        Tambah SHU

    </a>

</div>


{{-- ==========================================
          TABEL RIWAYAT SHU
========================================== --}}

<div class="card shadow">

    <div class="card-header">

        <h3 class="card-title">

            <i class="fas fa-table"></i>

            Riwayat SHU Koperasi

        </h3>

    </div>

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-bordered table-hover">

                <thead class="bg-light text-center">

                    <tr>

                        <th width="60">No</th>
                        <th>Periode Awal</th>
                        <th>Periode Akhir</th>
                        <th>Jasa Simpanan</th>
                        <th>Jasa Pinjaman</th>
                        <th>Dana Cadangan</th>
                        <th>Jasa Pengurus</th>
                        <th>Dana Sosial</th>
                        <th>Total SHU</th>
                        <th width="90">Aksi</th>

                    </tr>

                </thead>

                <tbody>

                @forelse($data as $item)

                    <tr>

                        <td class="text-center">

                            {{ $data->firstItem() + $loop->index }}

                        </td>

                        <td class="text-center">

                            {{ \Carbon\Carbon::parse($item->periode_awal)->format('d M Y') }}

                        </td>

                        <td class="text-center">

                            {{ \Carbon\Carbon::parse($item->periode_akhir)->format('d M Y') }}

                        </td>

                        <td>

                            Rp {{ number_format($item->jasa_simpanan,0,',','.') }}

                        </td>

                        <td>

                            Rp {{ number_format($item->jasa_pinjaman,0,',','.') }}

                        </td>

                        <td>

                            Rp {{ number_format($item->dana_cadangan,0,',','.') }}

                        </td>

                        <td>

                            Rp {{ number_format($item->jasa_pengurus,0,',','.') }}

                        </td>

                        <td>

                            Rp {{ number_format($item->dana_sosial,0,',','.') }}

                        </td>

                        <td>

                            <span class="badge badge-success p-2">

                                Rp {{ number_format($item->total_shu,0,',','.') }}

                            </span>

                        </td>

                        <td class="text-center">

                            <a href="{{ route('shu-koperasi.show',$item->id) }}"
                                class="btn btn-warning btn-sm">

                                <i class="fas fa-eye"></i>

                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="10" class="text-center text-muted">

                            <i class="fas fa-folder-open"></i>

                            Data SHU belum tersedia.

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

        <div class="mt-3 d-flex justify-content-end">

            {{ $data->links() }}

        </div>

    </div>

</div>

</div>

@endsection