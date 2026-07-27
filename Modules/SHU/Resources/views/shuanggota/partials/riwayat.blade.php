<div class="card">

    <div class="card-header">

        <h3 class="card-title">
            <i class="fas fa-history mr-2"></i>
            Riwayat SHU
        </h3>

    </div>

    <div class="card-body table-responsive p-0">

        <table class="table table-hover table-striped">

            <thead>

                <tr class="text-center">

                    <th width="5%">No</th>
                    <th width="10%">Periode</th>
                    <th width="15%">Jasa Simpanan</th>
                    <th width="15%">Jasa Pinjaman</th>
                    <th width="15%">Pajak SHU</th>
                    <th width="20%">SHU Diterima</th>

                </tr>

            </thead>

            <tbody>

                @forelse($riwayat as $item)

                <tr>

                    <td class="text-center">

                        {{ $loop->iteration + ($riwayat->currentPage() - 1) * $riwayat->perPage() }}

                    </td>

                   <td class="align-middle pl-4">

                        <strong>
                            Periode {{ \Carbon\Carbon::parse($item->periode_awal)->format('Y') }}
                        </strong>

                        <br>

                        <small class="text-muted">

                            {{ \Carbon\Carbon::parse($item->periode_awal)->translatedFormat('d M Y') }}
                            -
                            {{ \Carbon\Carbon::parse($item->periode_akhir)->translatedFormat('d M Y') }}

                        </small>

                    </td>
                    
                  <td class="text-center align-middle">

                        <span class="text-info font-weight-bold">

                            Rp {{ number_format($item->shu_simpanan,0,',','.') }}

                        </span>

                    </td>

                    <td class="text-center align-middle">

                        <span class="text-primary font-weight-bold">

                            Rp {{ number_format($item->shu_pinjaman,0,',','.') }}

                        </span>

                    </td>

                    <td class="text-center align-middle">

                        <span class="text-warning font-weight-bold">

                            Rp {{ number_format($item->pajak,0,',','.') }}

                        </span>

                    </td>

                    <td class="text-center align-middle">

                        <span class="badge badge-success p-2">

                            Rp {{ number_format($item->shu_anggota,0,',','.') }}

                        </span>

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="6" class="text-center py-4">

                        <i class="fas fa-folder-open fa-2x text-secondary mb-2"></i>

                        <br>

                        <span class="text-muted">

                            Belum terdapat riwayat SHU.

                        </span>

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    @if($riwayat->hasPages())

    <div class="card-footer clearfix">

        <div class="float-right">

            {{ $riwayat->links() }}

        </div>

    </div>

    @endif

</div>