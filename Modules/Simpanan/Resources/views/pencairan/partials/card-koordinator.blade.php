<div class="row">

    <div class="col-lg-4 col-md-6">

        <div class="small-box bg-warning">

            <div class="inner">

                <h3>
                    {{ $totalPending ?? 0 }}
                </h3>

                <p>
                    Menunggu Verifikasi
                </p>

                <small>
                    Pengajuan yang perlu diverifikasi
                </small>

            </div>

            <div class="icon">
                <i class="fas fa-hourglass-half"></i>
            </div>

        </div>

    </div>

    <div class="col-lg-4 col-md-6">

        <div class="small-box bg-info">

            <div class="inner">

                <h3>
                    {{ $totalDiverifikasi ?? 0 }}
                </h3>

                <p>
                    Sudah Diverifikasi
                </p>

                <small>
                    Menunggu proses penarikan oleh Bendahara
                </small>

            </div>

            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>

        </div>

    </div>

    <div class="col-lg-4 col-md-6">

        <div class="small-box bg-danger">

            <div class="inner">

                <h3>
                    {{ $totalDitolak ?? 0 }}
                </h3>

                <p>
                    Pengajuan Ditolak
                </p>

                <small>
                    Tidak memenuhi persyaratan penarikan
                </small>

            </div>

            <div class="icon">
                <i class="fas fa-times-circle"></i>
            </div>

        </div>

    </div>

</div>