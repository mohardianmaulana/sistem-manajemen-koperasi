<div class="card">

    <div class="card-header">

        <h3 class="card-title">

            <i class="fas fa-table"></i>

            Data Pencairan SHU

        </h3>

    </div>

    <div class="card-body table-responsive p-0">

        <table class="table table-hover table-bordered">

            <thead class="text-center">

                <tr>

                    <th width="5%">No</th>

                    <th>Kode Pencairan</th>

                    <th>Nama Anggota</th>

                    <th>Periode SHU</th>

                    <th>Nominal SHU</th>

                    <th>Status</th>

                    <th>Dicairkan Oleh</th>

                    <th>Bukti</th>

                    <th width="220">Aksi</th>

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

                        <td>

                            {{ $item->shuAnggota->user->name }}

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

                            {{ optional($item->pencair)->name ?? '-' }}

                        </td>

                        <td class="text-center">

                            @if($item->bukti)

                                <a
                                    href="{{ asset('storage/'.$item->bukti) }}"
                                    target="_blank"
                                    class="btn btn-info btn-sm">

                                    <i class="fas fa-eye"></i>

                                </a>

                            @else

                                -

                            @endif

                        </td>

                        <td class="text-center">

                            @if($item->status == 'siap_dicairkan')

                               <button
                                    type="button"
                                    class="btn btn-success btn-sm btn-cairkan"
                                    data-toggle="modal"
                                    data-target="#modalCairkan"
                                    data-id="{{ $item->id }}"
                                    data-kode="{{ $item->kode_pencairan }}"
                                    data-nama="{{ $item->shuAnggota->user->name }}"
                                    data-nominal="{{ number_format($item->nominal_pencairan,0,',','.') }}"
                                     data-no_rek="{{ $item->shuAnggota->user->no_rek }}"
                                    data-status="{{ $item->status }}">
                                    

                                    <i class="fas fa-check"></i>

                                    Cairkan

                                </button>

                               <button
                                    type="button"
                                    class="btn btn-danger btn-sm btn-gagal"
                                    data-toggle="modal"
                                    data-target="#modalGagal"
                                    data-id="{{ $item->id }}"
                                    data-kode="{{ $item->kode_pencairan }}"
                                    data-nama="{{ $item->shuAnggota->user->name }}"
                                    data-nominal="{{ number_format($item->nominal_pencairan, 0, ',', '.') }}"
                                    data-status="{{ $item->status }}">

                                    <i class="fas fa-times"></i>

                                    Gagal

                                </button>

                            @elseif($item->status == 'dicairkan')

                                <span class="text-success">

                                    <i class="fas fa-check-circle"></i>

                                    Selesai

                                </span>

                            @else

                                <span class="text-danger">

                                    <i class="fas fa-times-circle"></i>

                                    Gagal

                                </span>

                            @endif

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="9" class="text-center">

                            Belum terdapat data pencairan SHU.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    @if(method_exists($data, 'links'))

        <div class="card-footer clearfix">

            {{ $data->links() }}

        </div>

    @endif

</div>