<div class="modal fade" id="modal-generate-schedule">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Generate Schedule</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="FormGenerateSchedule">
                    @csrf
                    <div class="form-group required">
                        <label for="employee" class="control-label">Departement</label>
                        <select name="departement" id="departement" class="form-control" required>
                            @foreach($departements as $departement)
                                <option value="{{ $departement->uuid }}">{{ $departement->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group required">
                        <label for="shift" class="control-label">Shift</label>
                        <select name="shift" id="shift" class="form-control" required>
                            @foreach($shifts as $shift)
                                <option value="{{ $shift->uuid }}">{{ $shift->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group required">
                        <label for="date" class="control-label">Date</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text">
                                <i class="far fa-calendar-alt"></i>
                              </span>
                            </div>
                            <input type="text" name="date" class="form-control float-right" id="reservation">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="desc" class="control-label">Description</label>
                        <textarea name="desc" id="desc" class="form-control" placeholder="Enter Description"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" id="btn-save-schedule" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        $('#modal-generate-schedule').modal('show');
        $('#reservation').daterangepicker()
    });
</script>

<script>
    $('#btn-save-schedule').click(function(e) {
     e.preventDefault();
     Swal.fire({
         title: "Generate Schedule",
         text: "Are you sure?"
         , icon: 'warning'
         , target: document.getElementById('content')
         , reverseButtons: true
         , confirmButtonText: "Yes"
         , showCancelButton: true
         , cancelButtonText: 'Cancel'
     }).then((result) => {
         if (result.isConfirmed) {
             let act = '{{ route("schedules.generate.store") }}'
             let form_data = new FormData(document.querySelector("#FormGenerateSchedule"));
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
                             $('#modal-generate-schedule').modal('hide');
                            //  $('#example1').DataTable().ajax.reload();
                            window.location.reload();
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

