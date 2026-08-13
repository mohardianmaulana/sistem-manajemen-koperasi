<div class="card">

    <div class="card-header">

        <h3 class="card-title">

            <i class="fas fa-history"></i>

            Riwayat Penyaluran SHU

        </h3>

    </div>

    <div class="card-body table-responsive p-0">

        <table class="table table-hover table-bordered">

            <thead class="text-center">

                <tr>

                    <th width="5%">No</th>

                    <th>Kode Penyaluran</th>

                    <th>Periode SHU</th>

                    <th>Nominal SHU</th>

                    <th>Tanggal Penyaluran</th>

                    <th>Status</th>

                    <th>Bukti</th>

                    <th width="10%">Aksi</th>

                </tr>

            </thead>

            <tbody>

                @forelse($data as $item)

                    <tr>

                        <td class="text-center">

                            {{ $loop->iteration }}

                        </td>

                        <td>

                            {{ $item->kode_pencairan }}

                        </td>

                        <td class="text-center">

                            {{ \Carbon\Carbon::parse($item->shuAnggota->periode_awal)->format('d M Y') }}
                            <br>
                            s/d
                            <br>
                            {{ \Carbon\Carbon::parse($item->shuAnggota->periode_akhir)->format('d M Y') }}

                        </td>

                        <td class="text-right">

                            Rp {{ number_format($item->nominal_pencairan,0,',','.') }}

                        </td>

                        <td class="text-center">

                            {{ \Carbon\Carbon::parse($item->tanggal_pencairan)->format('d-m-Y') }}

                        </td>

                        <td class="text-center">

                            @if($item->status == 'siap_dicairkan')

                                <span class="badge badge-warning">

                                    Siap Dicairkan

                                </span>

                            @elseif($item->status == 'dicairkan')

                                <span class="badge badge-success">

                                    Dicairkan

                                </span>

                            @else

                                <span class="badge badge-danger">

                                    Gagal

                                </span>

                            @endif

                        </td>

                        <td class="text-center">

                            @if($item->bukti)

                                <a
                                    href="{{ asset('storage/'.$item->bukti) }}"
                                    target="_blank"
                                    class="btn btn-info btn-sm">

                                    <i class="fas fa-eye"></i>

                                    Lihat

                                </a>

                            @else

                                -

                            @endif

                        </td>

                        <td class="text-center">

                        <a
                            href="{{ route('pencairan.show', $item->id) }}"
                            class="btn btn-primary btn-sm">

                            <i class="fas fa-eye"></i>

                            Detail

                        </a>

                    </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="7" class="text-center">

                            Belum terdapat data pencairan SHU.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    @if(method_exists($data,'links'))

        <div class="card-footer clearfix">

            {{ $data->links() }}

        </div>

    @endif

</div>