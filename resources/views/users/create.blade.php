<form id="CreateForm">
    @csrf

    {{-- personal info --}}
    <div class="card mb-3">
        <div class="card-header">
            <h5 class="card-title">Personal Info</h5>
        </div>

        <div class="card-body">
            {{-- Name and email section --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="Name" name="name" placeholder="Name">
                        <label for="Name">Name</label>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="email" class="form-control" id="Email" name="email" placeholder="Email">
                        <label for="Email">Email</label>
                    </div>
                </div>
            </div>

            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="Password" name="password" placeholder="Password">
                <label for="Password">Password</label>
            </div>
        </div>
    </div>


    {{-- related info --}}
    <div class="card mb-3">
        <div class="card-header">
            <h5 class="card-title">Related Info</h5>
        </div>

        <div class="card-body">
            {{-- Role and position section --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <select class="form-select" id="Role" name="role">
                            <option disabled selected value>Select Role</option>
                            @foreach ($data['roles'] as $role)
                                <option value="{{ $role->name }}"> {{ $role->name }}</option>
                            @endforeach
                        </select>
                        <label for="Role">Role</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <select class="form-select" id="positionId" name="position_id">
                            <option disabled selected value>Select Position</option>
                            @foreach ($data['positions'] as $position)
                                <option value="{{ $position->id }}"> {{ $position->ar_name }}</option>
                            @endforeach
                        </select>
                        <label for="positionId">Position</label>
                    </div>
                </div>
            </div>

            {{-- Headquarter and faculty section --}}
            <div class="row">
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <select class="form-select" id="headquarterId" name="headquarter_id">
                            <option disabled selected value>Select Headquarter</option>
                            @foreach ($data['headquarters'] as $headquarter)
                                <option value="{{ $headquarter->id }}"> {{ $headquarter->ar_name }}</option>
                            @endforeach
                        </select>
                        <label for="headquarterId">Headquarter</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <select class="form-select" id="facultyId" name="faculty_id" disabled>
                            <option disabled selected value>Select Faculty</option>
                            @foreach ($data['faculties'] as $faculty)
                                <option value="{{ $faculty->id }}"> {{ $faculty->ar_name }}</option>
                            @endforeach
                        </select>
                        <label for="facultyId">Faculty</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-floating mb-3">
        <button type="submit" class="btn btn-primary" id="submitCreateForm">Submit</button>
    </div>

</form>


<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // let faculty dependance on headquarter
    $('#headquarterId').change(function() {
        var headquarterId = $(this).val();

        if (headquarterId) {
            $('#facultyId').prop('disabled', false); // Enable the faculty select field

            // Make an AJAX request to get faculties related to the selected headquarter
            $.ajax({
                url: `/faculties/get_faculties_by_headquarter_id/${headquarterId}`,
                type: 'GET',
                success: function(faculties) {
                    // Clear the previous options
                    $('#facultyId').empty().append(
                        '<option disabled selected value>Select Faculty</option>');

                    // Populate the faculties select field with new options
                    $.each(faculties, function(index, faculty) {
                        $('#facultyId').append(
                            `<option value="${faculty.id}">${faculty.ar_name}</option>`);
                    });
                },
                error: function() {
                    console.error('Failed to fetch faculties');
                }
            });
        } else {
            $('#facultyId').prop('disabled', true).empty().append(
                '<option disabled selected value>Select Faculty</option>'
            ); // Disable and reset faculty select field
        }
    });

    $("#submitCreateForm").click(function(e) {
        e.preventDefault();
        // Collect form data
        var formDataArray = $('#CreateForm').serializeArray();

        // Convert form data array to an object
        var formData = {};
        for (var i = 0; i < formDataArray.length; i++) {
            var item = formDataArray[i];
            formData[item.name] = item.value;
        }

        $.ajax({
            type: "POST",
            url: "{{ route('users.store') }}",
            data: {
                name: formData.name,
                email: formData.email,
                password: formData.password,
                role: formData.role,
                position_id: formData.position_id,
                faculty_id: formData.faculty_id,
                headquarter_id: formData.headquarter_id,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#closeCreateModal').click();

                toastr.options = {
                    "closeButton": true,
                    "progressBar": true,
                    "positionClass": "toast-top-right",
                    "timeOut": "3500",
                    "preventDuplicates": true,
                    "extendedTimeOut": "1000"
                };

                toastr.success(response.message);

                // Ensure userContainer is a jQuery object
                var userContainer = $('#userContainer');

                // Check if there are any existing rows
                var existingRowsCount = userContainer.find('tr').length;

                // Calculate the next index count based on the number of rows
                var nextIndex = existingRowsCount > 0 ? existingRowsCount + 1 : 1;

                // Append the new row to the faculty container (which should be a <tbody>)
                userContainer.append(`
                        <tr id="headquarter_${response.data.id}">
                            <th class="text-center" scope="row">${nextIndex}</th>
                            <td class="text-center">${response.data.name}</td>
                            <td class="text-center">${response.data.email}</td>
                            <td class="text-center"><span class="badge rounded-pill text-bg-success">Active</span></td>
                            <td class="text-center">
                                <a class="btn btn-secondary btn-sm" role="button" id="viewUserBtn"
                                    data-user-id="${response.data.id}" data-bs-toggle="modal"
                                    data-bs-target="#viewModal">View</a>

                                <a class="btn btn-primary btn-sm" role="button"
                                    id="editUserBtn" data-user-id="${response.data.id}"
                                    data-bs-toggle="modal" data-bs-target="#editModal">Edit</a>

                                <a class="btn btn-danger btn-sm" role="button" id="deleteUserBtn"
                                    data-user-id="${response.data.id}" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal">Delete</a>
                            </td>
                        </tr>
                    `);
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
