<div class="modal fade" id="modal-detail-gaji">
    <div class="modal-dialog modal-lg " style="border-radius: 20px;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Detail Gaji</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <th width="15%">Nama</th>
                                    <td>: {{ $income->employee->full_name }}</td>
                                </tr>
        
                                <tr>
                                    <th width="15%">Nominal</th>
                                    <td>: Rp. {{ number_format($income->nominal, 0, ',', '.') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-lg-6">
                        <table class="table table-borderless">
                            <tbody>

                                <tr>
                                    <th width="15%">Periode</th>
                                    <td>: {{ $income->period }}</td>
                                </tr>
        
                                <tr>
                                    <th width="15%"">Status</th>
                                    <td>: {{ $income->status }}</td>
                                </tr>
        
                            </tbody>
                        </table>
                    </div>

                </div>
              
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Rincian Gaji</h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                  <th scope="col">Kategori</th>
                                  <th scope="col">Tipe</th>
                                  <th scope="col">Nominal</th>
                                  <th scope="col">Deskripsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($income->incomeDetail as $item)
                                    <tr>
                                        <td>{{ $item->category }}</td>
                                        <td>{{ $item->type }}</td>
                                        <td>Rp. {{ number_format($item->nominal, 0, ',', '.')  }}</td>
                                        <td>{{ $item->desc }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <a href="{{ route('generate-pdf', $income->id) }}" class="btn btn-secondary" target="_blank">Print Slip Gaji</a>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#modal-detail-gaji').modal('show');
    });
</script>
