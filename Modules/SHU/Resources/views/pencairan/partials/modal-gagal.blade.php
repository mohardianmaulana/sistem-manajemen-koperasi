<div
    class="modal fade"
    id="modalGagal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="modalGagalLabel"
    aria-hidden="true">

    <div class="modal-dialog">

        <form
            method="POST"
            id="formGagal">

            @csrf
            @method('PUT')

            <div class="modal-content">

                <div class="modal-header bg-danger">

                    <h5 class="modal-title">

                        <i class="fas fa-times-circle"></i>

                        Tandai Penyaluran Gagal

                    </h5>

                    <button
                        type="button"
                        class="close"
                        data-dismiss="modal">

                        <span>&times;</span>

                    </button>

                </div>

                <div class="modal-body">

                    <div class="alert alert-warning">

                        <i class="fas fa-exclamation-triangle"></i>

                        Berikan alasan mengapa penyaluran SHU gagal dilakukan.

                    </div>

                    <div class="form-group">

                        <label>

                            Kode Penyaluran

                        </label>

                        <input
                            type="text"
                            id="gagal_kode_pencairan"
                            class="form-control"
                            readonly>

                    </div>

                    <div class="form-group">

                        <label>

                            Nama Anggota

                        </label>

                        <input
                            type="text"
                            id="gagal_nama_anggota"
                            class="form-control"
                            readonly>

                    </div>

                    <div class="form-group">

                        <label>

                            Nominal SHU

                        </label>

                        <input
                            type="text"
                            id="gagal_nominal"
                            class="form-control"
                            readonly>

                    </div>

                    <div class="form-group">

                        <label>

                            Keterangan
                            <span class="text-danger">*</span>

                        </label>

                        <textarea
                            name="keterangan"
                            rows="4"
                            class="form-control @error('keterangan') is-invalid @enderror"
                            placeholder="Masukkan alasan pencairan gagal..."></textarea>

                        @error('keterangan')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

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

                        <i class="fas fa-times-circle"></i>

                        Tandai Gagal

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>