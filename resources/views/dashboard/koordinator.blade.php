@extends('adminlte::page')

@section('title', 'Dashboard Koordinator')

@section('content_header')
    <div class="dashboard-header">
        <div>
            <h1>Dashboard</h1>
            <p>Ringkasan aktivitas yang memerlukan tindakan Anda.</p>
        </div>
    </div>
@stop

@section('content')

    @php
        $pendingSukarela = $summary['sukarela']['pending'];
        $pendingWajib = $summary['wajib']['pending'];
        $pendingPenarikan = $summary['penarikan']['menunggu'];

        $totalMenunggu = $pendingSukarela
            + $pendingWajib
            + $pendingPenarikan;
    @endphp


    {{-- Welcome --}}
    <div class="welcome-card mb-4">
        <div class="welcome-content">

            <div>
                <span class="welcome-label">
                    DASHBOARD KOORDINATOR
                </span>

                <h2>
                    Selamat Datang,
                    <strong>{{ Auth::user()->name }}</strong>
                </h2>

                <p>
                    Pantau dan lakukan verifikasi terhadap aktivitas simpanan
                    dan penarikan anggota.
                </p>
            </div>

            <div class="welcome-icon">
                <i class="fas fa-tasks"></i>
            </div>

        </div>
    </div>


    {{-- Perlu Tindakan --}}
    <div class="action-summary mb-4">

        <div class="action-summary-header">
            <div>
                <span class="section-label">
                    PERLU TINDAKAN
                </span>

                <h3>
                    {{ $totalMenunggu }} aktivitas
                </h3>

                <p>
                    Data yang masih membutuhkan proses verifikasi.
                </p>
            </div>

            <div class="action-summary-icon">
                <i class="fas fa-bell"></i>
            </div>
        </div>

    </div>


    {{-- Kartu Perlu Verifikasi --}}
    <div class="row">

        {{-- Simpanan Sukarela --}}
        <div class="col-lg-4 col-md-6 mb-4">

            <div class="action-card">

                <div class="action-card-header">

                    <div>
                        <span class="card-label">
                            SIMPANAN
                        </span>

                        <h3>Sukarela</h3>
                    </div>

                    <div class="action-icon sukarela">
                        <i class="fas fa-hand-holding-usd"></i>
                    </div>

                </div>

                <div class="action-number">
                    {{ $pendingSukarela }}
                </div>

                <p class="action-description">
                    Simpanan menunggu verifikasi
                </p>

                <div class="action-footer">

                    <span>
                        <i class="fas fa-clock"></i>
                        Menunggu tindakan
                    </span>

                    <a href="{{ route('simpanan-sukarela.index') }}">
                        Periksa
                        <i class="fas fa-arrow-right"></i>
                    </a>

                </div>

            </div>

        </div>


        {{-- Simpanan Wajib --}}
        <div class="col-lg-4 col-md-6 mb-4">

            <div class="action-card">

                <div class="action-card-header">

                    <div>
                        <span class="card-label">
                            SIMPANAN
                        </span>

                        <h3>Wajib</h3>
                    </div>

                    <div class="action-icon wajib">
                        <i class="fas fa-wallet"></i>
                    </div>

                </div>

                <div class="action-number">
                    {{ $pendingWajib }}
                </div>

                <p class="action-description">
                    Simpanan menunggu verifikasi
                </p>

                <div class="action-footer">

                    <span>
                        <i class="fas fa-clock"></i>
                        Menunggu tindakan
                    </span>

                    <a href="{{ route('simpanan-wajib.index') }}">
                        Periksa
                        <i class="fas fa-arrow-right"></i>
                    </a>

                </div>

            </div>

        </div>


        {{-- Penarikan --}}
        <div class="col-lg-4 col-md-6 mb-4">

            <div class="action-card">

                <div class="action-card-header">

                    <div>
                        <span class="card-label">
                            PENARIKAN
                        </span>

                        <h3>Simpanan Sukarela</h3>
                    </div>

                    <div class="action-icon penarikan">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>

                </div>

                <div class="action-number">
                    {{ $pendingPenarikan }}
                </div>

                <p class="action-description">
                    Penarikan menunggu verifikasi
                </p>

                <div class="action-footer">

                    <span>
                        <i class="fas fa-clock"></i>
                        Menunggu tindakan
                    </span>

                    <a href="{{ route('pencairan-simpanan.index') }}">
                        Periksa
                        <i class="fas fa-arrow-right"></i>
                    </a>

                </div>

            </div>

        </div>

    </div>


    {{-- Ringkasan Status --}}
    <div class="section-title">
        <span>RINGKASAN AKTIVITAS</span>
        <h3>Status Simpanan dan Penarikan</h3>
    </div>


    <div class="row">

        {{-- Sukarela --}}
        <div class="col-lg-4 mb-4">

            <div class="status-card">

                <div class="status-card-header">

                    <div>
                        <span class="card-label">
                            SIMPANAN
                        </span>

                        <h4>Sukarela</h4>
                    </div>

                    <i class="fas fa-hand-holding-usd"></i>

                </div>

                <div class="status-list">

                    <div class="status-row">
                        <span>
                            <i class="fas fa-clock text-warning"></i>
                            Menunggu
                        </span>

                        <strong>
                            {{ $summary['sukarela']['pending'] }}
                        </strong>
                    </div>

                    <div class="status-row">
                        <span>
                            <i class="fas fa-check-circle text-success"></i>
                            Berhasil
                        </span>

                        <strong>
                            {{ $summary['sukarela']['selesai'] }}
                        </strong>
                    </div>

                </div>

            </div>

        </div>


        {{-- Wajib --}}
        <div class="col-lg-4 mb-4">

            <div class="status-card">

                <div class="status-card-header">

                    <div>
                        <span class="card-label">
                            SIMPANAN
                        </span>

                        <h4>Wajib</h4>
                    </div>

                    <i class="fas fa-wallet"></i>

                </div>

                <div class="status-list">

                    <div class="status-row">
                        <span>
                            <i class="fas fa-clock text-warning"></i>
                            Menunggu
                        </span>

                        <strong>
                            {{ $summary['wajib']['pending'] }}
                        </strong>
                    </div>

                    <div class="status-row">
                        <span>
                            <i class="fas fa-check-circle text-success"></i>
                            Berhasil
                        </span>

                        <strong>
                            {{ $summary['wajib']['selesai'] }}
                        </strong>
                    </div>

                </div>

            </div>

        </div>


        {{-- Penarikan --}}
        <div class="col-lg-4 mb-4">

            <div class="status-card">

                <div class="status-card-header">

                    <div>
                        <span class="card-label">
                            PENARIKAN
                        </span>

                        <h4>Simpanan Sukarela</h4>
                    </div>

                    <i class="fas fa-money-bill-wave"></i>

                </div>

                <div class="status-list">

                    <div class="status-row">
                        <span>
                            <i class="fas fa-clock text-warning"></i>
                            Menunggu
                        </span>

                        <strong>
                            {{ $summary['penarikan']['menunggu'] }}
                        </strong>
                    </div>

                    <div class="status-row">
                        <span>
                            <i class="fas fa-check-circle text-success"></i>
                            Berhasil
                        </span>

                        <strong>
                            {{ $summary['penarikan']['berhasil'] }}
                        </strong>
                    </div>

                    <div class="status-row">
                        <span>
                            <i class="fas fa-times-circle text-danger"></i>
                            Ditolak
                        </span>

                        <strong>
                            {{ $summary['penarikan']['ditolak'] }}
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

        padding: 25px 30px;

        color: white;

        box-shadow:
            0 10px 25px rgba(52, 152, 219, .22);
    }

    .action-summary-header {
        display: flex;

        align-items: center;

        justify-content: space-between;
    }

    .section-label {
        font-size: 11px;

        font-weight: 700;

        letter-spacing: 1px;

        opacity: .8;
    }

    .action-summary h3 {
        margin: 5px 0;

        font-size: 30px;

        font-weight: 700;
    }

    .action-summary p {
        margin: 0;

        opacity: .8;
    }

    .action-summary-icon {
        width: 65px;
        height: 65px;

        border-radius: 16px;

        background: rgba(255,255,255,.15);

        display: flex;

        align-items: center;

        justify-content: center;

        font-size: 27px;
    }


    /* ==============================
       ACTION CARD
    ============================== */

    .action-card {
        background: white;

        border: 1px solid #e8edf2;

        border-radius: 16px;

        padding: 24px;

        height: 100%;

        box-shadow:
            0 7px 22px rgba(31,45,61,.06);

        transition: .25s ease;
    }

    .action-card:hover {
        transform: translateY(-4px);

        box-shadow:
            0 14px 30px rgba(31,45,61,.11);
    }

    .action-card-header {
        display: flex;

        justify-content: space-between;

        align-items: center;
    }

    .card-label {
        font-size: 10px;

        font-weight: 700;

        letter-spacing: 1px;

        color: #8a94a6;
    }

    .action-card h3 {
        margin: 4px 0 0;

        font-size: 21px;

        font-weight: 600;

        color: #263238;
    }

    .action-icon {
        width: 55px;
        height: 55px;

        border-radius: 15px;

        display: flex;

        align-items: center;

        justify-content: center;

        font-size: 23px;
    }

    .action-icon.sukarela {
        background: #e0f7fa;
        color: #16a6b6;
    }

    .action-icon.wajib {
        background: #e3f2fd;
        color: #3498db;
    }

    .action-icon.penarikan {
        background: #fff4e5;
        color: #f39c12;
    }

    .action-number {
        font-size: 42px;

        font-weight: 700;

        color: #263238;

        margin-top: 25px;
    }

    .action-description {
        color: #8a94a6;

        margin-bottom: 22px;
    }

    .action-footer {
        border-top: 1px solid #edf0f3;

        padding-top: 15px;

        display: flex;

        justify-content: space-between;

        align-items: center;

        font-size: 12px;
    }

    .action-footer span {
        color: #8a94a6;
    }

    .action-footer a {
        color: #3498db;

        font-weight: 600;

        text-decoration: none;
    }

    .action-footer a i {
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

        .welcome-icon {
            display: none;
        }

        .welcome-card h2 {
            font-size: 21px;
        }

        .action-summary-icon {
            display: none;
        }

        .action-number {
            font-size: 36px;
        }

    }

</style>

@stop