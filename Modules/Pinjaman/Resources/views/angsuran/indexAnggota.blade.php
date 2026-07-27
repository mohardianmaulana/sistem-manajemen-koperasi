@extends('adminlte::page')

@section('title', 'Daftar angsuran pinjaman')

@section('content_header')
    <h1 class="m-0 text-dark">Angsuran pinjaman</h1>
@stop

@section('css')
<style>
    .small-box .icon i {
        font-size: 45px !important;
        top: 20px;
    }

    .small-box .inner h5 {
        font-size: 20px;
        font-weight: bold;
    }

    .small-box {
        border-radius: 10px;
    }
</style>
@stop

@section('content')
    @if($angsuran->isNotEmpty())
    <div class="row">
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
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h5>Rp {{ number_format($angsuran->first()->pinjaman->jumlah_disetujui, 0, ',', '.') }}</h5>
                                    <p>Jumlah Pinjaman</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h5>Rp {{ number_format($angsuran->first()->pinjaman->jumlah_bunga, 0, ',', '.') }}</h5>
                                    <p>Jumlah Bunga</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-percent"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h5>Rp {{ number_format($angsuran->first()->pinjaman->total_pinjaman, 0, ',', '.') }}</h5>
                                    <p>Total Pinjaman</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-wallet"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h5>
                                        Rp {{ number_format($angsuran->where('status_bayar', '!=', 'lunas')->sum('jumlah_angsuran'), 0, ',', '.') }}
                                    </h5>
                                    <p>Sisa Pinjaman</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-hand-holding-usd"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- TABEL --}}
                    <div class="table-responsive mt-2">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="text-center">Angsuran ke</th>
                                    <th class="text-center">Jumlah angsuran</th>
                                    <th class="text-center">Tanggal jatuh tempo</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($angsuran as $item)
                                    <tr class="text-center">
                                        <td class="text-center">{{ $item->angsuran_ke }}</td>
                                        <td class="text-center">Rp. {{ number_format($item->jumlah_angsuran, 0, ',', '.') }}</td>
                                        <td class="text-center">
                                            {{ \Carbon\Carbon::parse($item->tanggal_jatuh_tempo)->locale('id')->translatedFormat('d F Y') }}
                                        </td>
                                        <td>
                                            @if ($item->status_bayar == 'belum_bayar')
                                                <span class="badge badge-info">
                                                    Belum bayar
                                                </span>
                                            @elseif ($item->status_bayar == 'lunas')
                                                <span class="badge badge-success">
                                                    Lunas
                                                </span>
                                            @elseif ($item->status_bayar == 'gagal_debet')
                                                <span class="badge badge-danger">
                                                    Gagal debet
                                                </span>
                                            @elseif ($item->status_bayar == 'verifikasi')
                                                <span class="badge badge-secondary">
                                                    Verifikasi
                                                </span>
                                            @elseif ($item->status_bayar == 'gagal_verifikasi')
                                                <span class="badge badge-danger">
                                                    Gagal verifikasi
                                                </span>
                                            @else
                                                <span class="badge badge-secondary">
                                                    {{ $item->status_pinjaman }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($item->status_bayar == 'belum_bayar')
                                                <button class="btn btn-secondary btn-sm" disabled>
                                                    Menunggu Auto Debet
                                                </button>
                                            @elseif ($item->status_bayar == 'gagal_debet')
                                                @php
                                                    $jatuhTempo = \Carbon\Carbon::parse($item->tanggal_jatuh_tempo);
                                                    $masihBulanYangSama =
                                                        $jatuhTempo->month == now()->month &&
                                                        $jatuhTempo->year == now()->year;
                                                @endphp
                                                @if($masihBulanYangSama)
                                                    <button type="button"
                                                        class="btn btn-warning btn-sm btn-bayar text-white"
                                                        data-toggle="modal"
                                                        data-target="#modalBayar"
                                                        data-id="{{ $item->id }}"
                                                        data-jumlah="{{ $item->jumlah_angsuran }}"
                                                        data-total="{{ $item->total_tagihan }}">
                                                        Bayar
                                                    </button>
                                                @else
                                                    <button class="btn btn-secondary btn-sm" disabled>
                                                        Digabung Tagihan Berikutnya
                                                    </button>
                                                @endif
                                            @elseif ($item->status_bayar == 'verifikasi')
                                                <button class="btn btn-primary btn-sm" disabled>
                                                    Verifikasi
                                                </button>
                                            @elseif ($item->status_bayar == 'gagal_verifikasi')
                                                @php
                                                    $jatuhTempo = \Carbon\Carbon::parse($item->tanggal_jatuh_tempo);
                                                    $masihBulanYangSama =
                                                        $jatuhTempo->month == now()->month &&
                                                        $jatuhTempo->year == now()->year;
                                                @endphp
                                                @if($masihBulanYangSama)
                                                    <button type="button"
                                                        class="btn btn-warning btn-sm btn-bayar-gagal text-white"
                                                        data-toggle="modal"
                                                        data-target="#modalBayarGagalVerifikasi"
                                                        data-id="{{ $item->id }}"
                                                        data-jumlah="{{ $item->jumlah_angsuran }}"
                                                        data-total="{{ $item->total_tagihan }}"
                                                        data-catatan="{{ $item->catatan_verifikasi }}">
                                                        Bayar
                                                    </button>
                                                @else
                                                    <button class="btn btn-secondary btn-sm" disabled>
                                                        Digabung Tagihan Berikutnya
                                                    </button>
                                                @endif
                                            @elseif ($item->status_bayar == 'lunas')
                                                @if($item->pembayaran)
                                                <button class="btn btn-primary btn-sm" 
                                                        data-toggle="modal" 
                                                        data-target="#detailModal{{ $item->id }}">
                                                        Detail
                                                </button>
                                                @endif
                                                <button class="btn btn-success btn-sm" disabled>
                                                    Sudah Lunas
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            Data pinjaman belum tersedia
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
    @else
    <div class="alert alert-info text-center">
        Anda belum memiliki pinjaman aktif.
    </div>
    @endif
@stop

<div class="modal fade" id="modalBayar" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">

            <form action="{{ route('pembayaran.store_manual') }}"
                method="POST"
                enctype="multipart/form-data">

                @csrf

                <input type="hidden"
                    name="id_angsuran"
                    id="id_angsuran">

                <input type="hidden"
                    name="jumlah_bayar"
                    id="total_tagihan_input">

                <div class="modal-header">
                    <h5 class="modal-title">
                        Pembayaran Angsuran
                    </h5>

                    <button type="button"
                        class="close"
                        data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="form-group">
                        <label>Rincian Pembayaran</label>

                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th width="50%">Angsuran Bulan Ini</th>
                                    <td class="text-right">
                                        <span id="angsuran_bulan_ini"></span>
                                    </td>
                                </tr>

                                <tr>
                                    <th>Total Tunggakan</th>
                                    <td class="text-right">
                                        <span id="total_tunggakan"></span>
                                    </td>
                                </tr>

                                <tr class="table-primary">
                                    <th>Total Pembayaran</th>
                                    <td class="text-right font-weight-bold">
                                        <span id="total_pembayaran"></span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="form-group text-center">
                        <label>QRIS Pembayaran</label>

                        <div class="mt-2">
                            <img src="{{ asset('images/qris.jpg') }}"
                                class="img-fluid"
                                style="max-width:250px;">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>
                            Upload Bukti Pembayaran
                            <span class="text-danger">*</span>
                        </label>

                        <input type="file"
                            name="bukti_pembayaran"
                            class="form-control"
                            accept="image/*"
                            required>
                        <small class="form-text text-muted">
                            Format file <strong>JPG, PNG, JPEG</strong> dengan ukuran maksimal <strong>2 MB</strong>.
                        </small>
                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit"
                        class="btn btn-success">
                        Bayar
                    </button>

                </div>

            </form>

        </div>
    </div>
</div>

<div class="modal fade" id="modalBayarGagalVerifikasi" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">

            <form action="{{ route('pembayaran.store_ulang_manual') }}"
                method="POST"
                enctype="multipart/form-data">

                @csrf

                <input type="hidden"
                    name="id_angsuran"
                    id="id_angsuran_gagal">

                <input type="hidden"
                    name="jumlah_bayar"
                    id="total_tagihan_gagal">

                <div class="modal-header">
                    <h5 class="modal-title">
                        Pembayaran Angsuran
                    </h5>

                    <button type="button"
                        class="close"
                        data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="form-group">
                        <label>Catatan</label>

                        <input type="text"
                            id="catatan"
                            class="form-control"
                            readonly>
                    </div>
                    <div class="form-group">
                        <label>Rincian Pembayaran</label>

                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th width="50%">Angsuran Bulan Ini</th>
                                    <td class="text-right">
                                        <span id="jumlah_angsuran_gagal"></span>
                                    </td>
                                </tr>

                                <tr>
                                    <th>Total Tunggakan</th>
                                    <td class="text-right">
                                        <span id="total_tunggakan_gagal"></span>
                                    </td>
                                </tr>

                                <tr class="table-primary">
                                    <th>Total Pembayaran</th>
                                    <td class="text-right font-weight-bold">
                                        <span id="total_pembayaran_gagal"></span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="form-group text-center">
                        <label>QRIS Pembayaran</label>

                        <div class="mt-2">
                            <img src="{{ asset('images/qris.jpg') }}"
                                class="img-fluid"
                                style="max-width:250px;">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>
                            Upload Bukti Pembayaran
                            <span class="text-danger">*</span>
                        </label>

                        <input type="file"
                            name="bukti_pembayaran"
                            class="form-control"
                            accept="image/*"
                            required>
                            <small class="form-text text-muted">
                                Format file <strong>JPG, PNG, JPEG</strong> dengan ukuran maksimal <strong>2 MB</strong>.
                            </small>
                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit"
                        class="btn btn-success">
                        Bayar
                    </button>

                </div>

            </form>

        </div>
    </div>
</div>

@foreach ($angsuran as $item)
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
                    Detail pembayaran angsuran
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
                    <div class="col-md-12">
                        <table class="table table-bordered">

                        <tr>
                            <th width="35%">Nama Anggota</th>
                            <td>{{ $item->pinjaman->pengajuan->users->name }}</td>
                        </tr>

                        <tr>
                            <th>Angsuran ke</th>
                            <td>{{ $item->angsuran_ke }}</td>
                        </tr>

                        <tr>
                            <th>Jumlah bayar</th>
                            <td>
                                Rp.
                                {{ number_format($item->pembayaran?->jumlah_bayar, 0, ',', '.') }}
                            </td>
                        </tr>

                        <tr>
                            <th>Jenis pembayaran</th>
                            <td>
                                {{ $item->pembayaran?->jenis_pembayaran }}
                            </td>
                        </tr>

                        <tr>
                            <th>Tanggal bayar</th>
                            <td>
                                {{ $item->pembayaran?->tanggal_bayar }}
                            </td>
                        </tr>

                        <tr>
                            <th>Bukti pembayaran</th>
                            <td>
                                @if($item->pembayaran && $item->pembayaran->bukti_pembayaran)
                                    <a href="{{ asset('bukti_pembayaran/'.$item->pembayaran->bukti_pembayaran) }}"
                                        target="_blank"
                                        class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                        Lihat
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th>Status pembayaran</th>
                            <td>
                                {{ $item->pembayaran?->status_pembayaran }}
                            </td>
                        </tr>
                        </table>
                    </div>
                </div>

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

@section('css')
	 <!--some css
    <link rel="stylesheet" href="/assets/css/admin_custom.css">-->
@stop

@push('js')
<script>

$(document).ready(function () {

    $('.btn-bayar').click(function () {

        let id = $(this).data('id');
        let jumlah = Number($(this).data('jumlah'));
        let total  = Number($(this).data('total'));

        let tunggakan = total - jumlah;

        $('#id_angsuran').val(id);
        $('#total_tagihan_input').val(total);

        $('#angsuran_bulan_ini').text(
            'Rp. ' + jumlah.toLocaleString('id-ID')
        );

        $('#total_tunggakan').text(
            'Rp. ' + tunggakan.toLocaleString('id-ID')
        );

        $('#total_pembayaran').text(
            'Rp. ' + total.toLocaleString('id-ID')
        );

    });

    $('.btn-bayar-gagal').click(function () {
        let id = $(this).data('id');
        let jumlah = $(this).data('jumlah');
        let catatan = $(this).data('catatan');
        let total  = Number($(this).data('total'));

        let tunggakan = total - jumlah;

        $('#id_angsuran_gagal').val(id);
        $('#total_tagihan_gagal').val(total);

        $('#jumlah_angsuran_gagal').text(
            'Rp. ' + jumlah.toLocaleString('id-ID')
        );

        $('#total_tunggakan_gagal').text(
            'Rp. ' + tunggakan.toLocaleString('id-ID')
        );

        $('#total_pembayaran_gagal').text(
            'Rp. ' + total.toLocaleString('id-ID')
        );

        $('#catatan').val(catatan);
    });

});

</script>
@endpush