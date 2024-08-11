@extends('layouts.base')
@section('title', 'Create Contract')

@section('toolbar')
    @include('components/toolbar', ['title' => 'Create', 'subtitle' => 'Contract'])
@endsection

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Form Create Contract</h3>
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
                    <label for="exampleInputEmail1" class="control-label">Start Date</label>
                    <input type="date" name="start_date" class="form-control" id="exampleInputEmail1"
                        placeholder="Write Data">
                </div>
                <div class="form-group required">
                    <label for="exampleInputEmail1" class="control-label">End Date</label>
                    <input type="date" name="end_date" class="form-control" id="exampleInputEmail1"
                        placeholder="Write Data">
                </div>

                <div class="form-group required">
                    <label for="exampleInputEmail1" class="control-label">Description</label>
                    <textarea name="description" class="form-control"></textarea>
                </div>

                <div class="form-group required">
                    <label for="exampleInputEmail1" class="control-label">Document</label>
                    <input type="file" name="document" class="form-control" accept="application/pdf" onchange="loadFilePdf(event)">
                </div>
                <div class="form-group ">
                    <iframe id="pdf-preview" class="mb-2 w-100 d-none" style="height: 500px;" src="" frameborder="0"></iframe>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" id="btn-simpan" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>

@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>
    <script>
        function loadFilePdf(event) {
            const input = event.target;
            const file = input.files[0];

            if (file) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    const pdfData = new Uint8Array(e.target.result);
                    pdfjsLib.getDocument({
                        data: pdfData
                    }).promise.then(pdfDoc => {
                        pdfDoc.getPage(1).then(page => {
                            const pdfPreview = document.getElementById('pdf-preview');
                            pdfPreview.src = URL.createObjectURL(new Blob([pdfData], {
                                type: 'application/pdf'
                            }));
                            pdfPreview.classList.remove('d-none');
                            document.getElementById('pdf-preview-canvas').classList.add('d-none');
                        });
                    });
                };

                reader.readAsArrayBuffer(file);
            } else {
                $('#pdf-preview').addClass('d-none');
                const pdfPreview = document.getElementById('pdf-preview');
                pdfPreview.src = '#';
            }
        }
    </script>
    <script>
        $('#btn-simpan').click(function(e) {
            e.preventDefault();
            Swal.fire({
                title: "Create Contract",
                text: "Are you sure?",
                icon: 'warning',
                target: document.getElementById('content'),
                reverseButtons: true,
                confirmButtonText: "Yes",
                showCancelButton: true,
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    let act = '{{ route('contracts.store') }}'
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
