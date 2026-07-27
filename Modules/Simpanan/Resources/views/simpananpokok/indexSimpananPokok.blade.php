@extends('adminlte::page')

@section('title', 'Data Simpanan Pokok')

@section('content_header')
<h1 class="m-0 text-dark">
    Data Simpanan Pokok
</h1>
@stop

@section('content')

<div class="row">

    <div class="col-md-4">
        <div class="small-box bg-info">

            <div class="inner">
                <h3>
                    Rp {{ number_format($summary['totalNominal'],0,',','.') }}
                </h3>
                <p>Total Simpanan Pokok</p>
            </div>

            <div class="icon">
                <i class="fas fa-wallet"></i>
            </div>

        </div>
    </div>

    <div class="col-md-4">
        <div class="small-box bg-warning">

            <div class="inner">
                <h3>{{ $summary['pending'] }}</h3>
                <p>Menunggu Verifikasi</p>
            </div>

            <div class="icon">
                <i class="fas fa-clock"></i>
            </div>

        </div>
    </div>

    <div class="col-md-4">
        <div class="small-box bg-success">

            <div class="inner">
                <h3>{{ $summary['selesai'] }}</h3>
                <p>Simpanan Disetujui</p>
            </div>

            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>

        </div>
    </div>

</div>

<div class="card">

    <div class="card-header">

        <div class="row">

            <div class="col-md-6">

                @role('admin')
                <a href="{{ route('simpanan-pokok.create') }}"
                   class="btn btn-primary">

                    <i class="fas fa-plus"></i>
                    Tambah Simpanan

                </a>
                @endrole

            </div>

            <div class="col-md-6">

                <form method="GET">

                    <div class="row">

                        <div class="col-md-4">

                            <select name="bulan" class="form-control">

                                <option value="">Semua Bulan</option>

                                @for($i=1;$i<=12;$i++)

                                    <option value="{{ $i }}"
                                        {{ request('bulan')==$i?'selected':'' }}>

                                        {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}

                                    </option>

                                @endfor

                            </select>

                        </div>

                        <div class="col-md-4">

                            <select name="tahun" class="form-control">

                                <option value="">Semua Tahun</option>

                                @for($i=date('Y');$i>=2024;$i--)

                                    <option value="{{ $i }}"
                                        {{ request('tahun')==$i?'selected':'' }}>

                                        {{ $i }}

                                    </option>

                                @endfor

                            </select>

                        </div>

                        <div class="col-md-2">

                            <button class="btn btn-primary btn-block">

                                <i class="fas fa-search"></i>

                            </button>

                        </div>

                        <div class="col-md-2">

                            <a href="{{ route('simpanan-pokok.index') }}"
                               class="btn btn-secondary btn-block">

                                <i class="fas fa-sync"></i>

                            </a>

                        </div>

                    </div>

                </form>

            </div>

        </div>

    </div>

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-bordered table-hover">

                <thead class="text-center">

                    <tr>

                        <th width="5%">No</th>
                        <th>Nama Anggota</th>
                        <th>Nominal</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Bukti</th>
                        <th width="18%">Aksi</th>

                    </tr>

                </thead>

                <tbody>

                @forelse($simpanan as $item)

                    <tr>

                        <td class="text-center">
                            {{ $loop->iteration + ($simpanan->currentPage()-1) * $simpanan->perPage() }}
                        </td>

                        <td>

                            {{ $item->user->name }}

                        </td>

                        <td>

                            Rp {{ number_format($item->nilai,0,',','.') }}

                        </td>

                        <td>

                            {{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}

                        </td>

                        <td class="text-center">

                            @if($item->status=='pending')

                                <span class="badge badge-warning">
                                    <i class="fas fa-clock"></i>
                                    Pending
                                </span>

                            @elseif($item->status=='selesai')

                                <span class="badge badge-success">
                                    <i class="fas fa-check-circle"></i>
                                    Selesai
                                </span>

                            @else

                                <span class="badge badge-danger">
                                    <i class="fas fa-times-circle"></i>
                                    Ditolak
                                </span>

                            @endif

                        </td>

                        <td class="text-center">

                            @if($item->bukti)

                                <a href="{{ asset('storage/'.$item->bukti) }}"
                                   target="_blank"
                                   class="btn btn-info btn-sm">

                                    <i class="fas fa-image"></i>

                                </a>

                            @else

                                <span class="badge badge-secondary">

                                    Belum Upload

                                </span>

                            @endif

                        </td>

                        <td class="text-center">

                            @role('admin')

                                <a href="{{ route('simpanan-pokok.show',$item->id) }}"
                                   class="btn btn-success btn-sm"
                                   title="Verifikasi Simpanan">

                                    <i class="fas fa-check-circle"></i>
                                    Verifikasi

                                </a>

                            @else

                                @if($item->status == 'pending' || $item->status == 'ditolak')

                                    <a href="{{ route('simpanan-pokok.show',$item->id) }}"
                                       class="btn btn-primary btn-sm"
                                       title="Upload Bukti Transfer">

                                        <i class="fas fa-upload"></i>
                                        Upload Bukti

                                    </a>

                                @else

                                    <button class="btn btn-secondary btn-sm" disabled>

                                        <i class="fas fa-check"></i>
                                        Selesai

                                    </button>

                                @endif

                            @endrole

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="7" class="text-center text-muted">

                            Belum ada data simpanan pokok.

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

        <div class="mt-3 d-flex justify-content-center">

            {{ $simpanan->links() }}

        </div>

    </div>

</div>

@stop