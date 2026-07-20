@extends('adminlte::page')

@section('title', 'Data SHU Koperasi')

@section('content_header')
    <h1 class="m-0 text-dark">Data SHU Koperasi</h1>
@stop

@section('content')

<div class="row">
    <div class="col-12">

            @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif

    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif

        <div class="mb-3">
            <a href="{{ route('shu-koperasi.create') }}"
                class="btn btn-primary"
                style="border-radius:10px">

                <i class="fas fa-plus"></i>
                Tambah SHU

            </a>
        </div>

        <div class="card">

            <div class="card-header">
                <h3 class="card-title">
                    Daftar SHU Koperasi
                </h3>
            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered table-hover">

                        <thead class="text-center">

                            <tr>
                                <th>No</th>
                                <th>Periode Awal</th>
                                <th>Periode Akhir</th>
                                <th>Jasa Simpanan</th>
                                <th>Jasa Pinjaman</th>
                                <th>Dana Cadangan</th>
                                <th>Jasa Pengurus</th>
                                <th>Dana Sosial</th>
                                <th>Total SHU</th>
                                <th width="120">Aksi</th>
                            </tr>

                        </thead>

                        <tbody>

                        @forelse($data as $item)

                            <tr>

                                <td class="text-center">
                                    {{ $loop->iteration }}
                                </td>

                                <td class="text-center">
                                    {{ $item->periode_awal }}
                                </td>

                                 <td class="text-center">
                                    {{ $item->periode_akhir }}
                                </td>

                                <td>
                                    Rp {{ number_format($item->jasa_simpanan,0,',','.') }}
                                </td>

                                <td>
                                    Rp {{ number_format($item->jasa_pinjaman,0,',','.') }}
                                </td>

                                <td>
                                    Rp {{ number_format($item->dana_cadangan,0,',','.') }}
                                </td>

                                <td>
                                    Rp {{ number_format($item->jasa_pengurus,0,',','.') }}
                                </td>

                                <td>
                                    Rp {{ number_format($item->dana_sosial,0,',','.') }}
                                </td>

                                <td>
                                    <strong>
                                        Rp {{ number_format($item->total_shu,0,',','.') }}
                                    </strong>
                                </td>

                                <td class="text-center">

                                    <a href="{{ route('shu-koperasi.show',$item->id) }}"
                                        class="btn btn-warning btn-sm">

                                        <i class="fas fa-edit"></i>

                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="9" class="text-center">
                                    Data SHU belum tersedia.
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