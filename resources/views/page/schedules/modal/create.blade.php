<div class="modal fade" id="modal-create-schedule">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Create Schedule</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="FormCreateSchedule">
                    @csrf
                    <div class="form-group required">
                        <label for="employee" class="control-label">Employee</label>
                        <select name="employee" id="employee" class="form-control" required>
                            <option value="">Select Employee</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group required">
                        <label for="shift" class="control-label">Shift</label>
                        <select name="shift" id="shift" class="form-control" required>
                            <option value="">Select Shift</option>
                            @foreach($shifts as $shift)
                                <option value="{{ $shift->id }}">{{ $shift->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group required">
                        <label for="date" class="control-label">Date</label>
                        <input type="date" name="date" id="date" class="form-control" required>
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
        $('#modal-create-schedule').modal('show'); // Show modal when document is ready

        // Handle save button click
        $('#btn-save-schedule').click(function(e) {
            e.preventDefault();
            Swal.fire({
                title: "Create Schedule",
                text: "Are you sure?",
                icon: 'warning',
                reverseButtons: true,
                confirmButtonText: "Yes",
                showCancelButton: true,
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    let form_data = new FormData(document.querySelector("#FormCreateSchedule"));
                    form_data.append('_token', '{{ csrf_token() }}');
                    $.ajax({
                        url: "{{ route('schedules.store') }}",
                        type: "POST",
                        data: form_data,
                        dataType: "json",
                        contentType: false,
                        processData: false,
                        success: function(data) {
                            if (data.status == "success") {
                                Swal.fire({
                                    title: data.msg,
                                    icon: 'success'
                                }).then(function() {
                                    $('#modal-create-schedule').modal('hide');
                                    $('#example1').DataTable().ajax.reload();
                                });
                            } else {
                                let msg = '';
                                if (data.valid && data.valid['employee']) {
                                    msg += '<strong>Employee field is required!</strong><br>';
                                }
                                if (data.valid && data.valid['shift']) {
                                    msg += '<strong>Shift field is required!</strong><br>';
                                }
                                Swal.fire({
                                    title: data.msg,
                                    html: msg,
                                    icon: 'error'
                                });
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            Swal.fire({
                                title: "Error",
                                text: errorThrown,
                                icon: 'error'
                            });
                            console.log(textStatus, errorThrown);
                        }
                    });
                }
            });
        });
    });
</script>
