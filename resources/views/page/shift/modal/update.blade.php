<div class="modal fade" id="modal-update">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update Posision</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="FormUpdate">
                    @csrf
                    @method('put')
                    <div class="form-group required ">
                        <label for="exampleInputEmail1" class="control-label">Name</label>
                        <input type="text" name="name" value="{{ $shift->name }}" class="form-control" id="exampleInputEmail1" placeholder="Write Data">
                    </div>

                    <div class="form-group required ">
                        <label for="exampleInputEmail1" class="control-label">Time In</label>
                        <input type="time" name="time_in" value="{{ $shift->time_in }}" class="form-control" id="exampleInputEmail1" placeholder="Write Data">
                    </div>

                    <div class="form-group required ">
                        <label for="exampleInputEmail1" class="control-label">Time Out</label>
                        <input type="time" name="time_out" value="{{ $shift->time_out }}" class="form-control" id="exampleInputEmail1" placeholder="Write Data">
                    </div>

                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" id="btn-simpan" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#modal-update').modal('show');
    });
</script>

<script>
       $('#btn-simpan').click(function(e) {
        e.preventDefault();
        Swal.fire({
            title: "Update Posision",
            text: "Are you sure?"
            , icon: 'warning'
            , target: document.getElementById('content')
            , reverseButtons: true
            , confirmButtonText: "Yes"
            , showCancelButton: true
            , cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                let act = '{{ route("shifts.update", ":uuid") }}'.replace(':uuid','{{ $shift->uuid }}')
                let form_data = new FormData(document.querySelector("#FormUpdate"));
                form_data.append('_token', '{{ csrf_token() }}')
                $.ajax({
                    url: act
                    , type: "POST"
                    , data: form_data
                    , dataType: "json"
                    , contentType: false
                    , processData: false
                    , success: function(data) {

                        if (data.status == "success") {
                            Swal.fire({
                                title: data.msg
                                , icon:'success'
                            }).then(function(result) {
                                $('#modal-update').modal('hide');
                                $('#example1').DataTable().ajax.reload();
                            });

                        } else {
                            var msg = '';
                            if(data.valid['name']){
                                msg += '<strong>Nama Wajib Diisi!</strong><br>';
                            }
                            Swal.fire({
                                title:  data.msg,
                                html: msg,
                                icon:'error'
                            })
                        
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
            
                        Swal.fire({
                            title:  textStatus,
                            text: errorThrown,
                            icon:'error', 
                        })
                        console.log(textStatus, errorThrown);
                    }

                })

            }
        })
    })
</script>
