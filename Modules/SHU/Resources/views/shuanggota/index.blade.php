@extends('adminlte::page')

@section('title', 'Perhitungan SHU')

@section('content_header')

<h1 class="m-0 text-dark">
    Perhitungan SHU Anggota
</h1>

@stop

@section('content')

@if(session('success'))

    <div class="alert alert-success alert-dismissible fade show">

        <i class="fas fa-check-circle"></i>

        {{ session('success') }}

        <button
            type="button"
            class="close"
            data-dismiss="alert"
            aria-label="Close">

            <span aria-hidden="true">&times;</span>

        </button>

    </div>

@endif

@if(session('error'))

    <div class="alert alert-danger alert-dismissible fade show">

        <i class="fas fa-exclamation-circle"></i>

        {{ session('error') }}

        <button
            type="button"
            class="close"
            data-dismiss="alert"
            aria-label="Close">

            <span aria-hidden="true">&times;</span>

        </button>

    </div>

@endif

<div class="card">

    {{-- Hanya Admin yang dapat melakukan Regenerate SHU --}}
    @role('admin')

    <div class="card-header">

        <form action="{{ route('shu.store') }}"
              method="POST">

            @csrf

            <div class="row">

                <div class="col-md-3">

                    <label>Tahun</label>

                    <input
                        type="number"
                        name="tahun"
                        class="form-control @error('tahun') is-invalid @enderror"
                        value="{{ old('tahun', date('Y')) }}">

                    @error('tahun')

                        <small class="text-danger">

                            {{ $message }}

                        </small>

                    @enderror

                </div>

                <div class="col-md-3 mt-4">

                    <button
                        type="submit"
                        class="btn btn-primary">

                        <i class="fas fa-sync"></i>

                        Regenerate SHU

                    </button>

                </div>

            </div>

        </form>

    </div>

    @endrole

    <div class="card-body">

        <table class="table table-bordered table-striped">

            <thead class="text-center">

                <tr>

                    <th>No</th>
                    <th>Tahun</th>
                    <th>Nama Anggota</th>
                    <th>SHU Simpanan</th>
                    <th>SHU Pinjaman</th>
                    <th>Total SHU</th>

                </tr>

            </thead>

            <tbody>

            @forelse($data as $item)

                <tr>

                    <td class="text-center">
                        {{ $loop->iteration }}
                    </td>

                    <td class="text-center">
                        {{ $item->tahun }}
                    </td>

                    <td>
                        {{ $item->anggota->username }}
                    </td>

                    <td class="text-right">
                        Rp {{ number_format($item->shu_simpanan, 0, ',', '.') }}
                    </td>

                    <td class="text-right">
                        Rp {{ number_format($item->shu_pinjaman, 0, ',', '.') }}
                    </td>

                    <td class="text-right">

                        <strong>

                            Rp {{ number_format($item->shu_anggota, 0, ',', '.') }}

                        </strong>

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="6" class="text-center">

                        Belum ada data SHU.

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

@stop