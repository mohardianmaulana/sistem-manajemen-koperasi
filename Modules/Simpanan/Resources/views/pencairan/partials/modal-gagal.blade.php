<div class="modal fade" id="modalGagal">

    <div class="modal-dialog">

        <form
            method="POST"
            id="formGagal">

            @csrf
            @method('PUT')

            <div class="modal-content">

                <div class="modal-header bg-secondary">

                    <h5 class="modal-title">

                        Penarikan Gagal

                    </h5>

                    <button
                        type="button"
                        class="close"
                        data-dismiss="modal">

                        <span>&times;</span>

                    </button>

                </div>

                <div class="modal-body">

                    <div class="form-group">

                        <label>

                            Alasan Kegagalan

                        </label>

                        <textarea
                            name="catatan"
                            class="form-control"
                            rows="4"
                            required></textarea>

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
                        class="btn btn-secondary">

                        Simpan

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>