@extends('adminlte::page')

@section('title', 'Detail Pencairan SHU')

@section('content_header')

<div class="d-flex justify-content-between align-items-center">

    <div>

        <h1 class="m-0 text-dark">

            <i class="fas fa-money-check-alt text-success"></i>

            Detail Pencairan SHU

        </h1>

        <small class="text-muted">

            Informasi lengkap mengenai proses pencairan SHU anggota koperasi.

        </small>

    </div>

</div>

@stop

@section('content')

<div class="container-fluid">

{{-- ==========================================
        ALERT
========================================== --}}

@if(session('success'))

<div class="alert alert-success alert-dismissible fade show">

    <button
        type="button"
        class="close"
        data-dismiss="alert">

        &times;

    </button>

    <i class="fas fa-check-circle"></i>

    {{ session('success') }}

</div>

@endif

@if(session('error'))

<div class="alert alert-danger alert-dismissible fade show">

    <button
        type="button"
        class="close"
        data-dismiss="alert">

        &times;

    </button>

    <i class="fas fa-times-circle"></i>

    {{ session('error') }}

</div>

@endif

@if($errors->any())

<div class="alert alert-danger alert-dismissible fade show">

    <button
        type="button"
        class="close"
        data-dismiss="alert">

        &times;

    </button>

    <ul class="mb-0">

        @foreach($errors->all() as $error)

            <li>{{ $error }}</li>

        @endforeach

    </ul>

</div>

@endif


{{-- ==========================================
        SUMMARY CARD
========================================== --}}

<div class="row">

    <div class="col-lg-3 col-md-6">

        <div class="small-box bg-success">

            <div class="inner">

                <h3>

                    Rp {{ number_format($data->nominal_pengajuan,0,',','.') }}

                </h3>

                <p>Total SHU</p>

            </div>

            <div class="icon">

                <i class="fas fa-wallet"></i>

            </div>

        </div>

    </div>

    <div class="col-lg-3 col-md-6">

        <div class="small-box bg-info">

            <div class="inner">

                <h3>

                    {{ ucfirst(str_replace('_',' ',$data->status)) }}

                </h3>

                <p>Status Pencairan</p>

            </div>

            <div class="icon">

                <i class="fas fa-check-circle"></i>

            </div>

        </div>

    </div>

   

    <div class="col-lg-3 col-md-6">

        <div class="small-box bg-primary">

            <div class="inner">

                <h3 style="font-size:20px">

                    {{ $data->tanggal_pencairan
                        ? \Carbon\Carbon::parse($data->tanggal_pencairan)->translatedFormat('d M Y')
                        : '-' }}

                </h3>

                <p>Tanggal Pencairan</p>

            </div>

            <div class="icon">

                <i class="fas fa-money-check-alt"></i>

            </div>

        </div>

    </div>

</div>


{{-- ==========================================
        DATA ANGGOTA & INFORMASI SHU
========================================== --}}

<div class="row">

    {{-- ============================
            DATA ANGGOTA
    ============================= --}}

    <div class="col-md-6">

        <div class="card card-outline card-primary shadow-sm">

            <div class="card-header">

                <h3 class="card-title">

                    <i class="fas fa-user-circle"></i>

                    Data Anggota

                </h3>

            </div>

            <div class="card-body">

                <table class="table table-borderless">

                    <tr>

                        <th width="170">

                            Nama Anggota

                        </th>

                        <td>

                            {{ $data->shuAnggota->user->name }}

                        </td>

                    </tr>

                    <tr>

                        <th>

                            NIP

                        </th>

                        <td>

                            {{ $data->shuAnggota->user->nip }}

                        </td>

                    </tr>

                    <tr>

                        <th>

                            Nomor Rekening

                        </th>

                        <td>

                            {{ $data->shuAnggota->user->no_rek }}

                        </td>

                    </tr>

                    <tr>

                        <th>

                            Periode SHU

                        </th>

                        <td>

                            {{ \Carbon\Carbon::parse($data->shuAnggota->periode_awal)->translatedFormat('d F Y') }}

                            <br>

                            <small class="text-muted">

                                sampai

                            </small>

                            <br>

                            {{ \Carbon\Carbon::parse($data->shuAnggota->periode_akhir)->translatedFormat('d F Y') }}

                        </td>

                    </tr>

                </table>

            </div>

        </div>

    </div>


    {{-- ============================
            INFORMASI SHU
    ============================= --}}

    <div class="col-md-6">

        <div class="card card-outline card-success shadow-sm">

            <div class="card-header">

                <h3 class="card-title">

                    <i class="fas fa-wallet"></i>

                    Informasi SHU

                </h3>

            </div>

            <div class="card-body">

                <table class="table table-borderless">

                    <tr>

                        <th width="180">

                            SHU Simpanan

                        </th>

                        <td class="text-right">

                            Rp {{ number_format($data->shuAnggota->shu_simpanan,0,',','.') }}

                        </td>

                    </tr>

                    <tr>

                        <th>

                            SHU Pinjaman

                        </th>

                        <td class="text-right">

                            Rp {{ number_format($data->shuAnggota->shu_pinjaman,0,',','.') }}

                        </td>

                    </tr>

                    <tr>

                        <th>

                            Pajak

                        </th>

                        <td class="text-right text-danger">

                            Rp {{ number_format($data->shuAnggota->pajak,0,',','.') }}

                        </td>

                    </tr>

                    <tr>

                        <th>

                            <strong>Total SHU</strong>

                        </th>

                        <td class="text-right">

                            <span class="badge badge-success p-2">

                                Rp {{ number_format($data->shuAnggota->shu_anggota,0,',','.') }}

                            </span>

                        </td>

                    </tr>

                    <tr>

                        <th>

                            Nominal Dicairkan

                        </th>

                        <td class="text-right">

                            <span class="badge badge-primary p-2">

                                Rp {{ number_format($data->nominal_pengajuan,0,',','.') }}

                            </span>

                        </td>

                    </tr>

                </table>

            </div>

        </div>

    </div>

