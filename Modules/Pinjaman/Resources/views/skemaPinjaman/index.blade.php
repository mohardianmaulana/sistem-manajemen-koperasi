@extends('adminlte::page')

@section('title', 'Skema Pinjaman')

@section('content_header')
    <h1 class="m-0 text-dark">Skema Pinjaman</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
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
                                            {{ $item->jaminan }}
                                        </td>
                                        <td>
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

@section('css')
	 <!--some css
    <link rel="stylesheet" href="/assets/css/admin_custom.css">-->
@stop
@push('js')

@endpush