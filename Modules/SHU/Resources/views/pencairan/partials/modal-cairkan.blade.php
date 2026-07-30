<div
    class="modal fade"
    id="modalCairkan"
    tabindex="-1"
    role="dialog"
    aria-labelledby="modalCairkanLabel"
    aria-hidden="true">

    <div class="modal-dialog modal-lg">

        <form
            method="POST"
            id="formCairkan"
            enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <div class="modal-content">

                <div class="modal-header bg-success">

                    <h5 class="modal-title">

                        <i class="fas fa-money-check-alt"></i>

                        Pencairan SHU

                    </h5>

                    <button
                        type="button"
                        class="close"
                        data-dismiss="modal">

                        <span>&times;</span>

                    </button>

                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-6">

                            <div class="form-group">

                                <label>Kode Pencairan</label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="kode_pencairan"
                                    readonly>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="form-group">

                                <label>Nama Anggota</label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="nama_anggota"
                                    readonly>

                            </div>

                        </div>

                    </div>


                    <div class="row">

                        <div class="col-md-6">

                            <div class="form-group">

                                <label>Nominal SHU</label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="nominal_pencairan"
                                    readonly>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="form-group">

                                <label>Status</label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="status_pencairan"
                                    readonly>

                            </div>

                        </div>

                    </div>


                    <div class="form-group">

                        <label>

                            Bukti Transfer
                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="file"
                            class="form-control @error('bukti') is-invalid @enderror"
                            name="bukti"
                            accept=".jpg,.jpeg,.png">

                        @error('bukti')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                        <small class="text-muted">

                            Format: JPG, JPEG, PNG.
                            Maksimal 2 MB.

                        </small>

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
                        class="btn btn-success">

                        <i class="fas fa-check-circle"></i>

                        Proses Pencairan

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>