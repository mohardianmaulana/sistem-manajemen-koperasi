<div class="modal fade" id="modalCairkan">

    <div class="modal-dialog">

        <form
            method="POST"
            enctype="multipart/form-data"
            id="formCairkan">

            @csrf
            @method('PUT')

            <div class="modal-content">

                <div class="modal-header bg-primary">

                    <h5 class="modal-title">

                        Pencairan Simpanan

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
                            <td id="modalKode"></td>
                        </tr>

                        <tr>
                            <th>Nama Anggota</th>
                            <td id="modalNama"></td>
                        </tr>

                        <tr>
                            <th>No. Rekening</th>
                            <td id="modalRekening"></td>
                        </tr>

                        <tr>
                            <th>Nominal</th>
                            <td id="modalNominal"></td>
                        </tr>

                    </table>

                    <hr>

                    <div class="form-group">

                        <label>

                            Bukti Transfer

                        </label>

                        <input
                            type="file"
                            name="bukti_transfer"
                            class="form-control"
                            required>

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
                        class="btn btn-primary">

                        <i class="fas fa-money-check-alt mr-1"></i>

                        Cairkan

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>