@extends('layouts.app')

@section('title')
    Session Department
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">

            <div class="card">
                <div class="card-header row">
                    <h6 class="col-md-11">sessions</h6>
                    {{-- if user position is secretary of department council --}}
                    @if (auth()->user()->position_id == 2)
                        <a class="col-md-1 btn btn-success btn-sm" href="{{ route('sessions-departments.create') }}"
                            type="button">Create</a>
                    @endif
                </div>

                <div class="card-body">
                    @if ($data['sessions']->isNotEmpty())
                        <table class="table table-striped table-hover">
                            <thead class="text-center">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Code</th>
                                    <th scope="col">Order</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Created By</th>
                                    <th scope="col">Responsible</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="table_body" id="sessionContainer">
                                @php $x = 0; @endphp
                                @foreach ($data['sessions'] as $session)
                                    @php $x++; @endphp
                                    <tr id="session_{{ $session->id }}">
                                        <th class="text-center" scope="row">{{ $x }}</th>
                                        <td class="text-center">{{ $session->code }}</td>
                                        <td class="text-center">{{ $session->order }}</td>
                                        <td class="text-center">
                                            @if ($session->status == 0)
                                                <span class="badge rounded-pill text-bg-primary">Pending</span>
                                            @elseif ($session->status == 1)
                                                <span class="badge rounded-pill text-bg-success">Accepted</span>
                                            @else
                                                <span class="badge rounded-pill text-bg-danger">Rejected</span>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $session->createdBy->name }}</td>
                                        <td class="text-center">{{ $session->responsible->name }}</td>
                                        <td class="text-center">

                                            <div class="btn-group">
                                                <button type="button"
                                                    class="btn btn-primary dropdown-toggle dropdown-toggle-split"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <span class="visually-hidden">Toggle Dropdown</span>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item text-success"
                                                            href="{{ route('sessions-departments.show', $session->id) }}"
                                                            id="viewSessionBtn">View</a>
                                                    </li>
                                                    @if (auth()->id() == $session->created_by)
                                                        {{-- display if status is pending or rejected with reason --}}
                                                        @if ($session->status == 0 || $session->status == 3)
                                                            <li>
                                                                <a class="dropdown-item text-primary"
                                                                    href="{{ route('sessions-departments.edit', $session->id) }}">Edit</a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item text-danger" role="button"
                                                                    id="deleteSessionBtn"
                                                                    data-session-id="{{ $session->id }}"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#deleteModal">Delete</a>
                                                            </li>
                                                        @endif
                                                    @endif
                                                    <li>
                                                        <a class="dropdown-item text-success"
                                                            href="{{ route('sessions-departments.start-session', $session->id) }}"
                                                            id="startSessionBtn">Start Session</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $data['sessions']->links('pagination::bootstrap-5') }}
                    @else
                        <p class="text-center text-danger">No sessions</p>
                    @endif
                </div>

                <!-- Delete Modal -->
                <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <b> Are you sure you want to delete this record? </b>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                            </div>
                        </div>
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
        $(document).on('click', '#deleteSessionBtn', function() {
            var sessionId = $(this).data('session-id'); // Get the session ID from the clicked button
            // Show the confirmation modal
            $('#deleteModal').modal('show');

            // Handle the delete confirmation button click inside the modal
            $('#confirmDeleteBtn').off('click').on('click', function() {
                $.ajax({
                    type: "DELETE",
                    url: `/sessions-departments/${sessionId}`,
                    success: function(response) {
                        $('#deleteModal').modal('hide'); // Hide the modal after deletion

                        $(`#session_${sessionId}`).remove();

                        toastr.options = {
                            "closeButton": true,
                            "progressBar": true,
                            "positionClass": "toast-top-right",
                            "timeOut": "3500",
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
        });
    </script>

@endsection
