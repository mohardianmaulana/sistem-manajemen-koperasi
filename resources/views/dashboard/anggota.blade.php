@extends('adminlte::page')

@section('title', 'Dashboard Anggota')

@section('content_header')
    <div class="dashboard-header">
        <div>
            <h1>Dashboard</h1>
            <p>Ringkasan aktivitas simpanan Anda pada koperasi.</p>
        </div>
    </div>
@stop

@section('content')

    @php
        $totalSimpanan = $summary['sukarela']['total'] + $summary['wajib']['total'];
        $sisaPinjaman = $summary['pinjaman']['sisa'];
        $sisaBulan = $summary['pinjaman']['sisaBulan'];

        $totalSukarela =
            $summary['sukarela']['selesai'] +
            $summary['sukarela']['gagal'];

        $totalWajib =
            $summary['wajib']['selesai'] +
            $summary['wajib']['gagal'];

        $persentaseSukarela = $totalSukarela > 0
            ? round(($summary['sukarela']['selesai'] / $totalSukarela) * 100)
            : 0;

        $persentaseWajib = $totalWajib > 0
            ? round(($summary['wajib']['selesai'] / $totalWajib) * 100)
            : 0;
    @endphp

@if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fa-solid fa-circle-check"></i>
                    {{ session('success') }}

                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fa-solid fa-circle-xmark"></i>
                    {{ session('error') }}

                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            @endif
    {{-- Welcome --}}
    <div class="welcome-card mb-4">
        <div class="welcome-content">
            <div>
                <span class="welcome-label">DASHBOARD ANGGOTA</span>

                <h2>
                    Selamat Datang,
                    <strong>{{ Auth::user()->name }}</strong>
                </h2>

                <p>
                    Pantau perkembangan simpanan dan pinjaman Anda dengan mudah melalui dashboard ini.
                </p>
            </div>

            <div class="welcome-icon">
                <i class="fas fa-chart-pie"></i>
            </div>
        </div>
    </div>


    {{-- Total Seluruh Simpanan --}}
    <div class="total-card mb-4">
        <div class="total-card-content">

            <div class="total-icon">
                <i class="fas fa-coins"></i>
            </div>

            <div class="total-info">
                <span>Total Seluruh Simpanan</span>

                <h2>
                    Rp {{ number_format($totalSimpanan, 0, ',', '.') }}
                </h2>

                <small>
                    Gabungan simpanan wajib dan simpanan sukarela
                </small>
            </div>

        </div>
    </div>

    <div class="row">
        {{-- Total Sisa pinjaman --}}
        <div class="col-md-6 mb-2">
            <div class="total-card mb-2">
                <div class="total-card-content">
        
                    <div class="total-icon">
                        <i class="fas fa-coins"></i>
                    </div>
        
                    <div class="total-info">
                        <span>Sisa pinjaman</span>
        
                        <h2>
                            Rp {{ number_format($sisaPinjaman, 0, ',', '.') }}
                        </h2>
        
                        <small>
                            Tersisa {{ $sisaBulan }} bulan angsuran
                        </small>
                    </div>
        
                </div>
            </div>
        </div>
    
        <div class="col-md-6 mb-2">
            <div class="total-card mb-2">
                <div class="total-card-content">
        
                    <div class="total-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
        
                    <div class="total-info">
                        <span>Angsuran Bulan Ini</span>
        
                        <h2>
                            Rp {{ number_format($summary['pinjaman']['angsuranBulanIni']['total'], 0, ',', '.') }}
                        </h2>
        
                        <small>
                            {{ $summary['pinjaman']['angsuranBulanIni']['jumlah'] }}
                            angsuran jatuh tempo bulan ini
                        </small>
                    </div>
        
                </div>
            </div>
        </div>
    </div>


    {{-- Detail Simpanan --}}
    <div class="row">

        {{-- Simpanan Sukarela --}}
        <div class="col-lg-6 mb-4">
            <div class="saving-card saving-sukarela">

                <div class="saving-card-header">
                    <div>
                        <span class="saving-label">
                            SIMPANAN
                        </span>

                        <h3>Sukarela</h3>
                    </div>

                    <div class="saving-icon">
                        <i class="fas fa-hand-holding-usd"></i>
                    </div>
                </div>


                <div class="saving-amount">
                    <span>Total Nominal</span>

                    <h2>
                        Rp {{ number_format($summary['sukarela']['total'], 0, ',', '.') }}
                    </h2>
                </div>


                {{-- Progress --}}
                <div class="saving-progress">

                    <div class="progress-info">
                        <span>Tingkat keberhasilan</span>
                        <strong>{{ $persentaseSukarela }}%</strong>
                    </div>

                    <div class="progress">
                        <div
                            class="progress-bar"
                            role="progressbar"
                            style="width: {{ $persentaseSukarela }}%"
                        ></div>
                    </div>

                </div>


                {{-- Status --}}
                <div class="saving-status">

                    <div class="status-item success">
                        <div class="status-icon">
                            <i class="fas fa-check"></i>
                        </div>

                        <div>
                            <strong>
                                {{ $summary['sukarela']['selesai'] }}
                            </strong>

                            <span>Berhasil</span>
                        </div>
                    </div>


                    <div class="status-item failed">
                        <div class="status-icon">
                            <i class="fas fa-times"></i>
                        </div>

                        <div>
                            <strong>
                                {{ $summary['sukarela']['gagal'] }}
                            </strong>

                            <span>Gagal</span>
                        </div>
                    </div>

                </div>

            </div>
        </div>


        {{-- Simpanan Wajib --}}
        <div class="col-lg-6 mb-4">
            <div class="saving-card saving-wajib">

                <div class="saving-card-header">
                    <div>
                        <span class="saving-label">
                            SIMPANAN
                        </span>

                        <h3>Wajib</h3>
                    </div>

                    <div class="saving-icon">
                        <i class="fas fa-wallet"></i>
                    </div>
                </div>


                <div class="saving-amount">
                    <span>Total Nominal</span>

                    <h2>
                        Rp {{ number_format($summary['wajib']['total'], 0, ',', '.') }}
                    </h2>
                </div>


                {{-- Progress --}}
                <div class="saving-progress">

                    <div class="progress-info">
                        <span>Tingkat keberhasilan</span>
                        <strong>{{ $persentaseWajib }}%</strong>
                    </div>

                    <div class="progress">
                        <div
                            class="progress-bar"
                            role="progressbar"
                            style="width: {{ $persentaseWajib }}%"
                        ></div>
                    </div>

                </div>


                {{-- Status --}}
                <div class="saving-status">

                    <div class="status-item success">
                        <div class="status-icon">
                            <i class="fas fa-check"></i>
                        </div>

                        <div>
                            <strong>
                                {{ $summary['wajib']['selesai'] }}
                            </strong>

                            <span>Berhasil</span>
                        </div>
                    </div>


                    <div class="status-item failed">
                        <div class="status-icon">
                            <i class="fas fa-times"></i>
                        </div>

                        <div>
                            <strong>
                                {{ $summary['wajib']['gagal'] }}
                            </strong>

                            <span>Gagal</span>
                        </div>
                    </div>

                </div>

            </div>
        </div>

    </div>


    {{-- Ringkasan Status --}}
    <div class="row">

        <div class="col-md-4 mb-3">
            <div class="mini-card">
                <div class="mini-icon blue">
                    <i class="fas fa-coins"></i>
                </div>

                <div>
                    <span>Total Simpanan</span>

                    <h4>
                        Rp {{ number_format($totalSimpanan, 0, ',', '.') }}
                    </h4>
                </div>
            </div>
        </div>


        <div class="col-md-4 mb-3">
            <div class="mini-card">
                <div class="mini-icon green">
                    <i class="fas fa-check-circle"></i>
                </div>

                <div>
                    <span>Total Berhasil</span>

                    <h4>
                        {{ $summary['sukarela']['selesai'] + $summary['wajib']['selesai'] }}
                        Transaksi
                    </h4>
                </div>
            </div>
        </div>


        <div class="col-md-4 mb-3">
            <div class="mini-card">
                <div class="mini-icon red">
                    <i class="fas fa-times-circle"></i>
                </div>

                <div>
                    <span>Total Gagal</span>

                    <h4>
                        {{ $summary['sukarela']['gagal'] + $summary['wajib']['gagal'] }}
                        Transaksi
                    </h4>
                </div>
            </div>
        </div>

    </div>

