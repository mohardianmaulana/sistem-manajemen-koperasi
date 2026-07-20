@extends('adminlte::page')

@section('title', 'Verifikasi bukti pembayaran')

@section('content_header')
    <h1 class="m-0 text-dark">Verifikasi bukti pembayaran</h1>
@stop

@section('content')
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
                    {{-- TABEL --}}
                    <div class="table-responsive mt-3">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Nama</th>
                                    <th class="text-center">Angsuran ke</th>
                                    <th class="text-center">Nominal pembayaran</th>
                                    <th class="text-center">Tanggal jatuh tempo</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Bukti pembayaran</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pembayaran as $item)
                                    <tr class="text-center">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->angsuran->pinjaman->pengajuan->users->name }}</td>
                                        <td>
                                            {{ $item->angsuran->angsuran_ke }}
                                        </td>
                                        <td>Rp. {{ number_format($item->jumlah_bayar, 0, ',', '.') }}</td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($item->tanggal_jatuh_tempo)->locale('id')->translatedFormat('d F Y') }}
                                        </td>
                                        <td>
                                            @if ($item->angsuran->status_bayar == 'belum_bayar')
                                                <span class="badge badge-info">
                                                    Belum bayar
                                                </span>
                                            @elseif ($item->angsuran->status_bayar == 'lunas')
                                                <span class="badge badge-success">
                                                    Lunas
                                                </span>
                                            @elseif ($item->angsuran->status_bayar == 'gagal_debet')
                                                <span class="badge badge-danger">
                                                    Gagal debet
                                                </span>
                                            @elseif ($item->angsuran->status_bayar == 'verifikasi')
                                                <span class="badge badge-secondary">
                                                    Verifikasi
                                                </span>
                                            @elseif ($item->angsuran->status_bayar == 'gagal_verifikasi')
                                                <span class="badge badge-danger">
                                                    {{ $item->status_pinjaman }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <button type="button"
                                                class="btn btn-info btn-sm btn-detail"
                                                data-toggle="modal"
                                                data-target="#modalBukti"
                                                data-image="{{ asset('bukti_pembayaran/' . $item->bukti_pembayaran) }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <button type="button"
                                                class="btn btn-danger btn-sm btn-gagal"
                                                data-toggle="modal"
                                                data-target="#modalGagal"
                                                data-id="{{ $item->id }}">
                                                <i class="fa-solid fa-xmark"></i>
                                            </button>
                                            <form action="{{ route('pembayaran.verifikasi', ['id' => $item->id]) }}"
                                                method="POST"
                                                style="display:inline;">
                                                @csrf
                                                @method('PATCH')

                                                <button type="submit" class="btn btn-success btn-sm">
                                                    <i class="fa-solid fa-check"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            Data verifikasi pembayaran belum tersedia
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
                    Bukti Pembayaran
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

<div class="modal fade" id="modalGagal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">

            <form id="formGagalVerifikasi" method="POST">
                @csrf
                @method('PATCH')

                <div class="modal-header">
                    <h5 class="modal-title">
                        Gagal Verifikasi Pembayaran
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
                        <span class="text-danger">*</span>

                        <textarea
                            name="catatan"
                            class="form-control"
                            rows="4"
                            placeholder="Masukkan alasan mengapa pembayaran ditolak..."
                            required></textarea>
                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal">
                        Tutup
                    </button>

                    <button type="submit"
                        class="btn btn-danger">
                        Gagal Verifikasi
                    </button>

                </div>

            </form>

        </div>
    </div>
</div>

@section('css')
	 <!--some css
    <link rel="stylesheet" href="/assets/css/admin_custom.css">-->
@stop
@push('js')
<script>
$(document).ready(function () {

    $('.btn-detail').click(function () {
        let image = $(this).data('image');

        $('#previewBukti').attr('src', image);
    });

    $('.btn-gagal').click(function () {
        let id = $(this).data('id');

        let url = "{{ route('pembayaran.gagalVerifikasi', ':id') }}";
        url = url.replace(':id', id);

        $('#formGagalVerifikasi').attr('action', url);
    });

});
</script>
@endpush