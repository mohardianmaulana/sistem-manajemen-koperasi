@extends('adminlte::page')

@section('title', 'Daftar konfirmasi pencairan')

@section('content_header')
    <h1 class="m-0 text-dark">Daftar konfirmasi pencairan</h1>
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
                                    <th class="text-center">Skema pinjaman</th>
                                    <th class="text-center">Jumlah pengajuan</th>
                                    <th class="text-center">Lama angsuran</th>
                                    <th class="text-center">Tanggal pengajuan</th>
                                    <th class="text-center">Detail</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($persetujuan as $item)
                                    <tr class="text-center">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->users->name }}</td>
                                        <td>
                                            {{ $item->skemaPinjaman->nama }}
                                        </td>
                                        <td>Rp. {{ number_format($item->jumlah_pengajuan, 0, ',', '.') }}</td>
                                        <td>
                                            {{ $item->lama_angsuran }}
                                        </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->locale('id')->translatedFormat('d F Y') }}
                                        </td>
                                        <td>
                                            <button class="btn btn-primary btn-sm" 
                                                    data-toggle="modal" 
                                                    data-target="#detailModal{{ $item->id }}">
                                                    Detail
                                            </button>
                                        </td>
                                        <td>
                                            <form action="{{ route('persetujuan.pencairan', $item->id) }}"
                                                method="POST"
                                                style="display:inline;">
                                                @csrf
                                                @method('PATCH')

                                                <button type="submit" class="btn btn-success btn-sm">
                                                    Cairkan
                                                </button>
                                            </form>
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

@foreach ($persetujuan as $item)
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
                    Detail pengajuan pinjaman
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
                                {{ $item->lama_angsuran }} bulan
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
                        </table>
                    </div>

                    <div class="col-md-6">
                        <table class="table table-bordered">
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

                        <tr>
                            <th>Form pinjaman</th>
                            <td>
                                <a href="{{ route('pengajuanPinjaman.cetak', ['id' => $item->id]) }}" class="btn btn-danger btn-sm">
                                        <i class="fas fa-file-pdf"></i>
                                </a>
                            </td>
                        </tr>

                        <tr>
                            <th>Jaminan</th>
                            <td>
                                @forelse($item->skemaPinjaman->daftarJaminan as $jaminan)
                                    <div class="mb-1">
                                        <i class="fas fa-check-circle text-success mr-2"></i>
                                        {{ $jaminan->nama }}
                                    </div>
                                @empty
                                    <div class="text-muted">
                                        Tidak ada
                                    </div>
                                @endforelse
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

@endpush