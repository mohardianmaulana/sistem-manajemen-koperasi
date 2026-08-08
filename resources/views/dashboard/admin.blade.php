@extends('adminlte::page')

@section('title', 'Dashboard Admin')

@section('content_header')
    <div class="dashboard-header">
        <div>
            <h1>Dashboard</h1>
            <p>Kelola pendaftaran dan data anggota koperasi.</p>
        </div>
    </div>
@stop

@section('content')

    {{-- Welcome --}}
    <div class="welcome-card mb-4">
        <div class="welcome-content">

            <div>
                <span class="welcome-label">
                    DASHBOARD ADMIN
                </span>

                <h2>
                    Selamat Datang,
                    <strong>{{ Auth::user()->name }}</strong>
                </h2>

                <p>
                    Pantau pendaftaran anggota dan lakukan proses persetujuan
                    terhadap calon anggota koperasi.
                </p>
            </div>

            <div class="welcome-icon">
                <i class="fas fa-users-cog"></i>
            </div>

        </div>
    </div>


    {{-- Perlu Tindakan --}}
    <div class="action-summary mb-4">

        <div class="action-summary-content">

            <div>
                <span class="section-label">
                    PERLU TINDAKAN
                </span>

                <h2>
                    {{ $summary['pendingUser'] }}
                    Pendaftaran
                </h2>

                <p>
                    Pendaftaran anggota yang masih menunggu persetujuan.
                </p>
            </div>

            <div class="action-summary-icon">
                <i class="fas fa-user-clock"></i>
            </div>

        </div>

    </div>


    {{-- Statistik --}}
    <div class="row">

        {{-- Menunggu --}}
        <div class="col-lg-4 col-md-6 mb-4">

            <div class="stat-card pending">

                <div class="stat-card-content">

                    <div>
                        <span>
                            PENDAFTARAN
                        </span>

                        <h3>
                            {{ $summary['pendingUser'] }}
                        </h3>

                        <p>
                            Menunggu Persetujuan
                        </p>
                    </div>

                    <div class="stat-icon">
                        <i class="fas fa-user-clock"></i>
                    </div>

                </div>

            </div>

        </div>


        {{-- Anggota Aktif --}}
        <div class="col-lg-4 col-md-6 mb-4">

            <div class="stat-card active">

                <div class="stat-card-content">

                    <div>
                        <span>
                            ANGGOTA
                        </span>

                        <h3>
                            {{ $summary['activeUser'] }}
                        </h3>

                        <p>
                            Anggota Aktif
                        </p>
                    </div>

                    <div class="stat-icon">
                        <i class="fas fa-user-check"></i>
                    </div>

                </div>

            </div>

        </div>


        {{-- Total --}}
        <div class="col-lg-4 col-md-6 mb-4">

            <div class="stat-card total">

                <div class="stat-card-content">

                    <div>
                        <span>
                            ANGGOTA
                        </span>

                        <h3>
                            {{ $summary['totalUser'] }}
                        </h3>

                        <p>
                            Total Data Anggota
                        </p>
                    </div>

                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Informasi Pendaftaran --}}
    <div class="info-card">

        <div class="info-card-header">

            <div>
                <span class="section-label">
                    PENDAFTARAN ANGGOTA
                </span>

                <h3>
                    Persetujuan Anggota
                </h3>
            </div>

            <div class="info-icon">
                <i class="fas fa-user-plus"></i>
            </div>

        </div>

        <div class="info-card-body">

            @if ($summary['pendingUser'] > 0)

                <div class="pending-message">

                    <div class="pending-message-icon">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>

                    <div>
                        <strong>
                            Ada {{ $summary['pendingUser'] }}
                            pendaftaran yang perlu diperiksa.
                        </strong>

                        <p>
                            Silakan periksa data calon anggota dan lakukan
                            proses persetujuan.
                        </p>
                    </div>

                </div>

                <a
                    href="{{ route('user.index') }}"
                    class="btn btn-primary"
                >
                    <i class="fas fa-users mr-1"></i>
                    Periksa Pendaftaran
                </a>

            @else

                <div class="empty-message">

                    <div class="empty-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>

                    <div>
                        <strong>
                            Tidak ada pendaftaran yang menunggu.
                        </strong>

                        <p>
                            Semua pendaftaran anggota telah diproses.
                        </p>
                    </div>

                </div>

            @endif

        </div>

    </div>

