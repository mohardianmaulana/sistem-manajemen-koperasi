@extends('adminlte::page')

@section('title', 'Skema Pinjaman')

@section('content_header')
    <h1 class="m-0 text-dark">Skema Pinjaman</h1>
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
                    <h4 style="color: black;">Daftar skema pinjaman</h4>
                    <div class="mt-3 mb-2">
                        <a href="{{ route('skemaPinjaman.create') }}" class="btn btn-secondary" style="border-radius: 10px;">
                            <i class="fa-solid fa-plus me-2"></i> Tambah
                        </a>
                    </div>
                    {{-- TABEL --}}
                    <div class="table-responsive mt-3">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Nama Skema</th>
                                    <th class="text-center">Bunga</th>
                                    <th class="text-center">Tenor</th>
                                    <th class="text-center">Nominal</th>
                                    <th class="text-center">Jaminan</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($skemaPinjaman as $item)
                                    <tr class="text-center">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->nama }}</td>
                                        <td>
                                            {{ $item->bunga }} %
                                        </td>
                                        <td>
                                            {{ $item->min_tenor }} - {{ $item->max_tenor }} Bulan
                                        </td>
                                        <td>
                                            Rp {{ number_format($item->min_nominal, 0, ',', '.') }} - {{ number_format($item->max_nominal, 0, ',', '.') }}
                                        </td>
                                        <td>
                                            @if($item->jaminan == 'ada')
                                                <span class="badge badge-success">
                                                    {{ $item->daftarJaminan->count() }} Jaminan
                                                </span>

                                                <button
                                                    class="btn btn-info btn-sm"
                                                    data-toggle="modal"
                                                    data-target="#modalJaminan{{ $item->id }}">
                                                    <i class="fa-solid fa-circle-info"></i>
                                                </button>
                                            @else
                                                <span class="badge badge-secondary">Tidak</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('skemaPinjaman.edit', ['id' => $item->id]) }}" class="btn btn-warning btn-sm">
                                                <i class="fa-solid fa-pen"></i>
                                            </a>
                                            @if($item->status == 'aktif')
                                            <button type="button"
                                                    class="btn btn-danger btn-sm"
                                                    data-toggle="modal"
                                                    data-target="#modalNonaktif{{ $item->id }}">
                                                <i class="fa-solid fa-xmark"></i>
                                            </button>
                                            @elseif($item->status == 'nonaktif')
                                            <button type="button"
                                                    class="btn btn-primary btn-sm"
                                                    data-toggle="modal"
                                                    data-target="#modalAktif{{ $item->id }}">
                                                <i class="fa-solid fa-xmark"></i>
                                            </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            Data skema pinjaman belum tersedia
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

@foreach ($skemaPinjaman as $item)
<div class="modal fade"
    id="modalNonaktif{{ $item->id }}"
    tabindex="-1"
    role="dialog"
    aria-hidden="true">

    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header bg-danger">
                <h5 class="modal-title">
                    Konfirmasi nonaktif skema
                </h5>

                <button type="button"
                        class="close"
                        data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">
                Apakah Anda yakin ingin menonaktifkan skema pinjaman ini?
            </div>

            <div class="modal-footer">

                <button type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal">
                    Tidak
                </button>

                <form action="{{ route('skemaPinjaman.nonaktif', $item->id) }}"
                        method="POST">

                    @csrf
                    @method('PATCH')

                    <button type="submit"
                            class="btn btn-danger">
                        Ya, Nonaktif
                    </button>

                </form>

            </div>

        </div>
    </div>

</div>
@endforeach

@foreach ($skemaPinjaman as $item)
<div class="modal fade"
    id="modalAktif{{ $item->id }}"
    tabindex="-1"
    role="dialog"
    aria-hidden="true">

    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header bg-primary">
                <h5 class="modal-title">
                    Konfirmasi aktifkan skema
                </h5>

                <button type="button"
                        class="close"
                        data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">
                Apakah Anda yakin ingin mengaktifkan skema pinjaman ini?
            </div>

            <div class="modal-footer">

                <button type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal">
                    Tidak
                </button>

                <form action="{{ route('skemaPinjaman.aktif', $item->id) }}"
                        method="POST">

                    @csrf
                    @method('PATCH')

                    <button type="submit"
                            class="btn btn-primary">
                        Ya, Aktif
                    </button>

                </form>

            </div>

        </div>
    </div>

</div>
@endforeach

@foreach($skemaPinjaman as $item)
<div class="modal fade"
     id="modalJaminan{{ $item->id }}"
     tabindex="-1">

    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    Daftar Jaminan
                </h5>

                <button class="close"
                        data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <h6>{{ $item->nama }}</h6>

                <hr>

                @foreach($item->daftarJaminan as $jaminan)

                    <div class="text-center">
                        ✔ {{ $jaminan->nama }}
                    </div>

                @endforeach

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