@extends('adminlte::page')

@section('title', 'Data Simpanan Wajib')

@section('content_header')

<div class="d-flex justify-content-between align-items-center">

    <div>

        <h1 class="m-0 text-dark">
            Data Simpanan Wajib
        </h1>

        <small class="text-muted">
            Kelola data simpanan wajib anggota koperasi.
        </small>

    </div>

</div>

@stop

@section('content')

@php
    $cardClass = auth()->user()->hasRole('admin')
        ? 'col-lg-3 col-md-6'
        : 'col-lg-4 col-md-6';
@endphp

<div class="row">

    {{-- Total Simpanan Wajib --}}
    <div class="{{ $cardClass }}">

        <div class="small-box bg-info">

            <div class="inner">

                <h3 class="mb-1">
                    Rp {{ number_format($summary['totalNominal'],0,',','.') }}
                </h3>

                <p class="mb-0">
                    Total Simpanan Wajib
                </p>

            </div>

            <div class="icon">

                <i class="fas fa-wallet"></i>

            </div>

        </div>

    </div>

    {{-- Menunggu Verifikasi --}}
    <div class="{{ $cardClass }}">

        <div class="small-box bg-warning">

            <div class="inner">

                <h3 class="mb-1">
                    {{ $summary['pending'] }}
                </h3>

                <p class="mb-0">
                    Menunggu Verifikasi
                </p>

            </div>

            <div class="icon">

                <i class="fas fa-clock"></i>

            </div>

        </div>

    </div>

    {{-- Simpanan Disetujui --}}
    <div class="{{ $cardClass }}">

        <div class="small-box bg-success">

            <div class="inner">

                <h3 class="mb-1">
                    {{ $summary['selesai'] }}
                </h3>

                <p class="mb-0">
                    Simpanan Disetujui
                </p>

            </div>

            <div class="icon">

                <i class="fas fa-check-circle"></i>

            </div>

        </div>

    </div>

    @role('admin')

        {{-- Total Anggota Membayar --}}
        <div class="{{ $cardClass }}">

            <div class="small-box bg-primary">

                <div class="inner">

                    <h3 class="mb-1">
                        {{ $summary['totalAnggota'] }}
                    </h3>

                    <p class="mb-0">
                        Anggota Membayar
                    </p>

                </div>

                <div class="icon">

                    <i class="fas fa-users"></i>

                </div>

            </div>

        </div>

    @endrole

</div>



<div class="card">
    <div class="card-header">

    <div class="row align-items-end">

        {{-- Tombol --}}
        <div class="col-lg-5 col-md-12 mb-2">

            @role('admin')

                <a href="{{ route('simpanan-wajib.create') }}"
                   class="btn btn-primary">

                    <i class="fas fa-plus"></i>
                    Tambah Simpanan

                </a>

                <button
                    type="button"
                    class="btn btn-success"
                    data-toggle="modal"
                    data-target="#modalExportAutoDebit">

                    <i class="fas fa-file-pdf"></i>
                    Export Auto Debit

                </button>

            @endrole

        </div>

        {{-- Filter --}}
        <div class="col-lg-7 col-md-12">

            <form method="GET">

                <div class="row">

                    {{-- Bulan --}}
                    <div class="col-md-4">

                        <label>
                            Bulan
                        </label>

                        <select
                            name="bulan"
                            class="form-control">

                            <option value="">
                                Semua Bulan
                            </option>

                            @for($i=1;$i<=12;$i++)

                                <option
                                    value="{{ $i }}"
                                    {{ request('bulan') == $i ? 'selected' : '' }}>

                                    {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}

                                </option>

                            @endfor

                        </select>

                    </div>

                    {{-- Tahun --}}
                    <div class="col-md-3">

                        <label>
                            Tahun
                        </label>

                        <select
                            name="tahun"
                            class="form-control">

                            <option value="">
                                Semua Tahun
                            </option>

                            @for($i=date('Y');$i>=2024;$i--)

                                <option
                                    value="{{ $i }}"
                                    {{ request('tahun') == $i ? 'selected' : '' }}>

                                    {{ $i }}

                                </option>

                            @endfor

                        </select>

                    </div>

                    {{-- Cari --}}
                    <div class="col-md-2">

                        <label>&nbsp;</label>

                        <button
                            type="submit"
                            class="btn btn-primary btn-block">

                            <i class="fas fa-search"></i>
                            Cari

                        </button>

                    </div>

                    {{-- Reset --}}
                    <div class="col-md-3">

                        <label>&nbsp;</label>

                        <a
                            href="{{ route('simpanan-wajib.index') }}"
                            class="btn btn-secondary btn-block">

                            <i class="fas fa-sync-alt"></i>
                            Reset

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>
<div class="card-body">

    <div class="table-responsive">

        <table class="table table-bordered table-hover">

            <thead class="text-center">

                <tr>

                    <th width="5%">No</th>
                    <th>Nama Anggota</th>
                    <th>Nominal</th>
                    <th>Periode</th>
                    <th>Tahun</th>
                    <th>Bukti</th>
                    <th>Status</th>
                    <th width="15%">Aksi</th>

                </tr>

            </thead>

            <tbody>

                @forelse($data as $item)

                    <tr>

                        <td class="text-center align-middle">

                            {{ $loop->iteration + ($data->currentPage() - 1) * $data->perPage() }}

                        </td>

                        <td class="align-middle">

                            {{ $item->user->name ?? '-' }}

                        </td>

                        <td class="align-middle">

                            <strong>
                                Rp {{ number_format($item->nilai,0,',','.') }}
                            </strong>

                        </td>

                        <td class="text-center align-middle">

                            {{ \Carbon\Carbon::parse($item->periode)->format('d-m-Y') }}

                        </td>

                        <td class="text-center align-middle">

                            {{ $item->tahun }}

                        </td>

                        <td class="text-center align-middle">

                            @if($item->bukti)

                                <a
                                    href="{{ asset('storage/'.$item->bukti) }}"
                                    target="_blank"
                                    class="btn btn-info btn-sm">

                                    <i class="fas fa-file-alt"></i>
                                    Lihat

                                </a>

                            @else

                                <span class="badge badge-secondary">

                                    Belum Upload

                                </span>

                            @endif

                        </td>

                        <td class="text-center align-middle">

                            @if($item->status == 'pending')

                                <span class="badge badge-warning">

                                    <i class="fas fa-clock"></i>

                                    Pending

                                </span>

                            @elseif($item->status == 'selesai')

                                <span class="badge badge-success">

                                    <i class="fas fa-check-circle"></i>

                                    Selesai

                                </span>

                            @else

                                <span class="badge badge-danger">

                                    <i class="fas fa-times-circle"></i>

                                    Tidak Berhasil

                                </span>

                            @endif

                        </td>

                        <td class="text-center align-middle">

                            @role('admin')

                                <a
                                    href="{{ route('simpanan-wajib.show',$item->id) }}"
                                    class="btn btn-success btn-sm">

                                    <i class="fas fa-check-circle"></i>

                                    Verifikasi

                                </a>

                            @else

                                @if($item->status == 'pending')

                                    <button
                                        class="btn btn-secondary btn-sm"
                                        disabled>

                                        <i class="fas fa-clock"></i>

                                        Menunggu

                                    </button>

                                @elseif($item->status == 'selesai')

                                    <button
                                        class="btn btn-success btn-sm"
                                        disabled>

                                        <i class="fas fa-check"></i>

                                        Selesai

                                    </button>

                                @else

                                    <button
                                        class="btn btn-danger btn-sm"
                                        disabled>

                                        <i class="fas fa-times"></i>

                                        Gagal

                                    </button>

                                @endif

                            @endrole

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="8"
                            class="text-center text-muted">

                            Belum ada data simpanan wajib.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div class="mt-3 d-flex justify-content-center">

        {{ $data->links() }}

    </div>