</div>

{{-- ==========================================
        STATUS PENCAIRAN
========================================== --}}

<div class="card card-outline card-warning shadow-sm">

    <div class="card-header">

        <h3 class="card-title">

            <i class="fas fa-exchange-alt"></i>

            Status Pencairan SHU

        </h3>

    </div>

    <div class="card-body">

        <div class="row">

            <div class="col-md-4 text-center">

                @switch($data->status)

                    @case('menunggu')

                        <span class="badge badge-warning p-3">

                            <i class="fas fa-clock"></i>

                            Menunggu Persetujuan

                        </span>

                    @break

                    @case('disetujui')

                        <span class="badge badge-primary p-3">

                            <i class="fas fa-check-circle"></i>

                            Disetujui

                        </span>

                    @break

                    @case('ditolak')

                        <span class="badge badge-danger p-3">

                            <i class="fas fa-times-circle"></i>

                            Ditolak

                        </span>

                    @break

                    @case('dicairkan')

                        <span class="badge badge-success p-3">

                            <i class="fas fa-money-check-alt"></i>

                            Dicairkan

                        </span>

                    @break

                @endswitch

            </div>

            <div class="col-md-8">

                <table class="table table-borderless">

                    <tr>

                        <th width="220">

                            Nominal Dicairkan

                        </th>

                        <td>

                            <strong class="text-success">

                                Rp {{ number_format($data->nominal_pengajuan,0,',','.') }}

                            </strong>

                        </td>

                    </tr>

                    <tr>

                        <th>

                            Tanggal Pencairan

                        </th>

                        <td>

                            {{ $data->tanggal_pencairan
                                ? \Carbon\Carbon::parse($data->tanggal_pencairan)->translatedFormat('d F Y')
                                : '-' }}

                        </td>

                    </tr>

                    <tr>

                        <th>

                            Keterangan

                        </th>

                        <td>

                            {{ $data->keterangan ?? '-' }}

                        </td>

                    </tr>

                </table>

            </div>

        </div>

    </div>

</div>


{{-- ==========================================
        BUKTI TRANSFER
========================================== --}}

<div class="card card-outline card-info shadow-sm">

    <div class="card-header">

        <h3 class="card-title">

            <i class="fas fa-file-upload"></i>

            Bukti Transfer

        </h3>

    </div>

    <div class="card-body">

        @if($data->bukti)

            @php

                $ext = strtolower(pathinfo($data->bukti, PATHINFO_EXTENSION));

            @endphp

            @if(in_array($ext,['jpg','jpeg','png']))

                <div class="text-center">

                    <img
                        src="{{ asset('storage/'.$data->bukti) }}"
                        class="img-fluid rounded shadow border"
                        style="max-height:500px">

                </div>

                <div class="text-center mt-3">

                    <a
                        href="{{ asset('storage/'.$data->bukti) }}"
                        target="_blank"
                        class="btn btn-info">

                        <i class="fas fa-search-plus"></i>

                        Lihat Ukuran Penuh

                    </a>

                </div>

            @elseif($ext == 'pdf')

                <div class="text-center p-4">

                    <i class="fas fa-file-pdf fa-5x text-danger mb-3"></i>

                    <h5>

                        Bukti transfer tersedia dalam format PDF.

                    </h5>

                    <a
                        href="{{ asset('storage/'.$data->bukti) }}"
                        target="_blank"
                        class="btn btn-danger mt-3">

                        <i class="fas fa-file-pdf"></i>

                        Buka PDF

                    </a>

                </div>

            @endif

        @else

            <div class="text-center p-5">

                <i class="fas fa-image fa-4x text-muted mb-3"></i>

                <h5 class="text-muted">

                    Bukti transfer belum tersedia.

                </h5>

            </div>

        @endif

    </div>

