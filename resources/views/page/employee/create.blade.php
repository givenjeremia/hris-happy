@extends('layouts.base')
@section('title', 'Create Employee')

@section('toolbar')
    @include('components/toolbar', ['title' => 'Create', 'subtitle' => 'Employee'])
@endsection

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Form Create Employee</h3>
        </div>
        <form id="formCreate">
            @csrf
            <div class="card-body">

                <div class="form-group required">
                    <label for="exampleInputEmail1" class="control-label">Client</label>
                    <select name="client" class="form-control">
                        @foreach ($clients as $item)
                            <option value="{{ $item->uuid }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group required">
                    <label for="exampleInputEmail1" class="control-label">Posision</label>
                    <select name="client" class="form-control">
                        @foreach ($posisions as $item)
                            <option value="{{ $item->uuid }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group required">
                    <label for="exampleInputEmail1" class="control-label">Full Name</label>
                    <input type="text" name="full_name" class="form-control" id="exampleInputEmail1"
                        placeholder="Write Data">
                </div>
                <div class="form-group required">
                    <label for="exampleInputEmail1" class="control-label">NIK</label>
                    <input type="text" name="nik" class="form-control" id="exampleInputEmail1"
                        placeholder="Write Data">
                </div>

                <div class="form-group required">
                    <label for="exampleInputEmail1" class="control-label">Date Of Birth</label>
                    <input type="date" name="date_of_birth" class="form-control" id="exampleInputEmail1"
                        placeholder="Write Data">
                </div>

                <div class="form-group required">
                    <label for="exampleInputEmail1" class="control-label">Address</label>
                    <textarea name="description" class="form-control"></textarea>
                </div>

                <div class="form-group required">
                    <label for="exampleInputEmail1" class="control-label">Bank Account Name</label>
                    <input type="text" name="bank_account_name" class="form-control" id="exampleInputEmail1"
                    placeholder="Write Data">
                </div>

                <div class="form-group required">
                    <label for="exampleInputEmail1" class="control-label">Bank Account Number</label>
                    <input type="text" name="bank_account_number" class="form-control" id="exampleInputEmail1"
                    placeholder="Write Data">
                </div>

                <div class="form-group required">
                    <label for="exampleInputEmail1" class="control-label">Phone Number</label>
                    <input type="text" name="phone_number" class="form-control" id="exampleInputEmail1"
                    placeholder="Write Data">
                </div>

                <div class="form-group required">
                    <label for="exampleInputEmail1" class="control-label">Code PTKP</label>
                    <input type="text" name="code_ptkp" class="form-control" id="exampleInputEmail1"
                    placeholder="Write Data">
                </div>



            </div>

            <div class="card-footer">
                <button type="submit" id="btn-simpan" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>

@endsection

@section('scripts')
    <script>
        $('#btn-simpan').click(function(e) {
            e.preventDefault();
            Swal.fire({
                title: "Create Employee",
                text: "Are you sure?",
                icon: 'warning',
                target: document.getElementById('content'),
                reverseButtons: true,
                confirmButtonText: "Yes",
                showCancelButton: true,
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    let act = '{{ route('employee.store') }}'
                    let form_data = new FormData(document.querySelector("#formCreate"));
                    form_data.append('_token', '{{ csrf_token() }}')
                    $.ajax({
                        url: act,
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
                                }).then(function(result) {
                                    window.location.href =
                                        "{{ route('contracts.index') }}"
                                });

                            } else {
                                var msg = '';
                                Swal.fire({
                                    title: data.msg,
                                    html: msg,
                                    icon: 'error'
                                })
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            Swal.fire({
                                title: textStatus,
                                text: errorThrown,
                                icon: 'error',
                            })
                            console.log(textStatus, errorThrown);
                        }
                    })

                }
            })
        })
    </script>
@endsection
