@extends('layouts.app')

@section('title')
    Edit Department
@endsection

@section('content')
    <div class="container">
        <center>
            {{-- edit department form --}}
            <form id="editForm" class="w-50">
                @csrf
                <input type="hidden" id="departmentId" name="department_id" value="{{ $data['department']->id }}">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="enName" name="en_name"
                        value="{{ $data['department']->en_name }}" placeholder="English Name">
                    <label for="enName">English Name</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="arName" name="ar_name"
                        value="{{ $data['department']->ar_name }}" placeholder="Arabic Name">
                    <label for="arName">Arabic Name</label>
                </div>
                <div class="form-floating mb-3">
                    <select class="form-select" id="facultyId" name="faculty_id">
                        <option disabled selected value>Open this select menu</option>
                        <option value="{{ $data['department']->faculty->id }}" selected>
                            {{ $data['department']->faculty->en_name }}
                        </option>
                        @foreach ($data['faculties'] as $faculty)
                            <option value="{{ $faculty->id }}"> {{ $faculty->en_name }}</option>
                        @endforeach
                    </select>
                    <label for="facultyId">Works with selects</label>
                </div>
                <div class="form-floating mb-3">
                    <button type="submit" class="btn btn-success w-100" id="submitEditForm">Update</button>
                </div>

            </form>
        </center>


        {{-- council section --}}
        <div class="row">
            <div class="dropdown-menu text-body-secondary mt-3" id="councilSection"
                style="display: block; width: 90%;margin-left: -35px;">
                <!-- Your content for the Council dropdown goes here -->
                <div class="card">
                    <div class="card-header row">
                        <h6 class="col-md-11">Council</h6>

                        <a class="col-md-1 btn btn-success btn-sm" id="formateCouncilBtn" type="button" role="button"
                            data-bs-toggle="modal" data-bs-target="#councilModal">Formate council</a>
                    </div>

                    <div class="card-body">
                        @if ($data['departmentCouncil'])
                            <table class="table table-striped table-hover">
                                <thead class="text-center">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Position</th>
                                    </tr>
                                </thead>
                                <tbody class="table_body" id="councilContainer">
                                    @php $x = 0; @endphp
                                    @foreach ($data['departmentCouncil'] as $name => $position)
                                        @php $x++; @endphp
                                        <tr id="councilRow">
                                            <th class="text-center" scope="row">{{ $x }}</th>
                                            <td class="text-center">{{ $name }}</td>
                                            <td class="text-center">{{ $position }}</td>
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
        <div class="modal fade" id="councilModal" tabindex="-1" aria-labelledby="councilModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="councilModalLabel">Department Council</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" id="closeCouncilModal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="councilFormContent">
                        <!-- Department council form -->

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

        $("#submitEditForm").click(function(e) {
            e.preventDefault();

            var departmentId = document.getElementById('departmentId').value;

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
                url: `/departments/${departmentId}`,
                data: {
                    department_id: departmentId,
                    ar_name: formData.ar_name,
                    en_name: formData.en_name,
                    faculty_id: formData.faculty_id,
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
                        <td class="text-center">${response.data.faculty.en_name}</td>
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

        $('#formateCouncilBtn').click(function(e) {
            e.preventDefault();
            var departmentId = document.getElementById('departmentId').value;

            $.ajax({
                type: "GET",
                url: `/department-councils/form/${departmentId}`, // Use backticks for template literals
                success: function(response) {
                    $('#councilFormContent').html(response);
                },
                error: function(xhr) {
                    console.log(xhr.responseText); // Log error to console for debugging
                }
            });
        });

    </script>
@endsection
