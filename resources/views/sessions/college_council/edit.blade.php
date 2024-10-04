@extends('layouts.app')

@section('title')
    Edit College Council
@endsection

@section('content')
    <div class="container">
        {{-- change status to all topics in one step --}}
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="sessionCode" class="form-label">Session Code</label>
                    <input type="text" class="form-control" id="sessionCode"
                        value="{{ $data['collegeCouncil']->session->code }}" readonly>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="statusAll" class="form-label">Status</label>
                    <select class="form-select" id="statusAll" name="status_total">
                        <option disabled selected value>Select option</option>
                        <option value="1">Accepted</option>
                        <option value="2">Rejected</option>
                        <option value="3">Rejected with reason</option>
                    </select>
                </div>
            </div>
            <div class="col-md-12 d-none" id="rejectReasonSection">
                <div class="mb-3">
                    <div class="form-floating">
                        <textarea class="form-control" name="reject_reason_total" placeholder="Rejected reason" id="rejectedReason"
                            style="height: 100px"></textarea>
                        <label for="rejectedReason">Rejected reason</label>
                    </div>
                </div>
            </div>
        </div>

        <button type="button" class="btn btn-primary" id="updateStatusTotal">Save changes</button>

        <div class="card mt-5">
            <div class="card-header">
                <h5 class="card-title row">
                    <span class="col-md-11">Topics</span>

                    @if ($data['collegeCouncil']->status == 0)
                        <a class="col-md-1 btn btn-success btn-sm" role="button" id="statusBtn" data-bs-toggle="modal"
                            data-bs-target="#statusModal">Take decision</a>
                    @endif
                </h5>
            </div>

            <div class="card-body">

                <div class="row">
                    @foreach ($data['collegeCouncilWithTopics'] as $collegeCouncilTopic)
                        <div class="col-md-6">
                            <p><b>Title:</b> {{ $collegeCouncilTopic->agenda->name }}</p>
                        </div>
                        <div class="col-md-3">
                            <p><b>Status:</b>
                                @if ($collegeCouncilTopic->status == 0)
                                    <span class="badge rounded-pill text-bg-primary">Pending</span>
                                @elseif ($collegeCouncilTopic->status == 1)
                                    <span class="badge rounded-pill text-bg-success">Accepted</span>
                                @else
                                    <span class="badge rounded-pill text-bg-danger">Rejected</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-3">
                            <p><b>Reject reason:</b> <span
                                    class="text-danger">{{ $collegeCouncilTopic->reject_reason ?? 'لا يوجد' }}</span></p>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>

        <!-- Status Modal -->
        <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="statusModalLabel">Take decsion on session topics</h1>
                        <button type="button" class="btn-close" id="closeStatusModal" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        @foreach ($data['collegeCouncilWithTopics'] as $collegeCouncilTopic)
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="sessionCode" class="form-label">Topic</label>
                                        <p>{{ $collegeCouncilTopic->agenda->name }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="singleStatus_{{ $collegeCouncilTopic->agenda_id }}"
                                            class="form-label">Status</label>
                                        <select class="form-select singleStatus"
                                            id="singleStatus_{{ $collegeCouncilTopic->agenda_id }}"
                                            name="status_single[{{ $collegeCouncilTopic->agenda_id }}]">
                                            <option disabled selected value>Select option</option>
                                            <option value="1">Accepted</option>
                                            <option value="2">Rejected</option>
                                            <option value="3">Rejected with reason</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12 d-none"
                                    id="singleRejectedReasonSection_{{ $collegeCouncilTopic->agenda_id }}">
                                    <div class="mb-3">
                                        <div class="form-floating">
                                            <textarea class="form-control" name="reject_reason_single" placeholder="Rejected reason"
                                                id="singleRejectedReason_{{ $collegeCouncilTopic->agenda_id }}" style="height: 100px"></textarea>
                                            <label for="singleRejectedReason_{{ $collegeCouncilTopic->agenda_id }}">Rejected
                                                reason</label>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        @endforeach
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="updateStatus">Save changes</button>
                    </div>
                </div>
            </div>
        </div>

    </div>


    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var collegeCouncilId = `{{ $data['collegeCouncil']->id }}`;
        var sessionId = `{{ $data['collegeCouncil']->session_id }}`;

        $('#statusAll').on('change', function() {
            var selectedValue = $(this).val();
            if (selectedValue == '3') {
                $('#rejectReasonSection').removeClass('d-none').addClass('d-block');
                $('#rejectedReason').attr('required', 'required');
            } else {
                $('#rejectReasonSection').removeClass('d-block').addClass('d-none');
            }
        });

        $(document).ready(function() {
            $('.singleStatus').on('change', function() {
                // Get the dynamically generated agenda_id from the select's ID
                var agendaId = $(this).attr('id').split('_')[1];
                var selectedValue = $(this).val();

                if (selectedValue == '3') {
                    // Show the corresponding rejection reason section
                    $('#singleRejectedReasonSection_' + agendaId).removeClass('d-none').addClass('d-block');
                    // Make the textarea required
                    $('#singleRejectedReason_' + agendaId).attr('required', 'required');
                } else {
                    // Hide the rejection reason section
                    $('#singleRejectedReasonSection_' + agendaId).removeClass('d-block').addClass('d-none');
                    // Remove the required attribute from the textarea
                    $('#singleRejectedReason_' + agendaId).removeAttr('required');
                }
            });
        });

        $(document).ready(function() {
            $("#updateStatusTotal").click(function(e) {
                e.preventDefault();

                // Get the value of the 'statusAll' select field
                var statusAll = $('#statusAll').val() || 0;

                // Get the rejected reason if status is 'Rejected with reason'
                var rejectedReason = '';
                if (statusAll == '3') {
                    rejectedReason = $('#rejectedReason').val();
                }

                $.ajax({
                    type: "PUT",
                    url: `/college-councils/${collegeCouncilId}`,
                    data: {
                        changeStatusTotal: statusAll,
                        rejectReasonTotal: rejectedReason,
                        session_id: sessionId
                    },
                    success: function(response) {
                        toastr.options = {
                            "closeButton": true,
                            "progressBar": true,
                            "positionClass": "toast-top-right",
                            "timeOut": "1500",
                            "preventDuplicates": true,
                            "extendedTimeOut": "1000"
                        };

                        toastr.success(response.message);

                        $("#statusBtn").toggleClass("d-none");
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
        });


        $("#updateStatus").click(function(e) {
            e.preventDefault();

            var statusData = [];

            // Loop through all agenda items in the modal
            $('.singleStatus').each(function() {
                var agendaId = $(this).attr('id').split('_')[1]; // Extract agenda_id from the ID
                var status = $(this).val(); // Get the selected status value
                var rejectReason = ''; // Default to empty

                // If the status is 'Rejected with reason' (value 3), get the rejected reason textarea value
                if (status == '3') {
                    rejectReason = $('#singleRejectedReason_' + agendaId).val();
                }

                // Add the data for this agenda to the statusData array
                statusData.push({
                    agenda_id: agendaId,
                    status: status,
                    reject_reason: rejectReason
                });
            });

            // Send AJAX request with statusData
            $.ajax({
                type: "PUT",
                url: `/college-councils/${collegeCouncilId}`,
                data: {
                    changeSingleStatus: statusData
                },
                success: function(response) {
                    $('#closeStatusModal').click();

                    toastr.options = {
                        "closeButton": true,
                        "progressBar": true,
                        "positionClass": "toast-top-right",
                        "timeOut": "1500",
                        "preventDuplicates": true,
                        "extendedTimeOut": "1000"
                    };

                    toastr.success(response.message);

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
