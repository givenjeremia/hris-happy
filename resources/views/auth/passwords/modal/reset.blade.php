<div class="modal fade" id="modal-update-password">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update Password</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="FormUpdatePassword">
                    @csrf
                    <div class="form-group required ">
                        <label for="exampleInputEmail1" class="control-label">Current Password</label>
                        <input type="password" name="current_password" class="form-control" id="exampleInputEmail1" placeholder="Write Data">
                    </div>
                    <div class="form-group required ">
                        <label for="exampleInputEmail1" class="control-label">New Password</label>
                        <input type="password" name="new_password" class="form-control" id="exampleInputEmail1" placeholder="Write Data">
                    </div>
                    <div class="form-group required ">
                        <label for="exampleInputEmail1" class="control-label">Confirm Password</label>
                        <input type="password" name="new_password_confirmation" class="form-control" id="exampleInputEmail1" placeholder="Write Data">
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
        $('#modal-update-password').modal('show');
    });
</script>

<script>
       $('#btn-simpan').click(function(e) {
        e.preventDefault();
        Swal.fire({
            title: "Update Password",
            text: "Are you sure?"
            , icon: 'warning'
            , target: document.getElementById('content')
            , reverseButtons: true
            , confirmButtonText: "Yes"
            , showCancelButton: true
            , cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                let act = '{{ route("password.update") }}'
                let form_data = new FormData(document.querySelector("#FormUpdatePassword"));
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
                                window.location.reload();
                            });

                        } else {

                            Swal.fire({
                                title:  data.msg,
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
