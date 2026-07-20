@extends('adminlte::page')

@section('title', 'Daftar Pinjaman')

@section('content_header')
    <h1 class="m-0 text-dark">Daftar Pinjaman</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="mt-3 mb-2">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <h3>{{ $dashboard['totalAktif'] }}</h3>
                                        <p>Total Pinjaman Aktif</p>
                                    </div>

                                    <div class="icon">
                                        <i class="fas fa-money-check"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="small-box bg-info">
                                    <div class="inner">
                                        <h3>
                                            Rp {{ number_format($dashboard['totalNominal'],0,',','.') }}
                                        </h3>
                                        <p>Total Nilai Pinjaman</p>
                                    </div>

                                    <div class="icon">
                                        <i class="fas fa-wallet"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="small-box bg-warning">
                                    <div class="inner">
                                        <h3>{{ $dashboard['jatuhTempo'] }}</h3>
                                        <p>Jatuh Tempo Bulan Ini</p>
                                    </div>

                                    <div class="icon">
                                        <i class="fas fa-calendar"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="small-box bg-danger">
                                    <div class="inner">
                                        <h3>{{ $dashboard['gagalDebet'] }}</h3>
                                        <p>Gagal Debet</p>
                                    </div>

                                    <div class="icon">
                                        <i class="fas fa-exclamation-circle"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <form id="filterForm" action="{{ route('pinjaman.index') }}" method="GET">
                            <div class="d-flex gap-2">
                                <select name="status_pinjaman"
                                        class="form-control mx-2"
                                        style="max-width: 250px;"
                                        onchange="document.getElementById('filterForm').submit();">
                                    <option class="text-center" value="">-- Semua Status --</option>

                                    <option class="text-center" value="belum_aktif"
                                        {{ request('status_pinjaman') == 'belum_aktif' ? 'selected' : '' }}>
                                        Belum aktif
                                    </option>

                                    <option class="text-center" value="aktif"
                                        {{ request('status_pinjaman') == 'aktif' ? 'selected' : '' }}>
                                        Aktif
                                    </option>

                                    <option class="text-center" value="selesai"
                                        {{ request('status_pinjaman') == 'selesai' ? 'selected' : '' }}>
                                        Selesai
                                    </option>
                                </select>

                                {{-- FILTER SKEMA --}}
                                <select name="id_skema_pinjaman"
                                        class="form-control"
                                        style="max-width: 250px;"
                                        onchange="document.getElementById('filterForm').submit();">

                                    <option value="" class="text-center">-- Semua Skema --</option>

                                    @foreach ($skemaPinjaman as $skema)
                                        <option value="{{ $skema->id }}" class="text-center"
                                            {{ request('id_skema_pinjaman') == $skema->id ? 'selected' : '' }}>

                                            {{ $skema->nama }}

                                        </option>
                                    @endforeach

                                </select>
                            </div>
                        </form>
                    </div>
                    {{-- TABEL --}}
                    <div class="table-responsive mt-3">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Nama</th>
                                    <th class="text-center">Skema pinjaman</th>
                                    <th class="text-center">Total pinjaman</th>
                                    <th class="text-center">Progress</th>
                                    <th class="text-center">Sisa pinjaman</th>
                                    <th class="text-center">Jatuh tempo berikutnya</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pinjaman as $item)
                                    <tr class="text-center">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->pengajuan->users->name }}</td>
                                        <td>
                                            {{ $item->pengajuan->skemaPinjaman->nama }}
                                        </td>
                                        <td>Rp. {{ number_format($item->jumlah_disetujui, 0, ',', '.') }}</td>
                                        <td>
                                            @php
                                            $totalAngsuran = $item->angsuran->count();

                                            $lunas = $item->angsuran
                                                        ->where('status_bayar','lunas')
                                                        ->count();

                                            $belum = $item->angsuran
                                                        ->where('status_bayar','!=','lunas')
                                                        ->count();

                                            $progress = $totalAngsuran == 0
                                                ? 0
                                                : round(($lunas/$totalAngsuran)*100);
                                            @endphp
                                            {{ $lunas }} / {{ $totalAngsuran }}

                                            <div class="progress mt-1">
                                                <div class="progress-bar bg-success"
                                                    role="progressbar"
                                                    style="width: {{ $progress }}%">

                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                            $sudahBayar = $item->angsuran
                                                ->where('status_bayar','lunas')
                                                ->sum('jumlah_angsuran');

                                            $sisa = $item->total_pinjaman - $sudahBayar;
                                            @endphp
                                            Rp {{ number_format($sisa,0,',','.') }}
                                        </td>
                                        <td>
                                            @php
                                            $jatuhTempo = $item->angsuran
                                                    ->where('status_bayar','!=','lunas')
                                                    ->sortBy('tanggal_jatuh_tempo')
                                                    ->first();
                                            @endphp
                                            @if($jatuhTempo)
                                            {{ \Carbon\Carbon::parse(
                                                $jatuhTempo->tanggal_jatuh_tempo)->locale('id')->translatedFormat('d F Y') }}
                                            @else
                                            -
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item->status_pinjaman == 'belum_aktif')
                                                <span class="badge badge-danger">
                                                    Belum Aktif
                                                </span>

                                            @elseif ($item->status_pinjaman == 'aktif')
                                                <span class="badge badge-success">
                                                    Aktif
                                                </span>

                                            @elseif ($item->status_pinjaman == 'selesai')
                                                <span class="badge badge-info">
                                                    Selesai
                                                </span>

                                            @else
                                                <span class="badge badge-secondary">
                                                    {{ $item->status_pinjaman }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-primary btn-sm" 
                                                    data-toggle="modal" 
                                                    data-target="#detailModal{{ $item->id }}">
                                                    Detail
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            Data pinjaman belum tersedia
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

@foreach ($pinjaman as $item)
<div class="modal fade"
    id="detailModal{{ $item->id }}"
    tabindex="-1"
    role="dialog"
    aria-hidden="true">

    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title">
                    Detail Monitoring Pinjaman
                </h5>

                <button type="button"
                        class="close text-white"
                        data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">
                @php
                    $totalAngsuran = $item->angsuran->count();
                    $lunas = $item->angsuran->where('status_bayar','lunas')->count();
                    $gagal = $item->angsuran->where('status_bayar','gagal_debet')->count();

                    $progress = $totalAngsuran == 0
                        ? 0
                        : round(($lunas/$totalAngsuran)*100);

                    $sudahBayar = $item->angsuran
                        ->where('status_bayar','lunas')
                        ->sum('jumlah_angsuran');

                    $sisa = $item->total_pinjaman - $sudahBayar;

                    $jatuhTempo = $item->angsuran
                        ->where('status_bayar','!=','lunas')
                        ->sortBy('tanggal_jatuh_tempo')
                        ->first();
                @endphp

                {{-- Ringkasan --}}
                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h4>{{ $lunas }}/{{ $totalAngsuran }}</h4>
                                <p>Angsuran Lunas</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h4>{{ $progress }}%</h4>
                                <p>Progress Pembayaran</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h4>
                                    Rp {{ number_format($sisa,0,',','.') }}
                                </h4>
                                <p>Sisa Pinjaman</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h4>{{ $gagal }}</h4>
                                <p>Gagal Debet</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    {{-- Informasi Pinjaman --}}
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th width="40%">Nama Anggota</th>
                                <td>{{ $item->pengajuan->users->name }}</td>
                            </tr>

                            <tr>
                                <th>Skema Pinjaman</th>
                                <td>{{ $item->pengajuan->skemaPinjaman->nama }}</td>
                            </tr>

                            <tr>
                                <th>Nominal Disetujui</th>
                                <td>
                                    Rp {{ number_format($item->jumlah_disetujui,0,',','.') }}
                                </td>
                            </tr>

                            <tr>
                                <th>Total Pinjaman</th>
                                <td>
                                    Rp {{ number_format($item->total_pinjaman,0,',','.') }}
                                </td>
                            </tr>

                            <tr>
                                <th>Tenor</th>
                                <td>{{ $item->pengajuan->lama_angsuran }} Bulan</td>
                            </tr>

                            <tr>
                                <th>Bunga</th>
                                <td>{{ $item->pengajuan->skemaPinjaman->bunga }} %</td>
                            </tr>

                            <tr>
                                <th>Tanggal Disetujui</th>
                                <td>
                                    {{ \Carbon\Carbon::parse($item->tanggal_disetujui)->locale('id')->translatedFormat('d F Y') }}
                                </td>
                            </tr>

                            <tr>
                                <th>Jatuh Tempo Berikutnya</th>
                                <td>
                                    @if($jatuhTempo)
                                        {{ \Carbon\Carbon::parse($jatuhTempo->tanggal_jatuh_tempo)->locale('id')->translatedFormat('d F Y') }}
                                    @else
                                    -
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>

                    {{-- Riwayat Angsuran --}}
                    <div class="col-md-6">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr class="text-center">
                                    <th>Angsuran Ke</th>
                                    <th>Nominal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($item->angsuran as $angsuran)
                                <tr class="text-center">
                                    <td>{{ $angsuran->angsuran_ke }}</td>
                                    <td>
                                        Rp {{ number_format($angsuran->jumlah_angsuran,0,',','.') }}
                                    </td>
                                    <td>
                                        @if($angsuran->status_bayar=='lunas')
                                            <span class="badge badge-success">
                                                Lunas
                                            </span>
                                        @elseif($angsuran->status_bayar=='gagal_debet')
                                            <span class="badge badge-danger">
                                                Gagal debet
                                            </span>
                                        @elseif($angsuran->status_bayar=='gagal_verifikasi')
                                            <span class="badge badge-danger">
                                                Gagal verifikasi
                                            </span>
                                        @elseif($angsuran->status_bayar=='verifikasi')
                                            <span class="badge badge-primary">
                                                Verifikasi
                                            </span>
                                        @else
                                            <span class="badge badge-warning">
                                                Belum Bayar
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary"
                        data-dismiss="modal">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
@endforeach

@section('css')
	 <!--some css
    <link rel="stylesheet" href="/assets/css/admin_custom.css">-->
@stop
@push('js')

@endpush