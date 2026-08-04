<div class="modal fade" id="modalTolak">

    <div class="modal-dialog">

        <form
            method="POST"
            id="formTolak">

            @csrf
            @method('PUT')

            <div class="modal-content">

                <div class="modal-header bg-danger">

                    <h5 class="modal-title">

                        Tolak Pengajuan Pencairan

                    </h5>

                    <button
                        type="button"
                        class="close"
                        data-dismiss="modal">

                        <span>&times;</span>

                    </button>

                </div>

                <div class="modal-body">

                    <table class="table table-borderless">

                        <tr>
                            <th width="35%">Kode Pencairan</th>
                            <td id="tolakKode"></td>
                        </tr>

                        <tr>
                            <th>Nama Anggota</th>
                            <td id="tolakNama"></td>
                        </tr>

                        <tr>
                            <th>Nominal</th>
                            <td id="tolakNominal"></td>
                        </tr>

                    </table>

                    <hr>

                    <div class="form-group">

                        <label>

                            Catatan Penolakan

                        </label>

                        <textarea
                            name="catatan"
                            rows="4"
                            class="form-control @error('catatan') is-invalid @enderror"
                            required>{{ old('catatan') }}</textarea>

                        @error('catatan')

                        <span class="invalid-feedback d-block">

                            {{ $message }}

                        </span>

                        @enderror

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal">

                        Batal

                    </button>

                    <button
                        type="submit"
                        class="btn btn-danger">

                        Tolak

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>