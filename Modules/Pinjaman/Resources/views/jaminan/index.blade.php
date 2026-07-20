@extends('adminlte::page')

@section('title', 'Jaminan')

@section('content_header')
    <h1 class="m-0 text-dark">Jaminan</h1>
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
                    <h4 style="color: black;">Daftar jaminan</h4>
                    <div class="mt-3 mb-2">
                        <a href="{{ route('jaminan.create') }}" class="btn btn-secondary" style="border-radius: 10px;">
                            <i class="fa-solid fa-plus me-2"></i> Tambah
                        </a>
                    </div>
                    {{-- TABEL --}}
                    <div class="table-responsive mt-3">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Nama Jaminan</th>
                                    <th class="text-center">Deskripsi</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($jaminan as $item)
                                    <tr class="text-center">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->nama }}</td>
                                        <td>
                                            {{ $item->deskripsi }}
                                        </td>
                                        <td>
                                            <a href="{{ route('jaminan.edit', ['id' => $item->id]) }}" class="btn btn-warning btn-sm">
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
                                            Data jaminan belum tersedia
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

@foreach ($jaminan as $item)
<div class="modal fade"
    id="modalNonaktif{{ $item->id }}"
    tabindex="-1"
    role="dialog"
    aria-hidden="true">

    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header bg-danger">
                <h5 class="modal-title">
                    Konfirmasi nonaktif jaminan
                </h5>

                <button type="button"
                        class="close"
                        data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">
                Apakah Anda yakin ingin menonaktifkan jaminan ini?
            </div>

            <div class="modal-footer">

                <button type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal">
                    Tidak
                </button>

                <form action="{{ route('jaminan.nonaktif', $item->id) }}"
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

@foreach ($jaminan as $item)
<div class="modal fade"
    id="modalAktif{{ $item->id }}"
    tabindex="-1"
    role="dialog"
    aria-hidden="true">

    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header bg-primary">
                <h5 class="modal-title">
                    Konfirmasi aktifkan jaminan
                </h5>

                <button type="button"
                        class="close"
                        data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">
                Apakah Anda yakin ingin mengaktifkan jaminan ini?
            </div>

            <div class="modal-footer">

                <button type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal">
                    Tidak
                </button>

                <form action="{{ route('jaminan.aktif', $item->id) }}"
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

@section('css')
	 <!--some css
    <link rel="stylesheet" href="/assets/css/admin_custom.css">-->
@stop
@push('js')

@endpush