<div class="card">

    <div class="card-header">

        <h3 class="card-title">
            <i class="fas fa-filter"></i>
            Filter Data Penyaluran SHU
        </h3>

    </div>

    <div class="card-body">

        <div class="row">

            {{-- Form Filter --}}
            <div class="col-md-8">

                <form
                    method="GET"
                    action="{{ route('pencairan.index') }}">

                    <div class="form-row">

                        {{-- Tahun SHU --}}
                        <div class="col-md-4">

                            <label>Tahun SHU</label>

                            <select
                                name="tahun"
                                class="form-control">

                                @foreach($listTahun as $item)

                                    <option
                                        value="{{ $item }}"
                                        {{ $tahun == $item ? 'selected' : '' }}>

                                        {{ $item }}

                                    </option>

                                @endforeach

                            </select>

                        </div>

                        {{-- Status --}}
                        <div class="col-md-4">

                            <label>Status</label>

                            <select
                                name="status"
                                class="form-control">

                                <option value="">
                                    Semua Status
                                </option>

                                <option
                                    value="siap_dicairkan"
                                    {{ request('status') == 'siap_dicairkan' ? 'selected' : '' }}>

                                    Siap Dicairkan

                                </option>

                                <option
                                    value="dicairkan"
                                    {{ request('status') == 'dicairkan' ? 'selected' : '' }}>

                                    Dicairkan

                                </option>

                                <option
                                    value="gagal"
                                    {{ request('status') == 'gagal' ? 'selected' : '' }}>

                                    Gagal

                                </option>

                            </select>

                        </div>

                        {{-- Tombol Filter --}}
                        <div class="col-md-4 d-flex align-items-end">

                            <button
                                type="submit"
                                class="btn btn-primary mr-2">

                                <i class="fas fa-search"></i>
                                Filter

                            </button>

                            <a
                                href="{{ route('pencairan.index') }}"
                                class="btn btn-secondary">

                                <i class="fas fa-sync"></i>
                                Reset

                            </a>

                        </div>

                    </div>

                </form>

            </div>

            {{-- Tombol Aksi --}}
            <div class="col-md-4 d-flex justify-content-end align-items-end">

                {{-- Generate Data --}}
                <form
                    action="{{ route('pencairan.store') }}"
                    method="POST"
                    class="mb-0">

                    @csrf

                    <input
                        type="hidden"
                        name="tahun"
                        value="{{ $tahun }}">

                    <button
                        type="submit"
                        class="btn btn-success"
                        onclick="return confirm('Generate data pencairan SHU tahun {{ $tahun }}?')">

                        <i class="fas fa-plus-circle"></i>
                        Generate Data

                    </button>

                </form>

                {{-- Cairkan Semua --}}
                <form
                    action="{{ route('shu.pencairan.cairkan-semua') }}"
                    method="POST"
                    class="mb-0 ml-2">

                    @csrf

                    <button
                        type="submit"
                        class="btn btn-success"
                        onclick="return confirm('Apakah Anda yakin ingin mencairkan semua SHU yang siap dicairkan?')">

                        <i class="fas fa-check-double"></i>
                        Cairkan Semua

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>