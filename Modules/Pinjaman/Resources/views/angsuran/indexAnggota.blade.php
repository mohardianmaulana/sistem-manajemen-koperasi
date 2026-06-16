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
    <div class="row">
        <div class="col-12">
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
                                            {{ \Carbon\Carbon::parse($item->tanggal_jatuh_tempo)->format('d-m-Y') }}
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
                                                <button type="button"
                                                    class="btn btn-warning btn-sm btn-bayar"
                                                    data-toggle="modal"
                                                    data-target="#modalBayar"
                                                    data-id="{{ $item->id }}"
                                                    data-jumlah="{{ $item->jumlah_angsuran }}">
                                                    Bayar
                                                </button>
                                            @elseif ($item->status_bayar == 'lunas')
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
@stop

@foreach($angsuran as $item)
<div class="modal fade" id="modalBayar" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">

            <form action="{{ route('pembayaran.store_manual') }}"
                method="POST"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id_angsuran" id="id_angsuran">

                <div class="modal-header">
                    <h5 class="modal-title">Pembayaran Angsuran</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nominal Pembayaran</label>
                        <input type="text"
                            id="jumlah_angsuran"
                            value="Rp. {{ number_format($item->jumlah_angsuran, 0, ',', '.') }}"
                            class="form-control"
                            readonly>
                    </div>

                    <div class="form-group text-center">
                        <label>QRIS Pembayaran</label>
                        <div class="mt-2">
                            <img src="{{ asset('images/qris.png') }}"
                                alt="QRIS"
                                class="img-fluid"
                                style="max-width:250px;">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Upload Bukti Pembayaran <span class="text-danger">*</span></label>
                        <input type="file"
                            name="bukti_pembayaran"
                            class="form-control"
                            accept="image/*"
                            required>
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
@endforeach

@section('css')
	 <!--some css
    <link rel="stylesheet" href="/assets/css/admin_custom.css">-->
@stop
@push('js')

@endpush