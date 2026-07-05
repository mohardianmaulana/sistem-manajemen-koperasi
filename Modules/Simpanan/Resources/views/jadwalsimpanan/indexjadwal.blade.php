@extends('adminlte::page')

@section('title', 'Master Jenis Simpanan')

@section('content_header')
<h1 class="m-0 text-dark">Master Jenis Simpanan</h1>
@stop

@section('content')

<div class="card">

    <div class="card-header">

        <a href="{{ route('master-jenis-simpanan.create') }}"
            class="btn btn-primary"
            style="border-radius:10px">

            <i class="fas fa-plus"></i>
            Tambah Jenis Simpanan

        </a>

    </div>

    <div class="card-body">

        <table class="table table-bordered table-striped">

            <thead class="text-center">

                <tr>
                    <th width="5%">No</th>
                    <th>Jenis Simpanan</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Berakhir</th>
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
                            {{ $item->nama_jenis_simpanan }}
                        </td>

                        <td>
                            {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d-m-Y H:i') }}
                        </td>

                        <td>
                            {{ \Carbon\Carbon::parse($item->tanggal_berakhir)->format('d-m-Y H:i') }}
                        </td>

                        @php
                            $now = \Carbon\Carbon::now();
                            $status = $now->between(
                                \Carbon\Carbon::parse($item->tanggal_mulai),
                                \Carbon\Carbon::parse($item->tanggal_berakhir)
                            ) ? 'Aktif' : 'Tidak Aktif';
                        @endphp

                        <td class="text-center">

                            @if($status == 'Aktif')
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-danger">Tidak Aktif</span>
                            @endif

                        </td>

                        <td class="text-center">

                            <a href="{{ route('master-jenis-simpanan.show', $item->id) }}"
                                class="btn btn-warning btn-sm">

                                <i class="fas fa-edit"></i>

                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6" class="text-center">

                            Belum ada data jenis simpanan.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@stop