<div class="modal fade" id="modalVerifikasi">

    <div class="modal-dialog">

        <form
            method="POST"
            id="formVerifikasi">

            @csrf
            @method('PUT')

            <div class="modal-content">

                <div class="modal-header bg-success">

                    <h5 class="modal-title">

                        Verifikasi Penarikan

                    </h5>

                    <button
                        type="button"
                        class="close"
                        data-dismiss="modal">

                        <span>&times;</span>

                    </button>

                </div>

                <div class="modal-body">

                    <p>
                        Apakah Anda yakin ingin memverifikasi pengajuan penarikan ini?
                    </p>

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

                        <i class="fas fa-check"></i>

                        Verifikasi

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>