@stop


@section('css')

<style>

    /* =========================
       DASHBOARD HEADER
    ========================= */

    .dashboard-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 10px;
    }

    .dashboard-header h1 {
        font-weight: 600;
        margin-bottom: 3px;
    }

    .dashboard-header p {
        color: #6c757d;
        margin: 0;
    }


    /* =========================
       WELCOME CARD
    ========================= */

    .welcome-card {
        background: linear-gradient(
            135deg,
            #ffffff 0%,
            #eef7ff 100%
        );

        border-radius: 16px;
        padding: 28px 32px;

        border: 1px solid #e4edf5;

        box-shadow: 0 8px 25px rgba(31, 45, 61, 0.06);
    }

    .welcome-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .welcome-label {
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 1px;
        color: #3498db;
    }

    .welcome-card h2 {
        font-size: 25px;
        margin: 8px 0;
        color: #263238;
    }

    .welcome-card p {
        color: #718096;
        margin: 0;
    }

    .welcome-icon {
        width: 75px;
        height: 75px;

        border-radius: 20px;

        display: flex;
        align-items: center;
        justify-content: center;

        background: #e3f2fd;
        color: #3498db;

        font-size: 34px;
    }


    /* =========================
       TOTAL CARD
    ========================= */

    .total-card {
        background: linear-gradient(
            135deg,
            #1769aa 0%,
            #3498db 100%
        );

        border-radius: 16px;
        padding: 25px 30px;

        color: white;

        box-shadow: 0 10px 25px rgba(52, 152, 219, 0.25);
    }

    .total-card-content {
        display: flex;
        align-items: center;
    }

    .total-icon {
        width: 65px;
        height: 65px;

        border-radius: 16px;

        background: rgba(255,255,255,0.18);

        display: flex;
        align-items: center;
        justify-content: center;

        font-size: 28px;

        margin-right: 20px;
    }

    .total-info span {
        font-size: 14px;
        opacity: .85;
    }

    .total-info h2 {
        font-size: 30px;
        margin: 3px 0;
        font-weight: 700;
    }

    .total-info small {
        opacity: .8;
    }


    /* =========================
       SAVING CARD
    ========================= */

    .saving-card {
        background: #ffffff;

        border-radius: 16px;

        padding: 25px;

        border: 1px solid #e9edf2;

        box-shadow: 0 8px 25px rgba(31, 45, 61, 0.07);

        transition: all .25s ease;

        height: 100%;
    }

    .saving-card:hover {
        transform: translateY(-4px);

        box-shadow:
            0 15px 35px rgba(31, 45, 61, 0.12);
    }

    .saving-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .saving-label {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1px;
        color: #8a94a6;
    }

    .saving-card h3 {
        font-size: 23px;
        margin: 3px 0 0;
        font-weight: 600;
        color: #263238;
    }

    .saving-icon {
        width: 58px;
        height: 58px;

        border-radius: 15px;

        display: flex;
        align-items: center;
        justify-content: center;

        font-size: 25px;
    }


    /* Sukarela */

    .saving-sukarela .saving-icon {
        background: #e0f7fa;
        color: #16a6b6;
    }


    /* Wajib */

    .saving-wajib .saving-icon {
        background: #e3f2fd;
        color: #3498db;
    }


    /* =========================
       NOMINAL
    ========================= */

    .saving-amount {
        margin-top: 25px;
    }

    .saving-amount span {
        color: #8a94a6;
        font-size: 13px;
    }

    .saving-amount h2 {
        margin: 4px 0 0;

        font-size: 27px;

        font-weight: 700;

        color: #263238;
    }


    /* =========================
       PROGRESS
    ========================= */

    .saving-progress {
        margin-top: 25px;
    }

    .progress-info {
        display: flex;
        justify-content: space-between;

        font-size: 12px;

        color: #7a869a;

        margin-bottom: 7px;
    }

    .progress {
        height: 7px;

        background: #edf1f5;

        border-radius: 20px;
    }

    .progress-bar {
        border-radius: 20px;

        background: linear-gradient(
            90deg,
            #27ae60,
            #2ecc71
        );
    }


    /* =========================
       STATUS
    ========================= */

    .saving-status {
        display: flex;

        gap: 12px;

        margin-top: 25px;

        padding-top: 20px;

        border-top: 1px solid #edf0f3;
    }

    .status-item {
        flex: 1;

        display: flex;

        align-items: center;

        padding: 10px;

        border-radius: 10px;
    }

    .status-item.success {
        background: #f0faf4;
    }

    .status-item.failed {
        background: #fff3f3;
    }

    .status-icon {
        width: 35px;
        height: 35px;

        border-radius: 50%;

        display: flex;
        align-items: center;
        justify-content: center;

        margin-right: 10px;
    }

    .success .status-icon {
        background: #d9f5e4;
        color: #27ae60;
    }

    .failed .status-icon {
        background: #fde0e0;
        color: #e74c3c;
    }

    .status-item strong {
        display: block;

        font-size: 17px;

        color: #263238;
    }

    .status-item span {
        display: block;

        font-size: 11px;

        color: #7a869a;
    }


    /* =========================
       MINI CARD
    ========================= */

    .mini-card {
        background: white;

        border: 1px solid #e9edf2;

        border-radius: 13px;

        padding: 18px;

        display: flex;

        align-items: center;

        box-shadow: 0 5px 18px rgba(31, 45, 61, 0.05);
    }

    .mini-icon {
        width: 45px;
        height: 45px;

        border-radius: 12px;

        display: flex;
        align-items: center;
        justify-content: center;

        font-size: 18px;

        margin-right: 13px;
    }

    .mini-icon.blue {
        background: #e3f2fd;
        color: #3498db;
    }

    .mini-icon.green {
        background: #e4f8ed;
        color: #27ae60;
    }

    .mini-icon.red {
        background: #fdeaea;
        color: #e74c3c;
    }

    .mini-card span {
        display: block;

        color: #8a94a6;

        font-size: 12px;
    }

    .mini-card h4 {
        margin: 3px 0 0;

        font-weight: 600;

        color: #263238;
    }


    /* =========================
       RESPONSIVE
    ========================= */

    @media (max-width: 768px) {

        .welcome-content {
            align-items: flex-start;
        }

        .welcome-icon {
            display: none;
        }

        .welcome-card h2 {
            font-size: 21px;
        }

        .total-info h2 {
            font-size: 24px;
        }

        .saving-amount h2 {
            font-size: 23px;
        }
    }

</style>

@stop