</div>


{{-- ==========================================
        RIWAYAT PROSES
========================================== --}}

<div class="card card-outline card-secondary shadow-sm">

    <div class="card-header">

        <h3 class="card-title">

            <i class="fas fa-history"></i>

            Riwayat Proses Pencairan

        </h3>

    </div>

    <div class="card-body">

        <ul class="timeline">

            <li>

                <i class="fas fa-paper-plane bg-primary"></i>

                <div class="timeline-item">

                    <span class="time">

                        <i class="far fa-calendar"></i>

                        {{ $data->tanggal_pengajuan
                            ? \Carbon\Carbon::parse($data->tanggal_pengajuan)->translatedFormat('d F Y')
                            : '-' }}

                    </span>

                    <h3 class="timeline-header">

                        Pengajuan pencairan SHU dibuat oleh anggota.

                    </h3>

                </div>

            </li>

            @if($data->tanggal_persetujuan)

            <li>

                <i class="fas fa-check bg-success"></i>

                <div class="timeline-item">

                    <span class="time">

                        <i class="far fa-calendar"></i>

                        {{ \Carbon\Carbon::parse($data->tanggal_persetujuan)->translatedFormat('d F Y') }}

                    </span>

                    <h3 class="timeline-header">

                        Pengajuan telah disetujui.

                    </h3>

                </div>

            </li>

            @endif

            @if($data->tanggal_pencairan)

            <li>

                <i class="fas fa-money-check-alt bg-info"></i>

                <div class="timeline-item">

                    <span class="time">

                        <i class="far fa-calendar"></i>

                        {{ \Carbon\Carbon::parse($data->tanggal_pencairan)->translatedFormat('d F Y') }}

                    </span>

                    <h3 class="timeline-header">

                        Dana SHU berhasil ditransfer kepada anggota.

                    </h3>

                </div>

            </li>

            @endif

            @if($data->bukti)

            <li>

                <i class="fas fa-file-upload bg-success"></i>

                <div class="timeline-item">

                    <h3 class="timeline-header">

                        Bukti transfer telah diunggah.

                    </h3>

                </div>

            </li>

            @endif

        </ul>

    </div>

</div>


{{-- ==========================================
        TOMBOL KEMBALI
========================================== --}}

<div class="card shadow-sm">

    <div class="card-footer text-right">

        <a
            href="{{ route('pencairan.index') }}"
            class="btn btn-secondary">

            <i class="fas fa-arrow-left"></i>

            Kembali

        </a>

    </div>

</div>

</div>

{{-- ==========================================
        STYLE
========================================== --}}

<style>

.small-box{
    border-radius:12px;
    box-shadow:0 .125rem .25rem rgba(0,0,0,.075);
}

.small-box .inner h3{
    font-weight:700;
}

.card{
    border-radius:12px;
    border:none;
}

.card-header{
    font-weight:600;
}

.table th{
    width:35%;
    background:#f8f9fa;
    font-weight:600;
}

.table td,
.table th{
    vertical-align:middle !important;
}

.badge{
    font-size:14px;
    padding:10px 14px;
    border-radius:8px;
}

.timeline{
    position:relative;
    list-style:none;
    margin:0;
    padding:0;
}

.timeline:before{
    content:'';
    position:absolute;
    left:14px;
    top:0;
    bottom:0;
    width:2px;
    background:#dee2e6;
}

.timeline li{
    position:relative;
    margin-bottom:25px;
    padding-left:45px;
}

.timeline li:last-child{
    margin-bottom:0;
}

.timeline li i{
    position:absolute;
    left:0;
    top:0;
    width:30px;
    height:30px;
    line-height:30px;
    text-align:center;
    border-radius:50%;
    color:#fff;
}

.timeline-item{
    background:#fff;
    border:1px solid #e9ecef;
    border-radius:8px;
    padding:12px 15px;
    box-shadow:0 .125rem .25rem rgba(0,0,0,.05);
}

.timeline-item .time{
    float:right;
    color:#6c757d;
    font-size:13px;
}

.timeline-header{
    font-size:15px;
    margin:0;
    font-weight:600;
}

.img-preview{

    max-width:100%;
    max-height:500px;

    border-radius:10px;

    border:1px solid #dee2e6;

    box-shadow:0 .125rem .25rem rgba(0,0,0,.15);

}

.btn{
    border-radius:8px;
}

.alert{
    border-radius:10px;
}

</style>



{{-- ==========================================
        JAVASCRIPT
========================================== --}}

@push('js')

<script>

$(function(){

    /*
    |--------------------------------------------------------------------------
    | Auto Close Alert
    |--------------------------------------------------------------------------
    */

    setTimeout(function(){

        $('.alert').fadeOut('slow');

    },4000);

});

</script>

@endpush

@endsection