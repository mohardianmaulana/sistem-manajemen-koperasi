@extends('adminlte::page')

@section('title', 'Data Simpanan Sukarela')

@section('content_header')
    <h1 class="m-0 text-dark">Data Simpanan Sukarela</h1>
@stop

@section('content')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert">
                &times;
            </button>

            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert">
                &times;
            </button>

            {{ session('error') }}
        </div>
    @endif
<div class="card">

    <div class="card-header">

    {{-- Tombol tambah simpanan untuk anggota --}}
    @role('anggota')

        <a href="{{ route('simpanan-sukarela.create') }}"
           class="btn btn-primary"
           style="border-radius:10px">

            <i class="fas fa-plus"></i>
            Tambah Simpanan

        </a>

    @endrole

    {{-- Tombol export auto debit untuk admin --}}
    @role('admin')

        <a href="{{ route('simpanan-sukarela.export-auto-debit') }}"
           class="btn btn-success"
           style="border-radius:10px">

            <i class="fas fa-file-excel"></i>
            Export Auto Debit

        </a>

    @endrole

</div>

    <div class="card-body">

        <table class="table table-bordered table-striped">

            <thead class="text-center">
                <tr>
                    <th width="5%">No</th>
                    <th>Nama Anggota</th>
                    <th>Nominal</th>
                    <th>Periode</th>
                    <th>Tahun</th>
                    <th>Bukti</th>
                    <th>Status</th>
                    <th width="15%">Aksi</th>
                </tr>
            </thead>

            <tbody>

                @forelse($data as $item)

                    <tr>

                        <td class="text-center">
                            {{ $loop->iteration }}
                        </td>

                        <td>
                            {{ $item->user->name?? '-' }}
                        </td>

                        <td>
                            Rp {{ number_format($item->nilai, 0, ',', '.') }}
                        </td>

                        <td>
                            {{ \Carbon\Carbon::parse($item->periode)->format('d-m-Y') }}
                        </td>

                        <td class="text-center">
                            {{ $item->tahun }}
                        </td>

                        <td class="text-center">
                            <a href="{{ asset('storage/' . $item->bukti) }}" target="_blank" class="btn btn-info btn-sm">
                                <i class="fas fa-file-pdf"></i>
                            </a>
                        </td>

                        <td class="text-center">

                            @if($item->status == 'pending')

                                <span class="badge badge-warning">
                                    Pending
                                </span>

                            @elseif($item->status == 'selesai')

                                <span class="badge badge-success">
                                    Selesai
                                </span>

                            @else

                                <span class="badge badge-danger">
                                    Tidak Berhasil
                                </span>

                            @endif

                        </td>

                        <td class="text-center">

                            <a href="{{ route('simpanan-sukarela.show', $item->id) }}"
                               class="btn btn-primary btn-sm">

                                <i class="fas fa-edit"></i>

                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="7" class="text-center">
                            Belum ada data simpanan sukarela.
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