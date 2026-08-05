@extends('adminlte::page')

@section('title', 'Rapat Anggota Tahunan')

@section('content')

<section class="content-header">

    <div class="container-fluid">

        @if(session('success'))

            <div class="alert alert-success alert-dismissible">

                <button
                    type="button"
                    class="close"
                    data-dismiss="alert">

                    <span>&times;</span>

                </button>

                {{ session('success') }}

            </div>

        @endif

        @if(session('error'))

            <div class="alert alert-danger alert-dismissible">

                <button
                    type="button"
                    class="close"
                    data-dismiss="alert">

                    <span>&times;</span>

                </button>

                {{ session('error') }}

            </div>

        @endif

        @if($errors->any())

            <div class="alert alert-danger alert-dismissible">

                <button
                    type="button"
                    class="close"
                    data-dismiss="alert">

                    <span>&times;</span>

                </button>

                <strong>Terjadi kesalahan :</strong>

                <ul class="mb-0">

                    @foreach($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif

        <div class="row mb-2">

            <div class="col-sm-6">

                <h1>Rapat Anggota Tahunan</h1>

            </div>

            <div class="col-sm-6">

                <ol class="breadcrumb float-sm-right">

                    <li class="breadcrumb-item">

                        <a href="{{ route('home.index') }}">

                            Dashboard

                        </a>

                    </li>

                    <li class="breadcrumb-item active">

                        Rapat Anggota Tahunan

                    </li>

                </ol>

            </div>

        </div>

    </div>

</section>

<section class="content">

    <div class="container-fluid">

        <div class="card">

            <div class="card-header">

                <a
                    href="{{ route('rat.create') }}"
                    class="btn btn-primary">

                    <i class="fas fa-plus-circle mr-1"></i>

                    Tambah RAT

                </a>

            </div>

            <div class="card-body">

                @include('rat::partials.table')

            </div>

        </div>

    </div>

</section>

@endsection