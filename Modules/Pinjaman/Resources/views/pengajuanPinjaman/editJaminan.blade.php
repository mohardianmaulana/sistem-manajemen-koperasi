@extends('adminlte::page')

@section('title', 'Revisi Jaminan')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h1 class="m-0 text-dark">
        Revisi Dokumen Jaminan
    </h1>
</div>
@stop

@section('content')

@if($pengajuan->status_pengajuan == 'revisi')
<div class="alert alert-danger">
    <h5>
        <i class="icon fas fa-exclamation-triangle"></i>
        Perlu Revisi Dokumen
    </h5>

    Beberapa dokumen jaminan Anda ditolak oleh admin.
    Silakan upload ulang hanya dokumen yang berstatus
    <strong>Ditolak</strong>.
</div>
@endif

<a href="{{ route('pinjaman.indexAnggota') }}"
    class="btn btn-secondary mb-3">
    <i class="fas fa-arrow-left"></i>
    Kembali
</a>

<form action="{{ route('pengajuanPinjaman.simpanRevisi',$pengajuan->id) }}"
        method="POST"
        enctype="multipart/form-data">

    @csrf
    @method('PATCH')

    {{-- ========================================= --}}
    {{-- INFORMASI PENGAJUAN --}}
    {{-- ========================================= --}}

    <div class="card card-primary">

        <div class="card-header">
            <h3 class="card-title">
                Informasi Pengajuan
            </h3>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th width="180">
                                Tanggal Pengajuan
                            </th>

                            <td>
                                {{ \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan)->translatedFormat('d F Y') }}
                            </td>
                        </tr>

                        <tr>
                            <th>
                                Skema Pinjaman
                            </th>

                            <td>
                                {{ $pengajuan->skemaPinjaman->nama }}
                            </td>
                        </tr>

                        <tr>
                            <th>
                                Nominal
                            </th>

                            <td>
                                Rp {{ number_format($pengajuan->jumlah_pengajuan,0,',','.') }}
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th width="180">
                                Lama Angsuran
                            </th>

                            <td>
                                {{ $pengajuan->lama_angsuran }} Bulan
                            </td>
                        </tr>

                        <tr>
                            <th>
                                Status
                            </th>
                            <td>
                                @if($pengajuan->status_pengajuan=='revisi')
                                    <span class="badge badge-danger">
                                        Perlu Revisi
                                    </span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- ========================================= --}}
    {{-- DOKUMEN JAMINAN --}}
    {{-- ========================================= --}}

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                Dokumen Jaminan
            </h3>
        </div>

        <div class="card-body">
            @foreach($pengajuan->jaminan as $jaminan)

            <div class="card mb-4">
                <div class="card-header
                    @if($jaminan->pivot->status_verifikasi == 'verifikasi')
                        bg-success
                    @elseif($jaminan->pivot->status_verifikasi == 'ditolak')
                        bg-danger
                    @else
                        bg-warning
                    @endif
                    text-white">

                    <h3 class="card-title">
                        {{ $jaminan->nama }}
                    </h3>

                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <strong>Status</strong>
                            <br>
                            @if($jaminan->pivot->status_verifikasi=='verifikasi')
                                <span class="badge badge-success">
                                    Disetujui
                                </span>
                            @elseif($jaminan->pivot->status_verifikasi=='ditolak')
                                <span class="badge badge-danger">
                                    Ditolak
                                </span>
                            @else
                                <span class="badge badge-warning">
                                    Menunggu Verifikasi
                                </span>
                            @endif
                        </div>

                        <div class="col-md-4">
                            <strong>File Saat Ini</strong>
                            <br>
                            <a href="{{ asset('jaminan/'.$jaminan->pivot->file_jaminan) }}"
                                target="_blank"
                                class="btn btn-sm btn-outline-primary mt-2">
                                <i class="fas fa-file"></i>
                                Lihat Dokumen
                            </a>
                        </div>

                        <div class="col-md-5">
                            @if($jaminan->pivot->status_verifikasi=='ditolak')
                                <strong>
                                    Catatan Admin
                                </strong>

                                <div class="alert alert-danger mt-2 mb-3">
                                    {{ $jaminan->pivot->keterangan }}
                                </div>
                                <label>
                                    Upload Ulang
                                </label>

                                <input
                                    type="file"
                                    name="jaminan[{{ $jaminan->id }}]"
                                    class="form-control">

                            @elseif($jaminan->pivot->status_verifikasi=='verifikasi')
                                <div class="alert alert-success mb-0">
                                    <i class="fas fa-check-circle"></i>
                                    Dokumen telah disetujui.
                                </div>
                            @else
                                <div class="alert alert-warning mb-0">
                                    <i class="fas fa-clock"></i>
                                    Dokumen sedang diverifikasi admin.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- ========================================= --}}
    {{-- BUTTON --}}
    {{-- ========================================= --}}

    <div class="card">
        <div class="card-body text-right">
            <a href="{{ route('pinjaman.indexAnggota') }}"
                class="btn btn-secondary">
                <i class="fas fa-times"></i>
                Batal
            </a>

            <button
                class="btn btn-primary">
                <i class="fas fa-save"></i>
                Simpan Revisi
            </button>
        </div>
    </div>

</form>

@stop