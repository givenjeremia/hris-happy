@extends('layouts.base')
@section('title', 'Create Client')

@section('toolbar')
    @include('components/toolbar', ['title' => 'Create', 'subtitle' => 'Client'])
@endsection

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Form Create Client</h3>
        </div>
        <form id="formCreate">
            @csrf
            <div class="card-body">
                <div class="form-group mb-2">
                    <img id="preview-logo" src="#" class="img-fluid h-25 w-25  d-none" alt="">
                </div>
                <div class="form-group required">
                    <label for="exampleInputEmail1" class="control-label">Name</label>
                    <input type="text" name="name" class="form-control" id="exampleInputEmail1" placeholder="Write Data">
                </div>
                <div class="form-group required">
                    <label for="exampleInputEmail1" class="control-label">Address</label>
                    <input type="text" name="address" class="form-control" id="exampleInputEmail1" placeholder="Write Data">
                </div>
                <div class="form-group required">
                    <label for="exampleInputEmail1" class="control-label">Email</label>
                    <input type="email" name="email" class="form-control" id="exampleInputEmail1" placeholder="Write Data">
                </div>

                <div class="form-group required">
                    <label for="exampleInputEmail1" class="control-label">Latitude</label>
                    <input type="latitude" name="latitude" class="form-control" id="exampleInputEmail1" placeholder="Write Data">
                </div>

                <div class="form-group required">
                    <label for="exampleInputEmail1" class="control-label">Longitude</label>
                    <input type="longitude" name="longitude" class="form-control" id="exampleInputEmail1" placeholder="Write Data">
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
            title: "Create Client"
            , text: "Are you sure?"
            , icon: 'warning'
            , target: document.getElementById('content')
            , reverseButtons: true
            , confirmButtonText: "Yes"
            , showCancelButton: true
            , cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                let act = '{{ route("clients.store") }}'
                let form_data = new FormData(document.querySelector("#formCreate"));
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
                                window.location.href = "{{ route('clients.index') }}"
                            });

                        } else {
                            var msg = '';
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
@endsection
