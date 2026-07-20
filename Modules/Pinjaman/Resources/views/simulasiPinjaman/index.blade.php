@extends('adminlte::page')

@section('title', 'Simulasi Pinjaman')

@section('content_header')
    <h1 class="m-0 text-dark">Simulasi Pinjaman</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 style="color: black;">Pilih skema pinjaman</h4>
                    <div class="row justify-content-center">
                    @forelse($skema_pinjaman as $item)
                        <div class="col-lg-4 col-md-6 col-sm-12 mb-4 d-flex">
                            <div class="card shadow-lg border-0 w-100 h-100"
                                style="border-radius: 20px; overflow: hidden;">

                                {{-- Header Card --}}
                                <div class="card-header text-white"
                                    style="background: linear-gradient(135deg, #159fea, #5cb3ed);">
                                    <h4 class="mb-0 font-weight-bold">
                                        {{ $item->nama }}
                                    </h4>
                                </div>

                                {{-- Body Card --}}
                                <div class="card-body d-flex flex-column">

                                    <p class="text-muted mb-4">
                                        {{ $item->deskripsi }}
                                    </p>

                                    <div class="mb-2">
                                        <span class="font-weight-bold">
                                            <i class="fas fa-money-bill-wave text-info"></i>
                                            Nominal : 
                                        </span>
                                        <br>
                                        Rp {{ number_format($item->min_nominal, 0, ',', '.') }} - Rp {{ number_format($item->max_nominal, 0, ',', '.') }}
                                    </div>

                                    <div class="mb-2">
                                        <span class="font-weight-bold">
                                            <i class="fas fa-calendar-alt text-info"></i>
                                            Tenor :
                                        </span>
                                        <br>
                                        {{ $item->min_tenor }} - {{ $item->max_tenor }} Bulan
                                    </div>

                                    <div class="mb-2">
                                        <span class="font-weight-bold">
                                            <i class="fas fa-percent text-info"></i>
                                            Bunga :
                                        </span>
                                        <br>
                                        {{ $item->bunga }} %
                                    </div>

                                    <div class="">
                                        <span class="font-weight-bold">
                                            <i class="fas fa-shield-alt text-info"></i>
                                            Jaminan :
                                        </span>
                                        <br>
                                        <div class="border rounded p-2 bg-light">
                                            @forelse($item->daftarJaminan as $jaminan)
                                                <div class="mb-1">
                                                    <i class="fas fa-check-circle text-success mr-2"></i>
                                                    {{ $jaminan->nama }}
                                                </div>
                                            @empty
                                                <div class="text-muted">
                                                    Tidak ada
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>

                                    {{-- Tombol --}}
                                    <div class="mt-auto">
                                        <a href="{{ route('simulasiPinjaman.hasil', $item->id) }}" class="btn btn-primary btn-block"
                                        style="border-radius: 10px;">
                                            <i class="fas fa-calculator"></i>
                                            Pilih
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center">
                            <div class="card shadow-sm mx-auto p-3"
                                style="max-width: 300px; border-radius: 15px; background-color: #6c757d; color: white;">
                                <h5 class="mb-0">
                                    Tidak ada skema pinjaman
                                </h5>
                            </div>
                        </div>
                    @endforelse

                </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
	 <!--some css
    <link rel="stylesheet" href="/assets/css/admin_custom.css">-->
@stop
@push('js')

@endpush