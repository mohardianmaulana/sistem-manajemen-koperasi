@extends('adminlte::page')

@section('title','Edit Unit')

@section('content')

<div class="container-fluid">

    <div class="card">

        <div class="card-header">
            <h4>Edit Unit</h4>
        </div>

        <form action="{{ route('unit.update',$unit->id) }}" method="POST">

            @csrf
            @method('PUT')

            <div class="card-body">

                <div class="form-group">

                    <label>Nama Unit</label>

                    <input
                        type="text"
                        name="nama"
                        class="form-control @error('nama') is-invalid @enderror"
                        value="{{ old('nama',$unit->nama) }}">

                    @error('nama')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>

            <div class="card-footer">

                <button class="btn btn-primary">
                    Update
                </button>

                <a href="{{ route('unit.index') }}"
                   class="btn btn-secondary">

                    Kembali

                </a>

            </div>

        </form>

    </div>

</div>

@endsection