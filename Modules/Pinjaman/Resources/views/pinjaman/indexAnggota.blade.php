@extends('adminlte::page')

@section('title', 'Riwayat Pinjaman')

@section('content_header')
    <h1 class="m-0 text-dark">Riwayat Pinjaman</h1>
@stop

@section('content')

<div class="row">
    {{-- ===========================
        RIWAYAT PENGAJUAN
    ============================ --}}
    <div class="col-12">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fa-solid fa-circle-check"></i>
                {{ session('success') }}

                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fa-solid fa-circle-xmark"></i>
                {{ session('error') }}

                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        @endif
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">
                    Riwayat Pengajuan Pinjaman
                </h4>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr class="text-center">
                                <th width="5%">No</th>
                                <th>Tanggal</th>
                                <th>Skema</th>
                                <th>Nominal</th>
                                <th>Tenor</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                        @forelse($pengajuanPinjaman as $item)
                            <tr class="text-center">
                                <td class="text-center">
                                    {{ $loop->iteration }}
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->locale('id')->translatedFormat('d F Y') }}
                                </td>
                                <td>
                                    {{ $item->skemaPinjaman->nama }}
                                </td>
                                <td>
                                    Rp {{ number_format($item->jumlah_pengajuan,0,',','.') }}
                                </td>
                                <td class="text-center">
                                    {{ $item->lama_angsuran }} Bulan
                                </td>
                                <td class="text-center">
                                    @if($item->status_pengajuan == 'menunggu')
                                        <span class="badge badge-warning">
                                            Menunggu
                                        </span>
                                    @elseif($item->status_pengajuan == 'disetujui')
                                        <span class="badge badge-success">
                                            Disetujui
                                        </span>
                                    @elseif($item->status_pengajuan == 'revisi')
                                        <span class="badge badge-danger">
                                            Revisi jaminan
                                        </span>
                                    @elseif($item->status_pengajuan == 'verifikasi')
                                        <span class="badge badge-info">
                                            Verifikasi
                                        </span>
                                    @elseif($item->status_pengajuan == 'persetujuan_awal')
                                        <span class="badge badge-primary">
                                            Persetujuan
                                        </span>
                                    @elseif($item->status_pengajuan == 'persetujuan_akhir')
                                        <span class="badge badge-primary">
                                            Persetujuan Akhir
                                        </span>
                                    @elseif($item->status_pengajuan == 'pencairan')
                                        <span class="badge badge-primary">
                                            Pencairan
                                        </span>
                                    @else
                                        <span class="badge badge-danger">
                                            Ditolak
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-primary btn-sm" 
                                            data-toggle="modal" 
                                            data-target="#detailModal{{ $item->id }}">
                                            Detail
                                    </button>
                                    @if($item->status_pengajuan == 'menunggu')
                                        <a href="{{ route('pengajuanPinjaman.edit', ['id' => $item->id]) }}" class="btn btn-warning btn-sm">
                                            <i class="fa-solid fa-pen"></i>
                                            Edit
                                        </a>
                                        <button type="button"
                                                class="btn btn-danger btn-sm"
                                                data-toggle="modal"
                                                data-target="#modalBatal{{ $item->id }}">
                                            <i class="fa-solid fa-xmark"></i>
                                            Batal
                                        </button>
                                    @elseif($item->status_pengajuan == 'revisi')
                                        <a href="{{ route('pengajuanPinjaman.revisi', ['id' => $item->id]) }}"
                                            class="btn btn-info btn-sm">
                                                <i class="fas fa-upload"></i>
                                            Revisi Jaminan
                                        </a>
                                    @elseif($item->status_pengajuan == 'persetujuan_awal' || $item->status_pengajuan == 'persetujuan_akhir' || $item->status_pengajuan == 'ditolak')
                                        <a href="{{ route('pengajuanPinjaman.cetak', ['id' => $item->id]) }}" class="btn btn-danger btn-sm">
                                            <i class="fas fa-file-pdf"></i>
                                            Dokumen
                                        </a>
                                    @elseif($item->status_pengajuan == 'disetujui' || $item->status_pengajuan == 'pencairan' )
                                        <button type="button"
                                            class="btn btn-info btn-sm btn-detail"
                                            data-toggle="modal"
                                            data-target="#modalBukti"
                                            data-image="{{ asset('dokumen_pinjaman/' . $item->dokumen_ttd) }}">
                                            <i class="fas fa-file"></i>
                                            Dokumen
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">
                                    Belum ada pengajuan pinjaman.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    {{-- ===========================
        PINJAMAN AKTIF
    ============================ --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">
                    Pinjaman Saya
                </h4>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr class="text-center">
                                <th>No</th>
                                <th>Mulai tanggal</th>
                                <th>Nominal</th>
                                <th>Sisa Pinjaman</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>
                        @forelse($pinjaman as $items)
                            <tr class="text-center">
                                <td class="text-center">
                                    {{ $loop->iteration }}
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($items->tanggal_pinjaman)->locale('id')->translatedFormat('d F Y') }}
                                </td>
                                <td>
                                    Rp {{ number_format($items->total_pinjaman,0,',','.') }}
                                </td>
                                <td>
                                    @php
                                        $sisaPinjaman = $items->total_pinjaman - ($items->total_dibayar ?? 0);
                                    @endphp

                                    Rp {{ number_format($sisaPinjaman, 0, ',', '.') }}
                                </td>
                                <td class="text-center">
                                    @if($items->status_pinjaman == 'aktif')
                                        <span class="badge badge-primary">
                                            Berjalan
                                        </span>
                                    @elseif($items->status_pinjaman == 'selesai')
                                        <span class="badge badge-success">
                                            Lunas
                                        </span>
                                    @else
                                        <span class="badge badge-secondary">
                                            {{ ucfirst($items->status_pinjaman) }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6"
                                    class="text-center text-muted">
                                    Belum memiliki pinjaman.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@stop

<div class="modal fade" id="modalBukti" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    Dokumen pengajuan pinjaman
                </h5>

                <button type="button"
                        class="close"
                        data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body text-center">
                <img id="previewBukti"
                    src=""
                    class="img-fluid rounded"
                    style="max-height:600px;">
            </div>
        </div>
    </div>
</div>

@foreach ($pengajuanPinjaman as $item)
<div class="modal fade"
    id="modalBatal{{ $item->id }}"
    tabindex="-1"
    role="dialog"
    aria-hidden="true">

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title">
                    Konfirmasi Pembatalan
                </h5>
                <button type="button"
                        class="close"
                        data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">
                Apakah Anda yakin ingin membatalkan pengajuan pinjaman ini?
            </div>

            <div class="modal-footer">
                <button type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal">
                    Tidak
                </button>

                <form action="{{ route('pengajuanPinjaman.destroy', $item->id) }}"
                        method="POST">

                    @csrf
                    @method('DELETE')

                    <button type="submit"
                            class="btn btn-danger">
                        Ya, Batalkan
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
@endforeach

@foreach($pengajuanPinjaman as $item)
<div class="modal fade"
    id="detailModal{{ $item->id }}"
    tabindex="-1"
    role="dialog"
    aria-hidden="true">

    <div class="modal-dialog modal-lg" role="document">

        <div class="modal-content">

            {{-- HEADER --}}
            <div class="modal-header bg-primary">

                <h5 class="modal-title">
                    Detail Pengajuan Pinjaman
                </h5>

                <button type="button"
                        class="close text-white"
                        data-dismiss="modal"
                        aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>

            {{-- BODY --}}
            <div class="modal-body">

                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-bordered">

                        <tr>
                            <th width="35%">Nama Anggota</th>
                            <td>{{ $item->users->name }}</td>
                        </tr>

                        <tr>
                            <th>Skema</th>
                            <td>{{ $item->skemaPinjaman->nama }}</td>
                        </tr>

                        <tr>
                            <th>Pengajuan</th>
                            <td>
                                Rp.
                                {{ number_format($item->jumlah_pengajuan, 0, ',', '.') }}
                            </td>
                        </tr>

                        <tr>
                            <th>Tenor</th>
                            <td>
                                {{ $item->lama_angsuran }}
                            </td>
                        </tr>

                        <tr>
                            <th>Bunga</th>
                            <td>
                                {{ $item->skemaPinjaman->bunga }} %
                            </td>
                        </tr>

                        <tr>
                            <th>No HP</th>
                            <td>
                                {{ $item->no_hp }}
                            </td>
                        </tr>

                        <tr>
                            <th>No KTP</th>
                            <td>
                                {{ $item->no_ktp }}
                            </td>
                        </tr>

                        <tr>
                            <th>No Rekening</th>
                            <td>
                                {{ $item->no_rekening }}
                            </td>
                        </tr>
                        </table>
                    </div>

                    <div class="col-md-6">
                        <table class="table table-bordered">
                        <tr>
                            <th>Alamat</th>
                            <td>
                                {{ $item->alamat }}
                            </td>
                        </tr>

                        <tr>
                            <th>Nama istri/suami</th>
                            <td>
                                {{ $item->nama_istri_suami }}
                            </td>
                        </tr>

                        @if($item->pinjaman)
                        <tr>
                            <th>Jumlah Disetujui</th>
                            <td>
                                Rp.
                                {{ number_format($item->pinjaman->jumlah_disetujui, 0, ',', '.') }}
                            </td>
                        </tr>

                        <tr>
                            <th>Jumlah Bunga</th>
                            <td>
                                Rp.
                                {{ number_format($item->pinjaman->jumlah_bunga, 0, ',', '.') }}
                            </td>
                        </tr>

                        <tr>
                            <th>Total Pinjaman</th>
                            <td>
                                Rp.
                                {{ number_format($item->pinjaman->total_pinjaman, 0, ',', '.') }}
                            </td>
                        </tr>
                        @endif

                        <tr>
                            <th>Tanggal pengajuan</th>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->locale('id')->translatedFormat('d F Y') }}</td>
                        </tr>
                        
                        @if($item->pinjaman)
                        <tr>
                            <th>Tanggal Disetujui</th>
                            <td>{{ \Carbon\Carbon::parse($item->pinjaman->tanggal_disetujui)->locale('id')->translatedFormat('d F Y') }}</td>
                        </tr>
                        @endif
                        
                        <tr>
                            <th>Status Pengajuan</th>
                            <td>
                                @if($item->status_pengajuan == 'menunggu')
                                    <span class="badge badge-warning">
                                        Menunggu
                                    </span>
                                @elseif($item->status_pengajuan == 'disetujui')
                                    <span class="badge badge-success">
                                        Disetujui
                                    </span>
                                @elseif($item->status_pengajuan == 'revisi')
                                    <span class="badge badge-danger">
                                        Revisi jaminan
                                    </span>
                                @elseif($item->status_pengajuan == 'verifikasi')
                                    <span class="badge badge-info">
                                        Verifikasi
                                    </span>
                                @elseif($item->status_pengajuan == 'persetujuan_awal')
                                    <span class="badge badge-primary">
                                        Persetujuan
                                    </span>
                                @else
                                    <span class="badge badge-danger">
                                        Ditolak
                                    </span>
                                @endif
                            </td>
                        </tr>
                        </table>
                    </div>
                </div>

                <hr>

                <h5 class="mb-3">
                    Persetujuan
                </h5>

                <table class="table table-bordered">
                    <thead class="thead-light">
                        <tr class="text-center">
                            <th>Jabatan</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>

                    <tbody>

                        {{-- Bendahara --}}
                        <tr>
                            <td>Bendahara</td>

                            <td>
                                @if($item->persetujuan_bendahara)
                                    {{ ucfirst($item->persetujuan_bendahara->status) }}
                                @else
                                    Belum di proses
                                @endif
                            </td>

                            <td>
                                @if($item->persetujuan_bendahara)
                                    {{ \Carbon\Carbon::parse($item->persetujuan_bendahara->tanggal_disetujui)->locale('id')->translatedFormat('d F Y') }}
                                @else
                                    -
                                @endif
                            </td>

                            <td>
                                {{ $item->persetujuan_bendahara->catatan ?? '-' }}
                            </td>
                        </tr>

                        {{-- Wakil Direktur --}}
                        <tr>
                            <td>Wakil Direktur</td>

                            <td>
                                @if($item->persetujuan_wadir)
                                    {{ ucfirst($item->persetujuan_wadir->status) }}
                                @else
                                    Belum diproses
                                @endif
                            </td>

                            <td>
                                @if($item->persetujuan_wadir)
                                    {{ \Carbon\Carbon::parse($item->persetujuan_wadir->tanggal_disetujui)->locale('id')->translatedFormat('d F Y') }}
                                @else
                                    -
                                @endif
                            </td>

                            <td>
                                {{ $item->persetujuan_wadir->catatan ?? '-' }}
                            </td>
                        </tr>

                        {{-- Ketua --}}
                        <tr>
                            <td>Ketua</td>

                            <td>
                                @if($item->persetujuan_ketua)
                                    {{ ucfirst($item->persetujuan_ketua->status) }}
                                @else
                                    Belum diproses
                                @endif
                            </td>

                            <td>
                                @if($item->persetujuan_ketua)
                                    {{ \Carbon\Carbon::parse($item->persetujuan_ketua->tanggal_disetujui)->locale('id')->translatedFormat('d F Y') }}
                                @else
                                    -
                                @endif
                            </td>

                            <td>
                                {{ $item->persetujuan_ketua->catatan ?? '-' }}
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>

            {{-- FOOTER --}}
            <div class="modal-footer">
                <button type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
@endforeach

@push('js')
<script>
$(document).ready(function () {

    $('.btn-detail').click(function () {
        let image = $(this).data('image');

        $('#previewBukti').attr('src', image);
    });

});
</script>
@endpush