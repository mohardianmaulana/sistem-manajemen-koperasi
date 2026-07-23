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
    <div class="card-header">
   @role('admin')
        <a href="{{ route('shu.create') }}"
           class="btn btn-primary"
           style="border-radius:10px">

            <i class="fas fa-plus"></i>
            Tambah Simpanan

        </a>
    @endrole
    </div>

    <div class="card-body">

        <table class="table table-bordered table-striped">

            <thead class="text-center">

                <tr>

                    <th>No</th>
                    <th>Awal Periode</th>
                    <th>Akhir Periode</th>
                    <th>Nama Anggota</th>

                    <th>SHU Simpanan</th>
                    <th>SHU Pinjaman</th>
                    <th>Pajak</th>
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
                        {{ $item->periode_awal }}
                    </td>

                    <td class="text-center">
                        {{ $item->periode_akhir }}
                    </td>

                    <td>
                        {{ $item->user->name }}
                    </td>

                    <td class="text-right">
                        Rp {{ number_format($item->shu_simpanan, 0, ',', '.') }}
                    </td>

                    <td class="text-right">
                        Rp {{ number_format($item->shu_pinjaman, 0, ',', '.') }}
                    </td>

                    <td class="text-right">
                        Rp {{ number_format($item->pajak, 0, ',', '.') }}
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

        <div class="mt-3">
            {{ $data->links() }}
        </div>
    </div>

</div>

@stop