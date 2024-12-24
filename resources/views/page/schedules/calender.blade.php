@extends('layouts.base')
@section('title', 'Schedules')

@section('toolbar')
    @include('components.toolbar', ['title' => 'Schedules', 'subtitle' => 'Calender'])
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets_lte/plugins/fullcalendar/main.css') }}">
    <style>
        .fc-event {
            cursor: pointer; /* Change cursor to pointer */
        }


    </style>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Calender</h3>
        @if (auth()->user()->roles->pluck('name')[0] == 'admin')
            <div class="card-tools">
                <button onclick="generateForm()" type="button" class="btn btn-secondary mx-3">Generate Schedule</button>
                <button id="createScheduleBtn" type="button" class="btn btn-primary">Add Schedule</button>
            </div>
        @endif
    </div>
    <div class="card-body ">
        <div id="calendar" class="w-auto"></div>
    </div>
</div>
<div id="modal-div"></div>
@endsection

@section('scripts')

<script src="{{ asset('assets_lte/calender.min.global.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                timeZone: 'local',
                eventClick: function(info) {
             
                    console.log(info)
                    console.log(formatDate(info.event.startStr));
                    // Open Modal
                    let url = "{{ route('schedules.detail.calender', ':uuid') }}".replace(':uuid', formatDate(info.event.startStr))
                    $.ajax({
                        url: url,
                        method: "GET",
                        success: function(response) {
                            $('#modal-div').html("");
                            if (response.status == 'success') {
                                $('#modal-div').html(response.msg);
                            } else {
                                Swal.fire({
                                    title: response.msg,
                                    icon: 'error',
                                    confirmButtonText: "Oke"
                                })
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                title: 'Failed, Error Server',
                                icon: 'error',
                                confirmButtonText: "Oke"
                            })
                        }
                    });
                    info.jsEvent.preventDefault();
                },
                events: function(fetchInfo, successCallback, failureCallback) {
                    fetch(`/schedules/calender-data/${formatDate(fetchInfo.startStr)}/${formatDate(fetchInfo.endStr)}`, {
                            method: 'GET',
                            headers: {
                                'Content-Type': 'application/json',
                            },

                        })
                        .then(response => response.json())
                        .then(response => {

                            const events = response.data.map(event => ({
                                title: `${event.count} Pegawai`,
                                start: event.date,
                                allDay: true //
                            }));
                            successCallback(events);
                        })
                        .catch(error => {
                            console.error('Error fetching events:', error);
                            failureCallback(error);
                        });
                }

            });

            calendar.render();
        });

        function formatDate(dateStr) {
            return dateStr.split('T')[0];
        }

</script>

<script>
    $(document).ready(function() {

        $('#createScheduleBtn').on('click', function() {
            $.ajax({
                url: "{{ route('schedules.create') }}",
                method: "GET",
                success: function(response) {
                    if (response.status === 'success') {
                        $('#modal-div').html(response.msg);
                    } else {
                        alert('Error: ' + response.msg);
                    }
                },
                error: function(xhr) {
                    alert('Failed to load form: ' + xhr.responseJSON.msg);
                }
            });
        });


    });
</script>


<script>
    function generateForm(){
        $.ajax({
            url: "{{ route('schedules.generate.form') }}",
            method: "GET",
            success: function(response) {
                if (response.status === 'success') {
                    $('#modal-div').html(response.msg);
                } else {
                    alert('Error: ' + response.msg);
                }
            },
            error: function(xhr) {
                alert('Failed to load form: ' + xhr.responseJSON.msg);
            }
        });
    }
</script>

@endsection