</div>
</div>
<div class="modal fade"
     id="modalExportAutoDebit"
     tabindex="-1"
     role="dialog">

    <div class="modal-dialog">

        <form
            method="GET"
            action="{{ route('simpanan-wajib.export-auto-debit') }}">

            <div class="modal-content">

                <div class="modal-header bg-success">

                    <h5 class="modal-title">

                        <i class="fas fa-file-pdf"></i>

                        Export Auto Debit

                    </h5>

                    <button
                        type="button"
                        class="close text-white"
                        data-dismiss="modal">

                        <span>&times;</span>

                    </button>

                </div>

                <div class="modal-body">

                    <div class="alert alert-info">

                        <i class="fas fa-info-circle"></i>

                        Pilih periode data yang ingin diekspor.

                    </div>

                    <div class="form-group">

                        <label>

                            Bulan

                        </label>

                        <select
                            name="bulan"
                            class="form-control">

                            <option value="">
                                Semua Bulan
                            </option>

                            @for($i=1;$i<=12;$i++)

                                <option value="{{ $i }}">

                                    {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}

                                </option>

                            @endfor

                        </select>

                    </div>

                    <div class="form-group">

                        <label>

                            Tahun

                        </label>

                        <select
                            name="tahun"
                            class="form-control">

                            @for($i=date('Y');$i>=2024;$i--)

                                <option value="{{ $i }}">

                                    {{ $i }}

                                </option>

                            @endfor

                        </select>

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal">

                        <i class="fas fa-times"></i>

                        Batal

                    </button>

                    <button
                        type="submit"
                        class="btn btn-success">

                        <i class="fas fa-file-pdf"></i>

                        Export PDF

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>
@stop

@section('css')

<style>

.table td,
.table th{
    vertical-align: middle !important;
}

.table-hover tbody tr:hover{
    background-color:#f8f9fa;
}

.badge{
    font-size: 90%;
    padding: .45em .75em;
}

.small-box h3{
    font-size:1.8rem;
    font-weight:600;
}

.small-box p{
    font-size:15px;
    margin-bottom:0;
}

.card-header{
    background:#fff;
}

.btn{
    border-radius:6px;
}

.pagination{
    justify-content:center;
}

.small-box {
    position: relative;
    top:35%;

    transform:translateY(-50%);

}

.small-box .icon {
   position: absolute;
    top: 1%;
    right: 25px;
    transform: translateY(-50%);

}

.small-box .icon i {
    font-size: 55px !important;
}
</style>

@stop