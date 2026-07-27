@extends('adminlte::page')

@section('title', 'Pengajuan Pencairan SHU')

@section('content')

<div class="container-fluid">

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $item)
                    <li>{{ $item }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">

        <div class="col-md-8">

            <div class="card card-success card-outline">

                <div class="card-header">
                    <h3 class="card-title">
                        Pengajuan Pencairan SHU
                    </h3>
                </div>

                <form action="{{ route('pengajuan-pencairan.store') }}" method="POST">

                    @csrf

                    <input type="hidden"
                           name="id_shu_anggota"
                           value="{{ $summary['shu']->id }}">

                    <div class="card-body">

                        <table class="table table-bordered">

                            <tr>
                                <th width="35%">Periode SHU</th>
                                <td>
                                    {{ \Carbon\Carbon::parse($summary['shu']->periode_awal)->translatedFormat('d F Y') }}
                                    -
                                    {{ \Carbon\Carbon::parse($summary['shu']->periode_akhir)->translatedFormat('d F Y') }}
                                </td>
                            </tr>

                            <tr>
                                <th>Total SHU</th>
                                <td class="text-success font-weight-bold">
                                    Rp {{ number_format($summary['total_shu'],0,',','.') }}
                                </td>
                            </tr>

                            <tr>
                                <th>Sudah Dicairkan</th>
                                <td>
                                    Rp {{ number_format($summary['total_dicairkan'],0,',','.') }}
                                </td>
                            </tr>

                            <tr>
                                <th>Sedang Diproses</th>
                                <td>
                                    Rp {{ number_format($summary['total_diproses'],0,',','.') }}
                                </td>
                            </tr>

                            <tr class="table-success">
                                <th>Sisa SHU</th>
                                <td>
                                    <strong>
                                        Rp {{ number_format($summary['sisa_shu'],0,',','.') }}
                                    </strong>
                                </td>
                            </tr>

                        </table>

                        <hr>

                        <div class="form-group">

                            <label>Tanggal Pengajuan</label>

                            <input type="text"
                                   class="form-control"
                                   value="{{ now()->translatedFormat('d F Y') }}"
                                   readonly>

                        </div>

                        <div class="form-group">

                            <label>Nominal Pengajuan</label>

                            <input
                                type="number"
                                name="nominal_pengajuan"
                                class="form-control @error('nominal_pengajuan') is-invalid @enderror"
                                value="{{ old('nominal_pengajuan') }}"
                                min="1"
                                max="{{ $summary['sisa_shu'] }}"
                                required>

                            @error('nominal_pengajuan')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror

                            <small class="text-muted">
                                Maksimal pencairan sebesar
                                <strong>
                                    Rp {{ number_format($summary['sisa_shu'],0,',','.') }}
                                </strong>
                            </small>

                        </div>

                    </div>

                    <div class="card-footer text-right">

                        @if($summary['sisa_shu'] > 0)

                            <button type="submit"
                                    class="btn btn-success">

                                <i class="fas fa-hand-holding-usd"></i>

                                Ajukan Pencairan

                            </button>

                        @else

                            <button class="btn btn-secondary" disabled>

                                SHU Sudah Habis Dicairkan

                            </button>

                        @endif

                    </div>

                </form>

            </div>

        </div>

        <div class="col-md-4">

            <div class="card">

                <div class="card-header">

                    <h3 class="card-title">
                        Riwayat Pencairan
                    </h3>

                </div>

                <div class="card-body p-0">

                    <table class="table table-sm table-striped mb-0">

                        <thead>

                            <tr>

                                <th>Tanggal</th>
                                <th>Nominal</th>
                                <th>Status</th>

                            </tr>

                        </thead>

                        <tbody>

                        @forelse($summary['riwayat'] as $item)

                            <tr>

                                <td>

                                    {{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->translatedFormat('d/m/Y') }}

                                </td>

                                <td>

                                    Rp {{ number_format($item->nominal_pengajuan,0,',','.') }}

                                </td>

                                <td>

                                    @switch($item->status)

                                        @case('menunggu')
                                            <span class="badge badge-warning">Menunggu</span>
                                            @break

                                        @case('disetujui')
                                            <span class="badge badge-primary">Disetujui</span>
                                            @break

                                        @case('ditolak')
                                            <span class="badge badge-danger">Ditolak</span>
                                            @break

                                        @case('dicairkan')
                                            <span class="badge badge-success">Dicairkan</span>
                                            @break

                                    @endswitch

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="3" class="text-center">

                                    Belum ada riwayat pencairan.

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

@endsection