@extends('adminlte::page')

@section('title', 'Data Simpanan Pokok')

@section('content_header')
<h1 class="m-0 text-dark">Data Simpanan Pokok</h1>
@stop

@section('content')

<div class="card">
    <div class="card-header">

        <a href="{{ route('simpanan-pokok.create') }}"
            class="btn btn-primary"
            style="border-radius:10px">

            <i class="fas fa-plus"></i>
            Tambah Simpanan

        </a>

    </div>

    <div class="card-body">

        <table class="table table-bordered table-striped">
            <thead class="text-center">
                <tr>
                    <th width="5%">No</th>
                    <th>Nama Anggota</th>
                    <th>Nominal</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th width="15%">Aksi</th>
                </tr>
            </thead>

            <tbody>

                @forelse($simpanan as $item)

                    <tr>

                        <td class="text-center">
                            {{ $loop->iteration }}
                        </td>

                        <td>
                            {{ $item->anggota->nama }}
                        </td>

                        <td>
                            Rp {{ number_format($item->nilai,0,',','.') }}
                        </td>

                        <td>
                            {{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}
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

                            <a href="{{ route('simpanan-pokok.show',$item->id) }}"
                                class="btn btn-warning btn-sm">

                                <i class="fas fa-edit"></i>

                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6" class="text-center">
                            Belum ada data simpanan.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@stop