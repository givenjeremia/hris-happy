<div class="modal fade" id="modal-create">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Create Posision</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="FormCreate">
                    @csrf
                    <div class="form-group required ">
                        <label for="exampleInputEmail1" class="control-label">Departement</label>
                        <select name="departement" class="form-control" id="">
                            @foreach ($departements as $item)
                                <option value="{{ $item->uuid }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group required ">
                        <label for="exampleInputEmail1" class="control-label">Name</label>
                        <input type="text" name="name" class="form-control" id="exampleInputEmail1" placeholder="Write Data">
                    </div>
                    <div class="form-group required ">
                        <label for="exampleInputEmail1" class="control-label">Salary</label>
                        <input type="text" name="salary" class="form-control" id="exampleInputEmail1" placeholder="Write Data">
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
        $('#modal-create').modal('show');
    });
</script>

<script>
       $('#btn-simpan').click(function(e) {
        e.preventDefault();
        Swal.fire({
            title: "Create Posision",
            text: "Are you sure?"
            , icon: 'warning'
            , target: document.getElementById('content')
            , reverseButtons: true
            , confirmButtonText: "Yes"
            , showCancelButton: true
            , cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                let act = '{{ route("posisions.store") }}'
                let form_data = new FormData(document.querySelector("#FormCreate"));
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
                                $('#modal-create').modal('hide');
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
