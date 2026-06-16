@extends('adminlte::page')

@section('title', 'Daftar Pinjaman')

@section('content_header')
    <h1 class="m-0 text-dark">Daftar Pinjaman</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 style="color: black;">Daftar pinjaman</h4>
                    <div class="mt-3 mb-2">
                        <form id="filterForm" action="{{ route('pinjaman.index') }}" method="GET">
                            <div class="d-flex gap-2">
                                <select name="status_pinjaman"
                                        class="form-control mx-2"
                                        style="max-width: 250px;"
                                        onchange="document.getElementById('filterForm').submit();">
                                    <option class="text-center" value="">-- Semua Status --</option>

                                    <option class="text-center" value="belum_aktif"
                                        {{ request('status_pinjaman') == 'belum_aktif' ? 'selected' : '' }}>
                                        Belum aktif
                                    </option>

                                    <option class="text-center" value="aktif"
                                        {{ request('status_pinjaman') == 'aktif' ? 'selected' : '' }}>
                                        Aktif
                                    </option>

                                    <option class="text-center" value="selesai"
                                        {{ request('status_pinjaman') == 'selesai' ? 'selected' : '' }}>
                                        Selesai
                                    </option>
                                </select>

                                {{-- FILTER SKEMA --}}
                                <select name="id_skema_pinjaman"
                                        class="form-control"
                                        style="max-width: 250px;"
                                        onchange="document.getElementById('filterForm').submit();">

                                    <option value="" class="text-center">-- Semua Skema --</option>

                                    @foreach ($skemaPinjaman as $skema)
                                        <option value="{{ $skema->id }}" class="text-center"
                                            {{ request('id_skema_pinjaman') == $skema->id ? 'selected' : '' }}>

                                            {{ $skema->nama }}

                                        </option>
                                    @endforeach

                                </select>
                            </div>
                        </form>
                    </div>
                    {{-- TABEL --}}
                    <div class="table-responsive mt-3">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Nama</th>
                                    <th class="text-center">Skema pinjaman</th>
                                    <th class="text-center">Jumlah disetujui</th>
                                    <th class="text-center">Jumlah bunga</th>
                                    <th class="text-center">Total pinjaman</th>
                                    <th class="text-center">Tanggal disetujui</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pinjaman as $item)
                                    <tr class="text-center">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->pengajuan->users->name }}</td>
                                        <td>
                                            {{ $item->pengajuan->skemaPinjaman->nama }}
                                        </td>
                                        <td>Rp. {{ number_format($item->jumlah_disetujui, 0, ',', '.') }}</td>
                                        <td>
                                            Rp. {{ number_format($item->jumlah_bunga, 0, ',', '.') }}
                                        </td>
                                        <td>
                                            Rp. {{ number_format($item->total_pinjaman, 0, ',', '.') }}
                                        </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($item->tanggal_disetujui)->format('d-m-Y') }}
                                        </td>
                                        <td>
                                            @if ($item->status_pinjaman == 'belum_aktif')
                                                <span class="badge badge-danger">
                                                    Belum Aktif
                                                </span>

                                            @elseif ($item->status_pinjaman == 'aktif')
                                                <span class="badge badge-success">
                                                    Aktif
                                                </span>

                                            @elseif ($item->status_pinjaman == 'selesai')
                                                <span class="badge badge-info">
                                                    Selesai
                                                </span>

                                            @else
                                                <span class="badge badge-secondary">
                                                    {{ $item->status_pinjaman }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-primary btn-sm" 
                                                    data-toggle="modal" 
                                                    data-target="#detailModal{{ $item->id }}">
                                                    Detail
                                            </button>
                                        </td>
                                        {{-- <td>
                                            <a href="{{ route('skemaPinjaman.edit', ['id' => $item->id]) }}" class="btn btn-warning btn-sm">
                                                <i class="fa-solid fa-pen"></i>
                                            </a>
                                            <form action="" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="btn btn-danger btn-sm">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </td> --}}
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
                    Detail Pinjaman
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
                            <td>{{ $item->pengajuan->users->name }}</td>
                        </tr>

                        <tr>
                            <th>Skema</th>
                            <td>{{ $item->pengajuan->skemaPinjaman->nama }}</td>
                        </tr>

                        <tr>
                            <th>Pengajuan</th>
                            <td>
                                Rp.
                                {{ number_format($item->pengajuan->jumlah_pengajuan, 0, ',', '.') }}
                            </td>
                        </tr>

                        <tr>
                            <th>Tenor</th>
                            <td>
                                {{ $item->pengajuan->lama_angsuran }}
                            </td>
                        </tr>

                        <tr>
                            <th>Bunga</th>
                            <td>
                                {{ $item->pengajuan->skemaPinjaman->bunga }} %
                            </td>
                        </tr>

                        <tr>
                            <th>No HP</th>
                            <td>
                                {{ $item->pengajuan->no_hp }}
                            </td>
                        </tr>

                        <tr>
                            <th>No KTP</th>
                            <td>
                                {{ $item->pengajuan->no_ktp }}
                            </td>
                        </tr>

                        <tr>
                            <th>No Rekening</th>
                            <td>
                                {{ $item->pengajuan->no_rekening }}
                            </td>
                        </tr>
                        </table>
                    </div>

                    <div class="col-md-6">
                        <table class="table table-bordered">
                        <tr>
                            <th>Alamat</th>
                            <td>
                                {{ $item->pengajuan->alamat }}
                            </td>
                        </tr>

                        <tr>
                            <th>Nama istri/suami</th>
                            <td>
                                {{ $item->pengajuan->nama_istri_suami }}
                            </td>
                        </tr>

                        <tr>
                            <th>Jumlah Disetujui</th>
                            <td>
                                Rp.
                                {{ number_format($item->jumlah_disetujui, 0, ',', '.') }}
                            </td>
                        </tr>

                        <tr>
                            <th>Jumlah Bunga</th>
                            <td>
                                Rp.
                                {{ number_format($item->jumlah_bunga, 0, ',', '.') }}
                            </td>
                        </tr>

                        <tr>
                            <th>Total Pinjaman</th>
                            <td>
                                Rp.
                                {{ number_format($item->total_pinjaman, 0, ',', '.') }}
                            </td>
                        </tr>

                        <tr>
                            <th>Tanggal pengajuan</th>
                            <td>{{ \Carbon\Carbon::parse($item->pengajuan->tanggal_pengajuan)->format('d-m-Y') }}</td>
                        </tr>
                        
                        <tr>
                            <th>Tanggal Disetujui</th>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal_disetujui)->format('d-m-Y') }}</td>
                        </tr>

                        <tr>
                            <th>Status Pinjaman</th>
                            <td>

                                @if ($item->status_pinjaman == 'belum_aktif')
                                    <span class="badge badge-danger">
                                        Belum Aktif
                                    </span>

                                @elseif ($item->status_pinjaman == 'aktif')
                                    <span class="badge badge-success">
                                        Aktif
                                    </span>

                                @elseif ($item->status_pinjaman == 'selesai')
                                    <span class="badge badge-info">
                                        Selesai
                                    </span>

                                @endif

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

@section('css')
	 <!--some css
    <link rel="stylesheet" href="/assets/css/admin_custom.css">-->
@stop
@push('js')

@endpush