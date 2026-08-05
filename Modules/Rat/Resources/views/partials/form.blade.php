<div class="card-body">

    <div class="form-group">

        <label>

            Tahun

        </label>

        <input
            type="number"
            name="tahun"
            class="form-control @error('tahun') is-invalid @enderror"
            value="{{ old('tahun', $data->tahun ?? now()->year) }}"
            min="2000"
            max="2100"
            {{ isset($data) ? 'readonly' : '' }}
            required>

        @error('tahun')

            <span class="invalid-feedback">

                {{ $message }}

            </span>

        @enderror

    </div>

    <div class="form-group">

        <label>

            Tanggal RAT

        </label>

        <input
            type="date"
            name="tanggal_rat"
            class="form-control @error('tanggal_rat') is-invalid @enderror"
            value="{{ old(
                'tanggal_rat',
                isset($data)
                    ? $data->tanggal_rat->format('Y-m-d')
                    : ''
            ) }}"
            required>

        @error('tanggal_rat')

            <span class="invalid-feedback">

                {{ $message }}

            </span>

        @enderror

    </div>

    <div class="form-group">

        <label>

            Status

        </label>

        <select
            name="status"
            class="form-control @error('status') is-invalid @enderror"
            required>

            <option value="">

                -- Pilih Status --

            </option>

            <option
                value="belum"
                {{
                    old(
                        'status',
                        $data->status ?? 'belum'
                    ) == 'belum'
                    ? 'selected'
                    : ''
                }}>

                Belum

            </option>

            <option
                value="selesai"
                {{
                    old(
                        'status',
                        $data->status ?? ''
                    ) == 'selesai'
                    ? 'selected'
                    : ''
                }}>

                Selesai

            </option>

        </select>

        @error('status')

            <span class="invalid-feedback">

                {{ $message }}

            </span>

        @enderror

    </div>

</div>

<div class="card-footer">

    <a
        href="{{ route('rat.index') }}"
        class="btn btn-secondary">

        Kembali

    </a>

    <button
        type="submit"
        class="btn btn-primary float-right">

        {{ isset($data) ? 'Perbarui' : 'Simpan' }}

    </button>

</div>