@extends('adminlte::page')

@section('title', 'Dashboard Ketua')

@section('content_header')
    <div class="dashboard-header">
        <div>
            <h1>Dashboard</h1>
            <p>
                Pantau transaksi keuangan yang membutuhkan proses Anda.
            </p>
        </div>
    </div>
@stop

@section('content')

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
                <span class="welcome-label">
                    DASHBOARD KETUA
                </span>

                <h2>
                    Selamat Datang,
                    <strong>{{ Auth::user()->name }}</strong>
                </h2>

                <p>
                    Kelola proses persetujuan pengajuan pinjaman
                    anggota koperasi.
                </p>
            </div>

            <div class="welcome-icon">
                <i class="fas fa-wallet"></i>
            </div>

        </div>
    </div>

        <div class="col-md-12">
            {{-- Perlu Tindakan --}}
            <div class="action-summary mb-4">
        
                <div class="action-summary-content">
        
                    <div>
                        <span class="section-label">
                            PERLU TINDAKAN
                        </span>
        
                        <h2>
                            {{ $summary['pinjaman']['jumlahPersetujuan'] }} persetujuan
                        </h2>
        
                        <p>
                            Pengajuan pinjaman yang membutuhkan proses persetujuan.
                        </p>
                    </div>
        
                    <div class="action-summary-icon">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
        
                </div>
        
            </div>
        </div>

    {{-- Ringkasan Status --}}
    <div class="section-title">
        <h3>Ringkasan Pinjaman</h3>
    </div>


    <div class="row">

        {{-- Pinjaman Aktif --}}
        <div class="col-lg-3 mb-4">

            <div class="status-card">

                <div class="status-card-header">

                    <div>
                        <span class="card-label">
                            PINJAMAN
                        </span>

                        <h4>Pinjaman Aktif</h4>
                    </div>

                    <i class="fas fa-hand-holding-usd"></i>

                </div>

                <div class="status-list">

                    <div class="status-row">
                        <span>
                            <i class="fas fa-check-circle text-success"></i>
                            Aktif
                        </span>

                        <strong>
                            {{ $summary['pinjaman']['jumlahPinjamanAktif'] }}
                        </strong>
                    </div>

                </div>

            </div>

        </div>


        {{-- Total Nominal Pinjaman Aktif --}}
        <div class="col-lg-3 mb-4">

            <div class="status-card">

                <div class="status-card-header">

                    <div>
                        <span class="card-label">
                            PINJAMAN
                        </span>

                        <h4>Total Nominal</h4>
                    </div>

                    <i class="fas fa-money-bill-wave"></i>

                </div>

                <div class="status-list">

                    <div class="status-row">
                        <span>
                            <i class="fas fa-wallet text-primary"></i>
                            Pinjaman Aktif
                        </span>

                        <strong>
                            Rp {{ number_format(
                                $summary['pinjaman']['sisaPinjaman'],
                                0,
                                ',',
                                '.'
                            ) }}
                        </strong>
                    </div>

                </div>

            </div>

        </div>


        {{-- Total Tunggakan --}}
        <div class="col-lg-3 mb-4">

            <div class="status-card">

                <div class="status-card-header">

                    <div>
                        <span class="card-label">
                            PINJAMAN
                        </span>

                        <h4>Tunggakan</h4>
                    </div>

                    <i class="fas fa-exclamation-triangle"></i>

                </div>

                <div class="status-list">

                    <div class="status-row">
                        <span>
                            <i class="fas fa-clock text-warning"></i>
                            Belum Dibayar
                        </span>

                        <strong>
                            Rp {{ number_format(
                                $summary['pinjaman']['totalTunggakan'],
                                0,
                                ',',
                                '.'
                            ) }}
                        </strong>
                    </div>

                </div>

            </div>

        </div>


        {{-- Pengajuan Bulan Ini --}}
        <div class="col-lg-3 mb-4">

            <div class="status-card">

                <div class="status-card-header">

                    <div>
                        <span class="card-label">
                            PINJAMAN
                        </span>

                        <h4>Pengajuan Bulan Ini</h4>
                    </div>

                    <i class="fas fa-file-alt"></i>

                </div>

                <div class="status-list">

                    <div class="status-row">
                        <span>
                            <i class="fas fa-calendar-alt text-info"></i>
                            Pengajuan
                        </span>

                        <strong>
                            {{ $summary['pinjaman']['totalPengajuanPinjamanBulanIni'] }}
                        </strong>
                    </div>

                </div>

            </div>

        </div>

    </div>

@stop


@section('css')

