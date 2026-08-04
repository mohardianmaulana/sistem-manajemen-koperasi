<div class="card">

    <div class="card-header">

        <h3 class="card-title">
            Daftar Pencairan Simpanan
        </h3>

    </div>

    <div class="card-body border-bottom">

    <form
        action="{{ route('pencairan-simpanan.index') }}"
        method="GET">

        <div class="row">

            <div class="col-md-3">

                <label>Status</label>

                <select
                    name="status"
                    class="form-control">

                    <option value="">
                        Semua Status
                    </option>

                    <option
                        value="pending"
                        {{ request('status') == 'pending' ? 'selected' : '' }}>
                        Pending
                    </option>

                    <option
                        value="diverifikasi"
                        {{ request('status') == 'diverifikasi' ? 'selected' : '' }}>
                        Diverifikasi
                    </option>

                    <option
                        value="dicairkan"
                        {{ request('status') == 'dicairkan' ? 'selected' : '' }}>
                        Dicairkan
                    </option>

                    <option
                        value="ditolak"
                        {{ request('status') == 'ditolak' ? 'selected' : '' }}>
                        Ditolak
                    </option>

                    <option
                        value="gagal"
                        {{ request('status') == 'gagal' ? 'selected' : '' }}>
                        Gagal
                    </option>

                </select>

            </div>

            @unlessrole('anggota')

            <div class="col-md-3">

                <label>Nama Anggota</label>

                <input
                    type="text"
                    name="nama"
                    class="form-control"
                    placeholder="Cari nama anggota..."
                    value="{{ request('nama') }}">

            </div>

            @endunlessrole

            <div class="col-md-3">

                <label>Kode Pencairan</label>

                <input
                    type="text"
                    name="kode"
                    class="form-control"
                    placeholder="Cari kode..."
                    value="{{ request('kode') }}">

            </div>

            <div class="col-md-3">

                <label>&nbsp;</label>

                <div>

                    <button
                        type="submit"
                        class="btn btn-primary">

                        <i class="fas fa-search mr-1"></i>

                        Cari

                    </button>

                    <a
                        href="{{ route('pencairan-simpanan.index') }}"
                        class="btn btn-secondary">

                        <i class="fas fa-sync-alt mr-1"></i>

                        Reset

                    </a>

                </div>

            </div>

        </div>

    </form>

</div>

    <div class="card-body table-responsive p-0">

        <table class="table table-hover table-bordered">

            <thead class="text-center">

                <tr>

                    <th width="5%">No</th>

                    <th>Kode</th>

                    @unlessrole('anggota')
                        <th>Nama Anggota</th>
                    @endunlessrole

                    <th>Nominal</th>

                    <th>Status</th>

                    <th>Tanggal Pengajuan</th>

                    <th width="18%">
                        Aksi
                    </th>

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

                    @unlessrole('anggota')

                    <td>
                        {{ $item->anggota->name }}
                    </td>

                    @endunlessrole

                    <td>
                        Rp {{ number_format($item->nominal_pencairan,0,',','.') }}
                    </td>

                    <td class="text-center">

                        @switch($item->status)

                            @case('pending')

                                <span class="badge badge-warning">
                                    Pending
                                </span>

                                @break

                            @case('diverifikasi')

                                <span class="badge badge-info">
                                    Diverifikasi
                                </span>

                                @break

                            @case('dicairkan')

                                <span class="badge badge-success">
                                    Dicairkan
                                </span>

                                @break

                            @case('ditolak')

                                <span class="badge badge-danger">
                                    Ditolak
                                </span>

                                @break

                            @default

                                <span class="badge badge-secondary">
                                    Gagal
                                </span>

                        @endswitch

                    </td>

                    <td>

                        {{ $item->created_at->format('d M Y') }}

                    </td>

                    <td class="text-center">

                        <a
                            href="{{ route('pencairan-simpanan.show',$item->id) }}"
                            class="btn btn-info btn-sm">

                            <i class="fas fa-eye"></i>

                        </a>
                        
                        @role('anggota')

                        @if($item->status == 'pending')

                            <a
                                href="{{ route('pencairan-simpanan.edit', $item->id) }}"
                                class="btn btn-warning btn-sm"
                                title="Edit">

                                <i class="fas fa-edit"></i>

                            </a>

                        @endif

                    @endrole


                        @role('admin')

                            @if($item->status == 'pending')

                                <button
                                    type="button"
                                    class="btn btn-success btn-sm btn-verifikasi"
                                    data-toggle="modal"
                                    data-target="#modalVerifikasi"
                                    data-id="{{ $item->id }}"
                                    data-kode="{{ $item->kode_pencairan }}"
                                    data-nama="{{ $item->anggota->name }}"
                                    data-nominal="{{ number_format($item->nominal_pencairan,0,',','.') }}"
                                    title="Verifikasi">

                                    <i class="fas fa-check"></i>

                                </button>

                              <button
                                    type="button"
                                    class="btn btn-danger btn-sm btn-tolak"
                                    data-toggle="modal"
                                    data-target="#modalTolak"
                                    data-id="{{ $item->id }}"
                                    data-kode="{{ $item->kode_pencairan }}"
                                    data-nama="{{ $item->anggota->name }}"
                                    data-nominal="{{ number_format($item->nominal_pencairan,0,',','.') }}"
                                    title="Tolak">

                                    <i class="fas fa-times"></i>

                                </button>

                            @endif

                        @endrole

                        @role('bendahara')

                            @if($item->status == 'diverifikasi')

                                <button
                                    type="button"
                                    class="btn btn-primary btn-sm btn-cairkan"
                                    data-toggle="modal"
                                    data-target="#modalCairkan"

                                    data-id="{{ $item->id }}"
                                    data-kode="{{ $item->kode_pencairan }}"
                                    data-nama="{{ $item->anggota->name }}"
                                    data-rekening="{{ $item->anggota->no_rek }}"
                                    data-nominal="{{ number_format($item->nominal_pencairan,0,',','.') }}">

                                    <i class="fas fa-money-check-alt"></i>

                                </button>

                               <button
                                    type="button"
                                    class="btn btn-danger btn-sm btn-tolak"
                                    data-toggle="modal"
                                    data-target="#modalTolak"

                                    data-id="{{ $item->id }}"
                                    data-kode="{{ $item->kode_pencairan }}"
                                    data-nama="{{ $item->anggota->name }}"
                                    data-nominal="{{ number_format($item->nominal_pencairan,0,',','.') }}">

                                    <i class="fas fa-times"></i>

                                </button>

                            @endif

                        @endrole

                    </td>

                </tr>

                @empty

                <tr>

                    <td
                        colspan="@role('anggota')6 @else 7 @endrole"
                        class="text-center">

                        Belum ada data pencairan simpanan.

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div class="card-footer clearfix">

        {{ $data->links() }}

    </div>

</div>