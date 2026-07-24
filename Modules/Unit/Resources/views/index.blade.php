@extends('adminlte::page')

@section('title', 'Data Unit')

@section('content')
<div class="container-fluid">

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card">

        <div class="card-header">
            <div class="row align-items-center">

                <div class="col-md-8">

                    <form action="{{ route('unit.index') }}" method="GET">

                        <div class="input-group">

                            <input
                                type="text"
                                name="search"
                                class="form-control"
                                placeholder="Cari nama unit..."
                                value="{{ request('search') }}">

                            <div class="input-group-append">

                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Cari
                                </button>

                                @if(request()->filled('search'))
                                    <a href="{{ route('unit.index') }}"
                                       class="btn btn-secondary">
                                        Reset
                                    </a>
                                @endif

                            </div>

                        </div>

                    </form>

                </div>

                <div class="col-md-4 text-right">

                    <a href="{{ route('unit.create') }}" class="btn btn-success">
                        <i class="fas fa-plus"></i> Tambah Unit
                    </a>

                </div>

            </div>
        </div>

        <div class="card-body">

            <table class="table table-bordered table-striped">

                <thead>
                    <tr>
                        <th width="10%">No</th>
                        <th>Nama Unit</th>
                        <th width="20%">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($units as $key => $unit)

                        <tr>

                            <td>{{ $units->firstItem() + $key }}</td>

                            <td>{{ $unit->nama }}</td>

                            <td>

                                <a href="{{ route('unit.edit', $unit->id) }}"
                                   class="btn btn-warning btn-sm">
                                    Edit
                                </a>

                                <form action="{{ route('unit.destroy', $unit->id) }}"
                                      method="POST"
                                      style="display:inline">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Yakin ingin menghapus data ini?')">

                                        Hapus

                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="3" class="text-center">
                                Data tidak tersedia.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

            <div class="mt-3 d-flex justify-content-center">
                {{ $units->links() }}
            </div>

        </div>

    </div>

</div>
@endsection