<style>

    /* ==============================
       HEADER
    ============================== */

    .dashboard-header h1 {
        font-weight: 600;
        margin-bottom: 3px;
    }

    .dashboard-header p {
        color: #718096;
        margin: 0;
    }


    /* ==============================
       WELCOME
    ============================== */

    .welcome-card {
        background: linear-gradient(
            135deg,
            #ffffff,
            #eef7ff
        );

        border: 1px solid #e5edf5;

        border-radius: 16px;

        padding: 28px 32px;

        box-shadow:
            0 8px 25px rgba(31, 45, 61, .06);
    }

    .welcome-content {
        display: flex;

        align-items: center;

        justify-content: space-between;
    }

    .welcome-label {
        font-size: 11px;

        font-weight: 700;

        letter-spacing: 1px;

        color: #3498db;
    }

    .welcome-card h2 {
        margin: 8px 0;

        font-size: 25px;

        color: #263238;
    }

    .welcome-card p {
        margin: 0;

        color: #718096;
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

        font-size: 32px;
    }


    /* ==============================
       ACTION SUMMARY
    ============================== */

    .action-summary {
        background: linear-gradient(
            135deg,
            #1769aa,
            #3498db
        );

        border-radius: 16px;

        padding: 26px 30px;

        color: white;

        box-shadow:
            0 10px 25px rgba(52, 152, 219, .22);
    }

    .action-summary-content {
        display: flex;

        justify-content: space-between;

        align-items: center;
    }

    .section-label {
        font-size: 10px;

        font-weight: 700;

        letter-spacing: 1px;

        color: #3498db;
    }

    .action-summary .section-label {
        color: rgba(255,255,255,.8);
    }

    .action-summary h2 {
        margin: 5px 0;

        font-size: 30px;

        font-weight: 700;
    }

    .action-summary p {
        margin: 0;

        opacity: .85;
    }

    .action-summary-icon {
        width: 65px;
        height: 65px;

        border-radius: 16px;

        background: rgba(255,255,255,.15);

        display: flex;

        align-items: center;

        justify-content: center;

        font-size: 28px;
    }


    /* ==============================
       PROCESS CARD
    ============================== */

    .process-card {
        background: white;

        border: 1px solid #e8edf2;

        border-radius: 16px;

        padding: 25px;

        height: 100%;

        box-shadow:
            0 7px 22px rgba(31,45,61,.06);

        transition: .25s ease;
    }

    .process-card:hover {
        transform: translateY(-4px);

        box-shadow:
            0 14px 30px rgba(31,45,61,.10);
    }

    .process-card-header {
        display: flex;

        justify-content: space-between;

        align-items: center;

        padding-bottom: 20px;

        border-bottom: 1px solid #edf0f3;
    }

    .card-label {
        font-size: 10px;

        font-weight: 700;

        letter-spacing: 1px;

        color: #8a94a6;
    }

    .process-card h3 {
        margin: 4px 0 0;

        font-size: 21px;

        font-weight: 600;

        color: #263238;
    }

    .process-icon {
        width: 56px;
        height: 56px;

        border-radius: 15px;

        display: flex;

        align-items: center;

        justify-content: center;

        font-size: 24px;
    }

    .process-icon.penarikan {
        background: #fff4e5;

        color: #f39c12;
    }

    .process-icon.shu {
        background: #e4f8ed;

        color: #27ae60;
    }


    /* ==============================
       PROCESS MAIN
    ============================== */

    .process-main {
        display: flex;

        align-items: center;

        gap: 20px;

        padding: 25px 0;
    }

    .process-number {
        font-size: 45px;

        font-weight: 700;

        color: #263238;

        line-height: 1;
    }

    .process-main strong {
        font-size: 15px;

        color: #263238;
    }

    .process-main p {
        margin: 5px 0 0;

        color: #8a94a6;

        font-size: 13px;
    }


    /* ==============================
       PROCESS FOOTER
    ============================== */

    .process-footer {
        border-top: 1px solid #edf0f3;

        padding-top: 15px;

        display: flex;

        justify-content: space-between;

        align-items: center;

        font-size: 12px;
    }

    .process-footer span {
        color: #8a94a6;
    }

    .process-footer a {
        color: #3498db;

        font-weight: 600;

        text-decoration: none;
    }

    .process-footer a i {
        margin-left: 4px;
    }


    /* ==============================
       SECTION TITLE
    ============================== */

    .section-title {
        margin: 10px 0 20px;
    }

    .section-title span {
        font-size: 10px;

        letter-spacing: 1px;

        font-weight: 700;

        color: #3498db;
    }

    .section-title h3 {
        margin: 3px 0 0;

        font-size: 20px;

        font-weight: 600;

        color: #263238;
    }


    /* ==============================
       STATUS CARD
    ============================== */

    .status-card {
        background: white;

        border: 1px solid #e8edf2;

        border-radius: 15px;

        padding: 22px;

        box-shadow:
            0 6px 20px rgba(31,45,61,.05);
    }

    .status-card-header {
        display: flex;

        justify-content: space-between;

        align-items: center;

        padding-bottom: 17px;

        border-bottom: 1px solid #edf0f3;
    }

    .status-card-header h4 {
        margin: 4px 0 0;

        font-weight: 600;

        color: #263238;
    }

    .status-card-header > i {
        font-size: 23px;

        color: #b7c0cc;
    }

    .status-list {
        padding-top: 8px;
    }

    .status-row {
        display: flex;

        justify-content: space-between;

        align-items: center;

        padding: 10px 0;

        color: #718096;
    }

    .status-row strong {
        color: #263238;

        font-size: 16px;
    }

    .status-row i {
        width: 20px;
    }


    /* ==============================
       RESPONSIVE
    ============================== */

    @media (max-width: 768px) {

        .welcome-icon,
        .action-summary-icon {
            display: none;
        }

        .welcome-card h2 {
            font-size: 21px;
        }

        .action-summary h2 {
            font-size: 25px;
        }

        .process-main {
            gap: 15px;
        }

        .process-number {
            font-size: 38px;
        }

    }

</style>

@stop