<div class="row">

    <div class="col-lg-4 col-md-6">

        <div class="small-box bg-warning">

            <div class="inner">

                <h3>
                    {{ $totalSiapDicairkan ?? 0 }}
                </h3>

                <p>
                    Siap Dicairkan
                </p>

                <small>
                    Pengajuan yang telah diverifikasi oleh Koordinator
                </small>

            </div>

            <div class="icon">
                <i class="fas fa-money-check-alt"></i>
            </div>

        </div>

    </div>

    <div class="col-lg-4 col-md-6">

        <div class="small-box bg-success">

            <div class="inner">

                <h3>
                    {{ $totalDicairkan ?? 0 }}
                </h3>

                <p>
                    Sudah Dicairkan
                </p>

                <small>
                    Total penarikan yang berhasil dilakukan
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
                    {{ $totalGagal ?? 0 }}
                </h3>

                <p>
                    Penarikan Gagal
                </p>

                <small>
                    Pengajuan yang gagal ditarik
                </small>

            </div>

            <div class="icon">
                <i class="fas fa-times-circle"></i>
            </div>

        </div>

    </div>

</div>