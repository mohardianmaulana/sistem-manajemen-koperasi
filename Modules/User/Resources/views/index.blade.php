@extends('adminlte::page')

@section('title', 'Pendaftaran Anggota')

@section('content_header')
    <h1 class="m-0 text-dark">
        Pendaftaran Anggota
    </h1>
@stop

@section('content')

<div class="row">

    <div class="col-lg-4 col-md-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $summary['totalUser'] }}</h3>
                <p>Total Pendaftar</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $summary['pendingUser'] }}</h3>
                <p>Menunggu Verifikasi</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-clock"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $summary['activeUser'] }}</h3>
                <p>Anggota Aktif</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-check"></i>
            </div>
        </div>
    </div>

</div>

<div class="card">

    <div class="card-header d-flex justify-content-between align-items-center">

        <h5 class="mb-0">
            Daftar Pendaftaran Anggota
        </h5>

    </div>

    <div class="card-body">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">

                {{ session('success') }}

                <button
                    type="button"
                    class="close"
                    data-dismiss="alert">

                    <span>&times;</span>

                </button>

            </div>
        @endif

        <div class="table-responsive">

            <table class="table table-bordered table-hover">

                <thead class="text-center">

                    <tr>

                        <th width="5%">No</th>
                        <th>Nama</th>
                        <th>NIP</th>
                        <th>Unit</th>
                        <th>No HP</th>
                        <th>Status</th>
                        <th width="18%">Aksi</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($users as $item)

                        <tr>

                            <td class="text-center">
                                {{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}
                            </td>

                            <td>
                                {{ $item->name }}
                            </td>

                            <td>
                                {{ $item->nip ?? '-' }}
                            </td>

                            <td>
                                {{ optional($item->getUnit)->nama ?? '-' }}
                            </td>

                            <td>
                                {{ $item->no_hp ?? '-' }}
                            </td>

                            <td class="text-center">

                                @if(empty($item->username) || empty($item->email))

                                    <span class="badge badge-warning">
                                        <i class="fas fa-clock"></i>
                                        Menunggu Verifikasi
                                    </span>

                                @else

                                    <span class="badge badge-success">
                                        <i class="fas fa-check-circle"></i>
                                        Aktif
                                    </span>

                                @endif

                            </td>

                            <td class="text-center">

                                <a
                                    href="{{ route('user.edit', $item->id) }}"
                                    class="btn btn-warning btn-sm"
                                    title="Verifikasi / Edit">

                                    <i class="fas fa-user-check"></i>

                                </a>

                                <form
                                    action="{{ route('user.destroy', $item->id) }}"
                                    method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('Yakin ingin menghapus data ini?')">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-danger btn-sm"
                                        title="Hapus">

                                        <i class="fas fa-trash"></i>

                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="7" class="text-center text-muted">
                                Belum ada data pendaftaran anggota.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        @if($users->hasPages())

            <div class="mt-3 d-flex justify-content-center">
                {{ $users->links() }}
            </div>

        @endif

    </div>

</div>

@stop