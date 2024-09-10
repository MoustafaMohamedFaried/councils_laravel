@extends('layouts.app')

@section('title')
    Agendas
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">

            <div class="card">
                <div class="card-header row">
                    <h6 class="col-md-11">Agendas</h6>
                    <a class="col-md-1 btn btn-success btn-sm" id="createAgendaBtn" type="button" role="button"
                        data-bs-toggle="modal" data-bs-target="#createModal">Create</a>
                </div>

                <div class="card-body">
                    @if ($agendas->isNotEmpty())
                        <table class="table table-striped table-hover">
                            <thead class="text-center">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Code</th>
                                    <th scope="col">Order</th>
                                    <th scope="col">Topic title</th>
                                    <th scope="col">Created by</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="table_body" id="agendaContainer">
                                @php $x = 0; @endphp
                                @foreach ($agendas as $agenda)
                                    @php $x++; @endphp
                                    <tr id="agenda_{{ $agenda->id }}">
                                        <th class="text-center" scope="row">{{ $x }}</th>
                                        <td class="text-center">{{ $agenda->code }}</td>
                                        <td class="text-center">{{ $agenda->order }}</td>
                                        <td class="text-center">{{ $agenda->topic->title }}</td>
                                        <td class="text-center">{{ $agenda->uploader->name }}</td>

                                        <td class="text-center">
                                            <a class="btn btn-secondary btn-sm" role="button" id="viewAgendaBtn"
                                                data-agenda-id="{{ $agenda->id }}" data-bs-toggle="modal"
                                                data-bs-target="#viewModal">View</a>

                                            <a class="btn btn-primary btn-sm" role="button" id="editAgendaBtn"
                                                data-agenda-id="{{ $agenda->id }}" data-bs-toggle="modal"
                                                data-bs-target="#editModal">Edit</a>

                                            <a class="btn btn-danger btn-sm" role="button" id="deleteAgendaBtn"
                                                data-agenda-id="{{ $agenda->id }}" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal">Delete</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $agendas->links('pagination::bootstrap-5') }}
                    @else
                        <p class="text-center text-danger">No Agendas</p>
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

                <!-- Create Modal -->
                <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="createModalLabel">Create agenda</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" id="closeCreateModal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body" id="createFormContent">
                                <!-- create agenda form -->

                            </div>
                        </div>
                    </div>
                </div>

                <!-- View Modal -->
                <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="viewModalLabel">View agenda</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body" id="viewFormContent">
                                <!-- view agenda form -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Modal -->
                <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="editModalLabel">Edit agenda</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" id="closeEditModal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body" id="editFormContent">
                                <!-- edit agenda form -->
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

        $("#createAgendaBtn").click(function(e) {
            e.preventDefault();
            $.ajax({
                type: "GET",
                url: "{{ route('agendas.create') }}",
                success: function(response) {
                    $("#createFormContent").html(response);
                }
            });
        });

        $(document).on('click', '#viewAgendaBtn', function() {
            var agendaId = $(this).data('agenda-id'); // Get the faculty ID from the clicked button
            $.ajax({
                type: "GET",
                url: `/agendas/${agendaId}`,
                success: function(response) {
                    $('#viewFormContent').html(response);
                }
            });
        });

        $(document).on('click', '#editAgendaBtn', function() {

            var agendaId = $(this).data('agenda-id'); // Get the faculty ID from the clicked button
            $.ajax({
                type: "GET",
                url: `/agendas/${agendaId}/edit`,
                success: function(response) {
                    $('#editFormContent').html(response);
                }
            });
        });

        $(document).on('click', '#deleteAgendaBtn', function() {
            var agendaId = $(this).data('agenda-id'); // Get the agenda ID from the clicked button
            // Show the confirmation modal
            $('#deleteModal').modal('show');

            // Handle the delete confirmation button click inside the modal
            $('#confirmDeleteBtn').off('click').on('click', function() {
                $.ajax({
                    type: "DELETE",
                    url: `/agendas/${agendaId}`,
                    success: function(response) {
                        $('#deleteModal').modal('hide'); // Hide the modal after deletion

                        $(`#agenda_${agendaId}`).remove();

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
