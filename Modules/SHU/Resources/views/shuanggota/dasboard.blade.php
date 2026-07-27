@extends('adminlte::page')

@section('title', 'Data SHU Anggota')

@section('content_header')

<div class="d-flex justify-content-between align-items-center">

    <div>

        <h1 class="m-0 text-dark">
            Data SHU Anggota
        </h1>

        <small class="text-muted">
            Pengelolaan pembagian SHU anggota koperasi
        </small>

    </div>

    <div>

        <a href="{{ route('shu.generate') }}"
           class="btn btn-success">

            <i class="fas fa-calculator"></i>

            Generate SHU

        </a>

    </div>

</div>

@stop

@section('content')

@if(session('success'))

<div class="alert alert-success">

    {{ session('success') }}

</div>

@endif

<div class="card">

    <div class="card-header">

        <form method="GET">

            <div class="row">

                <div class="col-md-3">

                    <select
                        name="tahun"
                        class="form-control">

                        <option value="">
                            Semua Tahun
                        </option>

                    </select>

                </div>

                <div class="col-md-4">

                    <input
                        type="text"
                        class="form-control"
                        placeholder="Cari Nama Anggota"
                        name="search">

                </div>

                <div class="col-md-2">

                    <button class="btn btn-primary">

                        Filter

                    </button>

                </div>

            </div>

        </form>

    </div>

    <div class="card-body table-responsive">

        <table class="table table-bordered table-hover">

            <thead class="text-center">

            <tr>

                <th>No</th>

                <th>Nama Anggota</th>

                <th>Periode</th>

                <th>SHU Simpanan</th>

                <th>SHU Pinjaman</th>

                <th>Pajak</th>

                <th>Total SHU</th>

            </tr>

            </thead>

            <tbody>

            @forelse($data as $item)

            <tr>

                <td>

                    {{ $loop->iteration }}

                </td>

                <td>

                    {{ $item->user->name }}

                </td>

                <td>

                    {{ $item->tahun }}

                </td>

                <td>

                    Rp {{ number_format($item->shu_simpanan,0,',','.') }}

                </td>

                <td>

                    Rp {{ number_format($item->shu_pinjaman,0,',','.') }}

                </td>

                <td>

                    Rp {{ number_format($item->pajak,0,',','.') }}

                </td>

                <td>

                    <strong>

                        Rp {{ number_format($item->shu_anggota,0,',','.') }}

                    </strong>

                </td>

            </tr>

            @empty

            <tr>

                <td
                    colspan="8"
                    class="text-center">

                    Tidak ada data.

                </td>

            </tr>

            @endforelse

            </tbody>

        </table>

    </div>

    <div class="card-footer">

        {{ $data->links() }}

    </div>

</div>

@stop