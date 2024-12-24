<div class="modal fade" id="modal-tambah">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Detail Scheduler <strong>{{ \Carbon\Carbon::parse($date)->isoFormat('dddd, D MMMM Y') }}</strong></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Pegawai</th>
                            <th scope="col">Shift</th>
                            <th scope="col">Desc</th>
                            <th scope="col">Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as  $key => $item)
                        <tr>
                            <th scope="row">{{ $key+1 }}</th>
                            <td>{{ $item->employee->full_name }}</td>
                            <td>{{ $item->shift->name }}</td>
                            <td>{{ $item->desc }}</td>
            
                            <td>{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y, H:i') }}</td>
                        </tr>

                        @endforeach

                    </tbody>
                </table>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
    $(document).ready(function() {
        $('#modal-tambah').modal('show');
    });
</script>