@stop


@section('css')

<style>

    /* =========================
       HEADER
    ========================= */

    .dashboard-header h1 {
        font-weight: 600;
        margin-bottom: 3px;
    }

    .dashboard-header p {
        color: #718096;
        margin: 0;
    }


    /* =========================
       WELCOME
    ========================= */

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


    /* =========================
       ACTION SUMMARY
    ========================= */

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


    /* =========================
       STAT CARD
    ========================= */

    .stat-card {
        background: white;

        border: 1px solid #e8edf2;

        border-radius: 16px;

        padding: 24px;

        box-shadow:
            0 7px 22px rgba(31,45,61,.06);

        transition: .25s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);

        box-shadow:
            0 14px 30px rgba(31,45,61,.10);
    }

    .stat-card-content {
        display: flex;

        justify-content: space-between;

        align-items: center;
    }

    .stat-card span {
        font-size: 10px;

        font-weight: 700;

        letter-spacing: 1px;

        color: #8a94a6;
    }

    .stat-card h3 {
        font-size: 35px;

        font-weight: 700;

        margin: 5px 0 0;

        color: #263238;
    }

    .stat-card p {
        margin: 0;

        color: #8a94a6;

        font-size: 13px;
    }

    .stat-icon {
        width: 58px;
        height: 58px;

        border-radius: 15px;

        display: flex;

        align-items: center;

        justify-content: center;

        font-size: 24px;
    }

    .stat-card.pending .stat-icon {
        background: #fff4e5;
        color: #f39c12;
    }

    .stat-card.active .stat-icon {
        background: #e4f8ed;
        color: #27ae60;
    }

    .stat-card.total .stat-icon {
        background: #e3f2fd;
        color: #3498db;
    }


    /* =========================
       INFO CARD
    ========================= */

    .info-card {
        background: white;

        border: 1px solid #e8edf2;

        border-radius: 16px;

        box-shadow:
            0 7px 22px rgba(31,45,61,.05);

        overflow: hidden;
    }

    .info-card-header {
        padding: 22px 25px;

        display: flex;

        justify-content: space-between;

        align-items: center;

        border-bottom: 1px solid #edf0f3;
    }

    .info-card-header h3 {
        margin: 4px 0 0;

        font-size: 20px;

        font-weight: 600;

        color: #263238;
    }

    .info-icon {
        width: 50px;
        height: 50px;

        border-radius: 13px;

        background: #e3f2fd;

        color: #3498db;

        display: flex;

        align-items: center;

        justify-content: center;

        font-size: 21px;
    }

    .info-card-body {
        padding: 25px;
    }


    /* =========================
       PENDING MESSAGE
    ========================= */

    .pending-message {
        display: flex;

        align-items: center;

        padding: 18px;

        border-radius: 12px;

        background: #fff9ef;

        border: 1px solid #fceccf;

        margin-bottom: 20px;
    }

    .pending-message-icon {
        width: 45px;
        height: 45px;

        border-radius: 12px;

        background: #fff0d3;

        color: #f39c12;

        display: flex;

        align-items: center;

        justify-content: center;

        margin-right: 15px;

        font-size: 20px;
    }

    .pending-message strong {
        color: #263238;
    }

    .pending-message p {
        margin: 3px 0 0;

        color: #718096;

        font-size: 13px;
    }


    /* =========================
       EMPTY MESSAGE
    ========================= */

    .empty-message {
        display: flex;

        align-items: center;
    }

    .empty-icon {
        width: 45px;
        height: 45px;

        border-radius: 12px;

        background: #e4f8ed;

        color: #27ae60;

        display: flex;

        align-items: center;

        justify-content: center;

        margin-right: 15px;

        font-size: 20px;
    }

    .empty-message strong {
        color: #263238;
    }

    .empty-message p {
        margin: 3px 0 0;

        color: #718096;

        font-size: 13px;
    }


    /* =========================
       RESPONSIVE
    ========================= */

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

    }

</style>

@stop