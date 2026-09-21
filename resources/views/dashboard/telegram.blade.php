@extends('adminlte::page')

@section('title', 'Hubungkan Telegram')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fab fa-telegram-plane mr-2"></i>
                        Hubungkan Akun Telegram
                    </h3>
                </div>

                <div class="card-body text-center py-5">

                    <div class="mb-4">
                        <i class="fab fa-telegram-plane text-primary"
                           style="font-size: 70px;"></i>
                    </div>

                    <h4 class="font-weight-bold mb-3">
                        Hubungkan Telegram Anda
                    </h4>

                    <p class="text-muted mb-4">
                        Hubungkan akun Telegram Anda untuk menerima
                        notifikasi terkait aktivitas koperasi seperti
                        pengajuan pinjaman, persetujuan, pencairan,
                        dan pembayaran angsuran.
                    </p>

                    <div class="alert alert-primary text-left">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Cara menghubungkan:</strong>
                        <ol class="mb-0 mt-2">
                            <li>Download Telegram Desktop/Mobile.</li>
                            <li>Login akun telegram</li>
                            <li>Klik tombol <strong>Hubungkan Telegram</strong>.</li>
                            <li>Telegram akan terbuka secara otomatis.</li>
                            <li>Ketik <strong>/start {{$token}}</strong> pada bot Telegram.</li>
                            <li>Setelah muncul balasan ✔ Akun Telegram berhasil dihubungkan, kembali ke aplikasi, dan reload halaman.</li>
                        </ol>
                    </div>

                    <a href="https://t.me/{{ config('services.telegram.bot_username') }}"
                        target="_blank"
                        class="btn btn-primary btn-lg mt-3">
                            <i class="fab fa-telegram-plane mr-2"></i>
                            Hubungkan Telegram
                    </a>

                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        /* Sembunyikan sidebar dan tombol toggle hamburger */
        .main-sidebar,
        [data-widget="pushmenu"] {
            display: none !important;
        }

        /* Geser konten utama agar memenuhi area kiri layar */
        .content-wrapper,
        .main-header {
            margin-left: 0 !important;
        }
        
        .card {
            border-radius: 10px;
        }

        .card-header {
            border-radius: 10px 10px 0 0;
        }

        .alert {
            border-radius: 8px;
        }
    </style>
@stop