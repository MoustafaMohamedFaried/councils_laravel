@extends('layouts.app')

@section('title')
    Departments
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">

            <div class="card">
                <div class="card-header row">
                    <h6 class="col-md-11">Departments</h6>
                    @if (auth()->user()->name == 'Super Admin' || auth()->user()->email == 'super@gmail.com')
                        <a class="col-md-1 btn btn-success btn-sm" id="createDepartmentBtn" type="button" role="button"
                            data-bs-toggle="modal" data-bs-target="#createModal">Create</a>
                    @endif
                </div>

                <div class="card-body">
                    @if ($departments->isNotEmpty())
                        <table class="table table-striped table-hover">
                            <thead class="text-center">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Code</th>
                                    <th scope="col">English Name</th>
                                    <th scope="col">Arabic Name</th>
                                    <th scope="col">Faculty</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="table_body" id="departmentContainer">
                                @php $x = 0; @endphp
                                @foreach ($departments as $department)
                                    @php $x++; @endphp
                                    <tr id="department_{{ $department->id }}">
                                        <th class="text-center" scope="row">{{ $x }}</th>
                                        <td class="text-center">{{ $department->code }}</td>
                                        <td class="text-center">{{ $department->en_name }}</td>
                                        <td class="text-center">{{ $department->ar_name }}</td>
                                        <td class="text-center">{{ $department->faculty->en_name }}</td>
                                        <td class="text-center">
                                            <a class="btn btn-secondary btn-sm" role="button" id="viewDepartmentBtn"
                                                data-department-id="{{ $department->id }}" data-bs-toggle="modal"
                                                data-bs-target="#viewModal">View</a>

                                            @if (auth()->user()->name == 'Super Admin' || auth()->user()->email == 'super@gmail.com')
                                                <a class="btn btn-primary btn-sm" role="button" id="editBtn"
                                                    href="{{ route('departments.edit', $department->id) }}">Edit</a>

                                                {{-- <a class="btn btn-primary btn-sm" role="button" id="editDepartmentBtn"
                                                data-department-id="{{ $department->id }}" data-bs-toggle="modal"
                                                data-bs-target="#editModal">Edit</a> --}}

                                                <a class="btn btn-danger btn-sm" role="button" id="deleteDepartmentBtn"
                                                    data-department-id="{{ $department->id }}" data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal">Delete</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $departments->links('pagination::bootstrap-5') }}
                    @else
                        <p class="text-center text-danger">No Departments</p>
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
                                <h1 class="modal-title fs-5" id="createModalLabel">Create Department</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" id="closeCreateModal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body" id="createFormContent">
                                <!-- create Department form -->

                            </div>
                        </div>
                    </div>
                </div>

                <!-- View Modal -->
                <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="viewModalLabel">View Department</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body" id="viewFormContent">
                                <!-- view Department form -->
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
                                <h1 class="modal-title fs-5" id="editModalLabel">Edit Department</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" id="closeEditModal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body" id="editFormContent">
                                <!-- edit Department form -->
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

        $("#createDepartmentBtn").click(function(e) {
            e.preventDefault();
            $.ajax({
                type: "GET",
                url: "{{ route('departments.create') }}",
                success: function(response) {
                    $("#createFormContent").html(response);
                }
            });
        });

        $(document).on('click', '#viewDepartmentBtn', function() {

            var departmentId = $(this).data('department-id'); // Get the department ID from the clicked button
            $.ajax({
                type: "GET",
                url: `/departments/${departmentId}`,
                success: function(response) {
                    $('#viewFormContent').html(response);
                }
            });
        });

        $(document).on('click', '#editDepartmentBtn', function() {

            var departmentId = $(this).data('department-id'); // Get the faculty ID from the clicked button
            $.ajax({
                type: "GET",
                url: `/departments/${departmentId}/edit`,
                success: function(response) {
                    $('#editFormContent').html(response);
                }
            });
        });

        $(document).on('click', '#deleteDepartmentBtn', function() {
            var departmentId = $(this).data('department-id'); // Get the department ID from the clicked button
            // Show the confirmation modal
            $('#deleteModal').modal('show');

            // Handle the delete confirmation button click inside the modal
            $('#confirmDeleteBtn').off('click').on('click', function() {
                $.ajax({
                    type: "DELETE",
                    url: `/departments/${departmentId}`,
                    success: function(response) {
                        $('#deleteModal').modal('hide'); // Hide the modal after deletion

                        $(`#department_${departmentId}`).remove();

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
