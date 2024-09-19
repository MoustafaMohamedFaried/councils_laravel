@extends('layouts.app')

@section('title')
    Start Session
@endsection

@section('content')
    <div class="container">
        <div class="row text-center" style="margin-top: 25rem">
            <p class="col-md-2"><b>Session Code:</b> {{ $data['session']->code }}</p>
            <div class="col-md-3">
                <button class="btn btn-primary" id="attendanceBtn" data-bs-toggle="modal"
                    data-bs-target="#attendanceModal">Attendance</button>
            </div>
            <div class="col-md-3">
                <button class="btn btn-primary" id="decisionBtn" data-bs-toggle="modal"
                    data-bs-target="#decisionModal">Decision</button>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary" id="voteBtn" data-bs-toggle="modal"
                    data-bs-target="#voteModal">Vote</button>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary" id="reportBtn">Report</button>
            </div>
        </div>


        <!-- Attendance Modal -->
        <div class="modal fade" id="attendanceModal" tabindex="-1" aria-labelledby="attendanceModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="attendanceModalLabel">Take Attendance</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" id="closeAttendanceModal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="viewAttendanceContent">
                        {{-- content of attendance --}}
                    </div>

                </div>
            </div>
        </div>


        <!-- Decision Modal -->
        <div class="modal fade" id="decisionModal" tabindex="-1" aria-labelledby="decisionModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="decisionModalLabel">Add Decision</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" id="closeDecisionModal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        {{-- content of Decision --}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>


        <!-- Vote Modal -->
        <div class="modal fade" id="voteModal" tabindex="-1" aria-labelledby="voteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="voteModalLabel">Add Vote</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" id="closeVoteModal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        {{-- content of Vote --}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
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

        var sessionId = `{{ $data['session']->id }}`;

        $(document).on('click', '#attendanceBtn', function() {

            $.ajax({
                type: "GET",
                url: `/sessions-departments/fetch-attendance/${sessionId}`,
                success: function(response) {
                    $('#viewAttendanceContent').html(response);
                }
            });
        });
    </script>
@endsection
