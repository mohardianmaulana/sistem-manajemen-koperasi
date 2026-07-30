<div class="row">

    {{-- Total Penerima SHU --}}
    <div class="col-lg-3 col-6">

        <div class="small-box bg-info">

            <div class="inner">
                <h3>{{ number_format($dashboard['total_penerima_shu']) }}</h3>

                <p>Total Penerima SHU</p>
            </div>

            <div class="icon">
                <i class="fas fa-users"></i>
            </div>

        </div>

    </div>

    {{-- Total Nominal SHU --}}
    <div class="col-lg-3 col-6">

        <div class="small-box bg-success">

            <div class="inner">
                <h3>
                    Rp {{ number_format($dashboard['total_nominal_shu'],0,',','.') }}
                </h3>

                <p>Total Nominal SHU</p>
            </div>

            <div class="icon">
                <i class="fas fa-wallet"></i>
            </div>

        </div>

    </div>

    {{-- Total Data Pencairan --}}
    <div class="col-lg-3 col-6">

        <div class="small-box bg-primary">

            <div class="inner">
                <h3>{{ number_format($dashboard['total_pencairan']) }}</h3>

                <p>Total Data Pencairan</p>
            </div>

            <div class="icon">
                <i class="fas fa-file-invoice-dollar"></i>
            </div>

        </div>

    </div>

    {{-- Nominal Sudah Dicairkan --}}
    <div class="col-lg-3 col-6">

        <div class="small-box bg-warning">

            <div class="inner">
                <h3>
                    Rp {{ number_format($dashboard['total_nominal_dicairkan'],0,',','.') }}
                </h3>

                <p>Total Nominal Dicairkan</p>
            </div>

            <div class="icon">
                <i class="fas fa-money-check-alt"></i>
            </div>

        </div>

    </div>

</div>


<div class="row">

    {{-- Siap Dicairkan --}}
    <div class="col-lg-4 col-12">

        <div class="small-box bg-secondary">

            <div class="inner">

                <h3>
                    {{ number_format($dashboard['siap_dicairkan']) }}
                </h3>

                <p>
                    Siap Dicairkan
                </p>

            </div>

            <div class="icon">
                <i class="fas fa-clock"></i>
            </div>

        </div>

    </div>

    {{-- Dicairkan --}}
    <div class="col-lg-4 col-12">

        <div class="small-box bg-success">

            <div class="inner">

                <h3>
                    {{ number_format($dashboard['dicairkan']) }}
                </h3>

                <p>
                    Sudah Dicairkan
                </p>

            </div>

            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>

        </div>

    </div>

    {{-- Gagal --}}
    <div class="col-lg-4 col-12">

        <div class="small-box bg-danger">

            <div class="inner">

                <h3>
                    {{ number_format($dashboard['gagal']) }}
                </h3>

                <p>
                    Gagal Dicairkan
                </p>

            </div>

            <div class="icon">
                <i class="fas fa-times-circle"></i>
            </div>

        </div>

    </div>

</div>