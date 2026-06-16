@extends('adminlte::page')

@section('title', 'Daftar angsuran pinjaman')

@section('content_header')
    <h1 class="m-0 text-dark">Angsuran pinjaman</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <a href="{{ route('angsuran.index') }}" class="btn btn-secondary" style="border-radius: 10px;">
                            <i class="fa-solid fa-backward me-2"></i> Kembali
                        </a>
                    </div>
                    {{-- TABEL --}}
                    <div class="table-responsive mt-2">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Nama</th>
                                    <th class="text-center">Angsuran ke</th>
                                    <th class="text-center">Jumlah angsuran</th>
                                    <th class="text-center">Tanggal jatuh tempo</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Bukti pembayaran</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($angsuran as $item)
                                    <tr class="text-center">
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ $item->pinjaman->pengajuan->users->name }}</td>
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
                                            @if ($item->pembayaran && $item->pembayaran->bukti_pembayaran)
                                                <img src="{{ asset('storage/' . $item->pembayaran->bukti_pembayaran) }}"
                                                    width="100"
                                                    alt="Bukti Pembayaran">
                                            @else
                                                <span class="text-muted">Belum ada bukti</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($item->pembayaran && $item->pembayaran->bukti_pembayaran)
                                            <button class="btn btn-danger btn-sm"
                                                    data-toggle="modal"
                                                    data-target="#modalGagal{{ $item->id }}">
                                                Gagal
                                            </button>
                                            <form action="{{ route('pembayaran.verifikasi', $item->id) }}"
                                                method="POST"
                                                style="display:inline;">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-success btn-sm">
                                                    Sukses
                                                </button>
                                            </form>
                                            @else
                                                <button type="submit" class="btn btn-danger btn-sm" disabled>
                                                    Gagal
                                                </button>
                                                <button type="submit" class="btn btn-success btn-sm" disabled>
                                                    Sukses
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
<div class="modal fade"
     id="modalGagal{{ $item->id }}"
     tabindex="-1"
     role="dialog">

    <div class="modal-dialog">
        <div class="modal-content">

            <form action="{{ route('pembayaran.gagalVerifikasi', $item->id) }}"
                method="POST">

                @csrf
                @method('PATCH')

                <div class="modal-header bg-danger">
                    <h5 class="modal-title">
                        Gagal verifikasi
                    </h5>

                    <button type="button"
                            class="close"
                            data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="form-group">
                        <label>Alasan kegagalan</label>

                        <textarea name="catatan"
                                class="form-control"
                                rows="4"
                                required
                                placeholder="Masukkan alasan kegagalan"></textarea>
                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-secondary"
                            data-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit"
                            class="btn btn-danger">
                        Gagal
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