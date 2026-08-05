<div class="table-responsive">

    <table class="table table-bordered table-hover">

        <thead class="text-center">

            <tr>

                <th width="5%">
                    No
                </th>

                <th>
                    Tahun
                </th>

                <th>
                    Tanggal RAT
                </th>

                <th>
                    Status
                </th>

                <th width="15%">
                    Aksi
                </th>

            </tr>

        </thead>

        <tbody>

            @forelse($data as $item)

                <tr>

                    <td class="text-center">

                        {{ $loop->iteration + ($data->currentPage() - 1) * $data->perPage() }}

                    </td>

                    <td class="text-center">

                        {{ $item->tahun }}

                    </td>

                    <td class="text-center">

                        {{ $item->tanggal_rat->translatedFormat('d F Y') }}

                    </td>

                    <td class="text-center">

                        @if($item->status == 'belum')

                            <span class="badge badge-warning">

                                Belum

                            </span>

                        @elseif($item->status == 'selesai')

                            <span class="badge badge-success">

                                Selesai

                            </span>

                        @endif

                    </td>

                    <td class="text-center">

                        <a
                            href="{{ route('rat.edit', $item->id) }}"
                            class="btn btn-warning btn-sm"
                            title="Ubah">

                            <i class="fas fa-edit"></i>

                        </a>

                    </td>

                </tr>

            @empty

                <tr>

                    <td
                        colspan="5"
                        class="text-center">

                        Data RAT belum tersedia.

                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>

</div>

<div class="mt-3 d-flex justify-content-end">

    {{ $data->links() }}

</div>