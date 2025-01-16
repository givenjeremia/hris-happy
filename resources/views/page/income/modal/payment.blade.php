<div class="modal fade" id="modal-update-gaji">
    <div class="modal-dialog modal-lg " style="border-radius: 20px;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Payment Status</h4>
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
              
                

                <form id="FormUpdateGaji">
                    @csrf
                    @method('put')
                    <div class="form-group required ">
                        <label for="exampleInputEmail1" class="control-label">Status</label>
                        <select name="status" id="employee" class="form-control">
                            <option value="NO_PAYMENT" {{ $income->status  == 'NO_PAYMENT' ? 'selected' : '' }} >NO PAYMENT</option>
                            <option value="PAYMENT" {{ $income->status  == 'PAYMENT' ? 'selected' : '' }} >PAYMENT</option>
                        </select>
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
        $('#modal-update-gaji').modal('show');
    });


</script>


<script>
    $('#btn-simpan').click(function(e) {
     e.preventDefault();
     Swal.fire({
         title: "Update Status",
         text: "Are you sure?"
         , icon: 'warning'
         , target: document.getElementById('content')
         , reverseButtons: true
         , confirmButtonText: "Yes"
         , showCancelButton: true
         , cancelButtonText: 'Cancel'
     }).then((result) => {
         if (result.isConfirmed) {
             let act = '{{ route("income.update", ":uuid") }}'.replace(':uuid','{{ $income->uuid }}')
             let form_data = new FormData(document.querySelector("#FormUpdateGaji"));
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
                             $('#modal-update-gaji').modal('hide');
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