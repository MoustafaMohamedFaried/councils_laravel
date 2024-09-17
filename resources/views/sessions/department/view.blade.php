@extends('layouts.app')

@section('title')
    View Session
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">

            <div class="card-body">
                {{-- Session details secion --}}
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title row">
                            <span class="col-md-11">Session Details</span>

                            {{-- hide button when status accepted or rejected --}}
                            @if ($data['session']->status != 1 || $data['session']->status != 2)
                                <a class="col-md-1 btn btn-success btn-sm" role="button" id="statusBtn"
                                    data-session-id="{{ $data['session']->id }}" data-bs-toggle="modal"
                                    data-bs-target="#statusModal">Status</a>
                            @endif
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-4">
                                <p><b>Code:</b> {{ $data['session']->code }}</p>
                            </div>
                            <div class="col-md-4">
                                <p><b>Order:</b> {{ $data['session']->order }}</p>
                            </div>
                            <div class="col-md-4">
                                <p><b>Department:</b> {{ $data['session']->department->ar_name }}</p>
                            </div>
                            <div class="col-md-4">
                                <p><b>Place:</b> {{ $data['session']->place }}</p>
                            </div>
                            <div class="col-md-4">
                                <p id="statusRow">
                                    <b>Status:</b>
                                    @if ($data['session']->status == 0)
                                        <span class="badge rounded-pill text-bg-primary">Pending</span>
                                    @elseif ($data['session']->status == 1)
                                        <span class="badge rounded-pill text-bg-success">Accepted</span>
                                    @else
                                        <span class="badge rounded-pill text-bg-danger">Rejected</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-4">
                                <p id="rejectReasonRow"><b>Reject reason:</b>
                                    {{ $data['session']->reject_reason ?? 'ﻻ يوجد' }}</p>
                            </div>
                            <div class="col-md-4">
                                <p>
                                    <b>Decision By:</b>
                                    @if ($data['session']->decision_by == 0)
                                        Members
                                    @else
                                        Secretary of the Department Council
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-4">
                                <p><b>Created by:</b> {{ $data['session']->createdBy->name }}</p>
                            </div>
                            <div class="col-md-4">
                                <p><b>Responsible:</b> {{ $data['session']->responsible->name }}</p>
                            </div>
                            <div class="col-md-4">
                                <p><b>Total hours:</b> {{ $data['session']->total_hours }}</p>
                            </div>
                            <div class="col-md-4">
                                <p><b>Start date:</b> {{ $data['session']->start_time }}</p>
                            </div>
                            <div class="col-md-4">
                                <p><b>Schedual end date:</b> {{ $data['session']->schedual_end_time }}</p>
                            </div>
                            <div class="col-md-4">
                                <p><b>Actual start date:</b> {{ $data['session']->actual_start_time ?? 'لم يحدد بعد' }}</p>
                            </div>
                            <div class="col-md-4">
                                <p><b>Actual end date:</b> {{ $data['session']->actual_end_time ?? 'لم يحدد بعد' }}</p>
                            </div>
                            <div class="col-md-4">
                                <p><b>Created at:</b> {{ $data['session']->created_at }}</p>
                            </div>
                            <div class="col-md-4">
                                <p><b>Updated at:</b> {{ $data['session']->updated_at }}</p>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Topics secion --}}
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title">Topics</h5>
                    </div>

                    <div class="card-body">
                        <div class="row mb-3">
                            @foreach ($data['topics'] as $topic)
                                <div class="col-md-6">
                                    <ul>
                                        <li>{{ $topic }}</li>
                                    </ul>
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>

                {{-- Users & invitations secion --}}
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title">Invitations</h5>
                    </div>

                    <div class="card-body">
                        <div class="row mb-3">
                            @foreach ($data['invitations'] as $user)
                                <div class="col-md-6">
                                    <ul>
                                        <li>{{ $user }}</li>
                                    </ul>
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>

            </div>

            <!-- change status Modal -->
            <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="statusModalLabel">Take decision</h5>
                            <button type="button" class="btn-close" id="closeStatusModalBtn" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <select class="form-select form-select mb-3" name="status" id="Status">
                                <option disabled selected value>Choose option</option>
                                <option value="1">Accepted</option>
                                <option value="2">Rejected</option>
                                <option value="3">Rejected with reason</option>
                            </select>

                            <div class="form-floating mb-3" id="RejectReasonContainer" style="display: none;">
                                <input type="text" class="form-control" placeholder="Reject Reason" id="RejectReason"
                                    name="reject_reason">
                                <label for="RejectReason">Reject Reason</label>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-success" id="submitBtn">Submit</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#Status').val(''); // Clear status input
            $('#RejectReason').val(''); // Clear reject reason input
        });

        $('#Status').on('change', function() {
            if ($(this).val() == '3') {
                $('#RejectReasonContainer').show(); // Show the text input
            } else {
                $('#RejectReasonContainer').hide(); // Hide the text input
            }
        });

        $('#submitBtn').click(function(e) {
            e.preventDefault();

            var sessionId = "{{ $data['session']->id }}";

            $.ajax({
                type: "PUT",
                url: `/sessions-departments/changeStatus/${sessionId}`,
                data: {
                    session_id: sessionId,
                    status: $("#Status").val(),
                    reject_reason: $("#RejectReason").val(),
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $("#closeStatusModalBtn").click();

                    // remove status btn when status accepted
                    if (response.data.status == 1) {
                        $("#statusBtn").addClass('d-none');
                    }

                    toastr.options = {
                        "closeButton": true,
                        "progressBar": true,
                        "positionClass": "toast-top-right",
                        "timeOut": "2000",
                        "preventDuplicates": true,
                        "extendedTimeOut": "1000"
                    };

                    toastr.success(response.message);

                    $("#rejectReasonRow").html(` <b>Reject reason:</b> ${response.data.reject_reason || 'ﻻيوجد'} `);
                    $("#statusRow").html(
                        `
                            <b>Status:</b>
                            ${
                                response.data.status == 0
                                ? '<span class="badge rounded-pill text-bg-primary">Pending</span>'
                                : response.data.status == 1
                                ? '<span class="badge rounded-pill text-bg-success">Accepted</span>'
                                : '<span class="badge rounded-pill text-bg-danger">Rejected</span>'
                            }
                        `
                    );
                },

                error: function(xhr, status, error) {
                    console.error("An error occurred: ", error);
                    console.log(xhr.responseText);

                    toastr.options = {
                        "closeButton": true,
                        "progressBar": true,
                        "positionClass": "toast-top-right",
                        "timeOut": "3000",
                        "preventDuplicates": true,
                        "extendedTimeOut": "1000"
                    };

                    // Parse the response JSON
                    var response = JSON.parse(xhr.responseText);

                    // Concatenate all error messages into a single string
                    var errorMessage = "";

                    if (response.errors) {
                        $.each(response.errors, function(field, messages) {
                            $.each(messages, function(index, message) {
                                errorMessage +=
                                    `<div class="container">${message}<br></div>`;
                            });
                        });

                        // Display all error messages in a single toastr notification
                        toastr.error(errorMessage);
                    }
                }
            });
        });
    </script>
@endsection
