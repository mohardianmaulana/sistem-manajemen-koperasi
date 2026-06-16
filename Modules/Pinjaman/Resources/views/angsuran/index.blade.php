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
                        <a href="{{ route('angsuran.indexVerifikasi') }}" class="btn btn-secondary" style="border-radius: 10px;">
                            <i class="fa-solid fa-file-circle-check"></i> Verifikasi
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
                                            <form action="{{ route('angsuran.gagal_debet', $item->id) }}"
                                                method="POST"
                                                style="display:inline;">
                                                @csrf
                                                @method('PATCH')

                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    Gagal debet
                                                </button>
                                            </form>
                                            <a href="{{ route('pembayaran.store_auto_debet', ['id' => $item->id]) }}"
                                            class="btn btn-success btn-sm">
                                                Auto debet
                                            </a>
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

@section('css')
	 <!--some css
    <link rel="stylesheet" href="/assets/css/admin_custom.css">-->
@stop
@push('js')

@endpush