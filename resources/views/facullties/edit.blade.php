@extends('layouts.app')

@section('title')
    Edit Faculty
@endsection

@section('content')
    <div class="container">
        <center>
            <form id="editForm" class="w-50">
                @csrf
                <input type="hidden" id="facultyId" name="faculty_id" value="{{ $data['faculty']->id }}">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="enName" name="en_name"
                        value="{{ $data['faculty']->en_name }}" placeholder="English Name">
                    <label for="enName">English Name</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="arName" name="ar_name"
                        value="{{ $data['faculty']->ar_name }}" placeholder="Arabic Name">
                    <label for="arName">Arabic Name</label>
                </div>
                <div class="form-floating mb-3">
                    <select class="form-select" id="headquarterId" name="headquarter_id">
                        <option disabled selected value>Open this select menu</option>
                        <option value="{{ $data['faculty']->headquarter->id }}" selected>
                            {{ $data['faculty']->headquarter->en_name }}
                        </option>
                        @foreach ($data['headquarters'] as $headquarter)
                            <option value="{{ $headquarter->id }}"> {{ $headquarter->en_name }}</option>
                        @endforeach
                    </select>
                    <label for="headquarterId">Works with selects</label>
                </div>
                <div class="form-floating mb-3">
                    <button type="submit" class="btn btn-success w-100" id="submitEditForm">Update</button>
                </div>

            </form>
        </center>


        <div class="row" style="margin: 3rem 0 0 26rem">
            <div class="col-md-6">
                <!-- Button for Admins Section -->
                <button class="btn btn-lg btn-primary m-2" type="button" onclick="toggleVisibility('adminsSection')">
                    Admins
                </button>

                <!-- Button for Departments Section -->
                <button class="btn btn-lg btn-primary m-2" type="button" onclick="toggleVisibility('departmentsSection')">
                    Departments
                </button>

                <!-- Button for Council Section -->
                <button class="btn btn-lg btn-primary m-2" type="button" onclick="toggleVisibility('councilSection')">
                    Council
                </button>
            </div>
        </div>

        <div class="row">
            <!-- Dropdown Menu for Admins -->
            <div class="dropdown-menu text-body-secondary mt-3" id="adminsSection"
                style="display: none; width: 90%;margin-left: -35px;">
                <!-- Your content for the Council dropdown goes here -->
                <p class="text-center">Admin Content</p>
            </div>

            <!-- Dropdown Menu for Departments -->
            <div class="dropdown-menu text-body-secondary mt-3" id="departmentsSection"
                style="display: none; width: 90%;margin-left: -35px;">
                <div class="card">
                    <div class="card-header row">
                        <h6 class="col-md-11">Departments</h6>

                        <a class="col-md-1 btn btn-success btn-sm" id="createDepartmentBtn" type="button" role="button"
                            data-bs-toggle="modal" data-bs-target="#CreateModal">Create</a>
                    </div>

                    <div class="card-body">
                        @if ($data['facultyDepartments']->isNotEmpty())
                            <table class="table table-striped table-hover">
                                <thead class="text-center">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Code</th>
                                        <th scope="col">English Name</th>
                                        <th scope="col">Arabic Name</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="table_body" id="departmentContainer">
                                    @php $x = 0; @endphp
                                    @foreach ($data['facultyDepartments'] as $department)
                                        @php $x++; @endphp
                                        <tr id="department_{{ $department->id }}">
                                            <th class="text-center" scope="row">{{ $x }}</th>
                                            <td class="text-center">{{ $department->code }}</td>
                                            <td class="text-center">{{ $department->en_name }}</td>
                                            <td class="text-center">{{ $department->ar_name }}</td>
                                            <td class="text-center">
                                                <a class="btn btn-secondary btn-sm" role="button" id="viewDepartmentBtn"
                                                    data-department-id="{{ $department->id }}" data-bs-toggle="modal"
                                                    data-bs-target="#viewModal">View</a>

                                                <a class="btn btn-primary btn-sm" role="button" id="editDepartmentBtn"
                                                    data-department-id="{{ $department->id }}" data-bs-toggle="modal"
                                                    data-bs-target="#editModal">Edit</a>

                                                <a class="btn btn-danger btn-sm" role="button" id="deleteDepartmentBtn"
                                                    data-department-id="{{ $department->id }}" data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal">Delete</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $data['facultyDepartments']->links('pagination::bootstrap-5') }}
                        @else
                            <p class="text-center text-danger">No Departments</p>
                        @endif
                    </div>
                </div>
            </div>


            <!-- Dropdown Menu for Council -->
            <div class="dropdown-menu text-body-secondary mt-3" id="councilSection"
                style="display: none; width: 90%;margin-left: -35px;">
                <!-- Your content for the Council dropdown goes here -->
                <div class="card">
                    <div class="card-header row">
                        <h6 class="col-md-11">Council</h6>

                        <a class="col-md-1 btn btn-success btn-sm" id="formateCouncilBtn" type="button" role="button"
                            data-bs-toggle="modal" data-bs-target="#councilModal">Formate council</a>
                    </div>

                    <div class="card-body">
                        @if ($data['facultyCouncil'])
                            <table class="table table-striped table-hover">
                                {{-- <thead class="text-center">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Position</th>
                                    </tr> --}}
                                </thead>
                                <tbody class="table_body" id="councilContainer">
                                    @php $x = 0; @endphp
                                    @foreach ($data['facultyCouncil'] as $name => $position)
                                        {{-- @php $x++; @endphp
                                        <tr id="councilRow">
                                            <th class="text-center" scope="row">{{ $x }}</th>
                                            <td class="text-center">{{ $name }}</td>
                                            <td class="text-center">{{ $position }}</td>
                                        </tr> --}}
                                        @php $x++; @endphp
                                        <tr>
                                            <th class="text-center" scope="row">{{ $x }}</th>
                                            <td class="text-center">{{ $position }}</td>
                                            <td scope="col" class="text-center">{{ $name }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{-- {{ $data['facultyDepartments']->links('pagination::bootstrap-5') }} --}}
                        @else
                            <p class="text-center text-danger">Council hasn't been formated yet</p>
                        @endif
                    </div>

                </div>
            </div>

        </div>

        <!-- Council Modal -->
        <div class="modal fade" id="councilModal" tabindex="-1" aria-labelledby="councilModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="councilModalLabel">Faculty Council</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" id="closeCouncilModal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="councilFormContent">
                        <!-- faculty council form -->

                    </div>
                </div>
            </div>
        </div>

        <!-- Create department Modal -->
        <div class="modal fade" id="CreateModal" tabindex="-1" aria-labelledby="CreateModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="CreateModalLabel">Create Department</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" id="closeCreateModal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="createFormContent">
                        <!-- create Department form -->

                    </div>
                </div>
            </div>
        </div>

        <!-- Delete department Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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

        <!-- Edit department Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="editModalLabel">Edit Department</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" id="closeEditModal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="editFormContent">
                        <!-- edit Department form -->
                        <form id="editDepartmentForm">
                            @csrf
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="departmentEnName" name="en_name"
                                    value="" placeholder="English Name">
                                <label for="enName">English Name</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="departmentArName" name="ar_name"
                                    value="" placeholder="Arabic Name">
                                <label for="arName">Arabic Name</label>
                            </div>
                            <div class="form-floating mb-3">
                                <button type="submit" class="btn btn-primary w-100"
                                    id="submitEditDepartmentForm">Update</button>
                            </div>

                        </form>
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

        function toggleVisibility(sectionId) {
            // Select the target div
            var section = document.getElementById(sectionId);

            // Toggle display property
            if (section.style.display === 'none' || section.style.display === '') {
                // Hide all sections first
                document.querySelectorAll('.dropdown-menu').forEach(function(div) {
                    div.style.display = 'none';
                });

                // Then show the clicked section
                section.style.display = 'block';
            } else {
                // Hide the section if it's already visible
                section.style.display = 'none';
            }
        }

        var facultyId = document.getElementById('facultyId').value;

        $("#submitEditForm").click(function(e) {
            e.preventDefault();

            // Collect form data
            var formDataArray = $('#editForm').serializeArray();

            // Convert form data array to an object
            var formData = {};
            for (var i = 0; i < formDataArray.length; i++) {
                var item = formDataArray[i];
                formData[item.name] = item.value;
            }

            $.ajax({
                type: "PUT",
                url: `/faculties/${facultyId}`,
                data: {
                    faculty_id: facultyId,
                    ar_name: formData.ar_name,
                    en_name: formData.en_name,
                    headquarter_id: formData.headquarter_id,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#closeEditModal').click();

                    toastr.options = {
                        "closeButton": true,
                        "progressBar": true,
                        "positionClass": "toast-top-right",
                        "timeOut": "3500",
                        "preventDuplicates": true,
                        "extendedTimeOut": "1000"
                    };

                    toastr.success(response.message);

                    var facultyContainer = $('#facultyContainer');

                    var facultyRow = $('#faculty_' + facultyId);

                    var thElement = facultyRow.find('th[scope="row"]');

                    var rowIndex = thElement.text();

                    facultyRow.html(
                        `
                            <th class="text-center" scope="row">${rowIndex}</th>
                            <td class="text-center">${response.data.code}</td>
                            <td class="text-center">${response.data.en_name}</td>
                            <td class="text-center">${response.data.ar_name}</td>
                            <td class="text-center">
                                <a class="btn btn-secondary btn-sm" role="button" id="viewFacultyBtn"
                                    data-faculty-id="${response.data.id}" data-bs-toggle="modal"
                                    data-bs-target="#viewModal">View</a>

                                <a class="btn btn-primary btn-sm" role="button"
                                    id="editFacultyBtn" data-faculty-id="${response.data.id}"
                                    data-bs-toggle="modal" data-bs-target="#editModal">Edit</a>

                                <a class="btn btn-danger btn-sm" role="button" id="deletefacultyBtn"
                                    data-faculty-id="${response.data.id}" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal">Delete</a>
                            </td>
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

        $('#formateCouncilBtn').click(function(e) {
            e.preventDefault();

            $.ajax({
                type: "GET",
                url: `/faculty-councils/form/${facultyId}`, // Use backticks for template literals
                success: function(response) {
                    $('#councilFormContent').html(response);
                },
                error: function(xhr) {
                    console.log(xhr.responseText); // Log error to console for debugging
                }
            });
        });

        $("#createDepartmentBtn").click(function(e) {
            e.preventDefault();
            $.ajax({
                type: "GET",
                url: `/departments/create/${facultyId}`,
                success: function(response) {
                    $("#createFormContent").html(response);
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

        // When the "Edit" button is clicked fetch department data at form
        $(document).on('click', '#editDepartmentBtn', function(e) {
            e.preventDefault();

            // Get the department ID from the data attribute
            var departmentId = $(this).data('department-id');

            // Send an AJAX request to fetch the department data
            $.ajax({
                type: "GET",
                url: `/departments/get-department/${departmentId}`, // Make sure this route exists in your web.php
                success: function(response) {
                    // Fill the form fields with the department data
                    $('#departmentEnName').val(response.en_name);
                    $('#departmentArName').val(response.ar_name);
                    // You can populate any other fields if needed
                }
            });
        });

        // saving update department
        $("#submitEditDepartmentForm").click(function(e) {
            e.preventDefault();

            var departmentId = $('#editDepartmentBtn').data('department-id'); // Get the department ID

            // Collect form data
            var formDataArray = $('#editDepartmentForm').serializeArray();

            // Convert form data array to an object
            var formData = {};
            for (var i = 0; i < formDataArray.length; i++) {
                var item = formDataArray[i];
                formData[item.name] = item.value;
            }

            $.ajax({
                type: "PUT",
                url: `/departments/${departmentId}`,
                data: {
                    department_id: departmentId,
                    ar_name: formData.ar_name,
                    en_name: formData.en_name,
                    faculty_id: facultyId,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#closeEditModal').click();

                    toastr.options = {
                        "closeButton": true,
                        "progressBar": true,
                        "positionClass": "toast-top-right",
                        "timeOut": "3500",
                        "preventDuplicates": true,
                        "extendedTimeOut": "1000"
                    };

                    toastr.success(response.message);

                    var departmentContainer = $('#departmentContainer');

                    var departmentRow = $('#department_' + departmentId);

                    var thElement = departmentRow.find('th[scope="row"]');

                    var rowIndex = thElement.text();

                    departmentRow.html(
                        `
                            <th class="text-center" scope="row">${rowIndex}</th>
                            <td class="text-center">${response.data.department.code}</td>
                            <td class="text-center">${response.data.department.en_name}</td>
                            <td class="text-center">${response.data.department.ar_name}</td>
                            <td class="text-center">
                                <a class="btn btn-secondary btn-sm" role="button" id="viewDepartmentBtn"
                                    data-department-id="${response.data.department.id}" data-bs-toggle="modal"
                                    data-bs-target="#viewModal">View</a>

                                <a class="btn btn-primary btn-sm" role="button"
                                    id="editDepartmentBtn" data-department-id="${response.data.department.id}"
                                    data-bs-toggle="modal" data-bs-target="#editModal">Edit</a>

                                <a class="btn btn-danger btn-sm" role="button" id="deleteDepartmentBtn"
                                    data-department-id="${response.data.department.id}" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal">Delete</a>
                            </td>
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
