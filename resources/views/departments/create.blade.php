<form id="CreateForm">
    @csrf
    <div class="form-floating mb-3">
        <input type="text" class="form-control" id="enName" name="en_name" placeholder="English Name">
        <label for="enName">English Name</label>
    </div>
    <div class="form-floating mb-3">
        <input type="text" class="form-control" id="arName" name="ar_name" placeholder="Arabic Name">
        <label for="arName">Arabic Name</label>
    </div>
    @if ($faculty_id)
        <input type="hidden" name="faculty_id" value="{{ $faculty_id }}" id="createDepFromFaculty">
    @else
        <div class="form-floating mb-3">
            <select class="form-select" id="facultyId" name="faculty_id">
                <option disabled selected value>Select Faculty</option>
                @foreach ($faculties as $faculty)
                    <option value="{{ $faculty->id }}"> {{ $faculty->en_name }}</option>
                @endforeach
            </select>
            <label for="facultyId">Faculty</label>
        </div>
    @endif
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
            url: "{{ route('departments.store') }}",
            data: {
                ar_name: formData.ar_name,
                en_name: formData.en_name,
                faculty_id: formData.faculty_id,
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

                // Ensure departmentContainer is a jQuery object
                var departmentContainer = $('#departmentContainer');

                // Check if there are any existing rows
                var existingRowsCount = departmentContainer.find('tr').length;

                // Calculate the next index count based on the number of rows
                var nextIndex = existingRowsCount > 0 ? existingRowsCount + 1 : 1;

                // Append the new row to the faculty container (which should be a <tbody>)
                departmentContainer.append(`
                        <tr id="department_${response.data.id}">
                            <th class="text-center" scope="row">${nextIndex}</th>
                            <td class="text-center">${response.data.code}</td>
                            <td class="text-center">${response.data.en_name}</td>
                            <td class="text-center">${response.data.ar_name}</td>
                            <td class="text-center">${response.data.faculty.en_name}</td>
                            <td class="text-center">
                                <a class="btn btn-secondary btn-sm" role="button" id="viewDepartmentBtn"
                                    data-department-id="${response.data.id}" data-bs-toggle="modal"
                                    data-bs-target="#viewModal">View</a>

                                <a class="btn btn-primary btn-sm" role="button"
                                    id="editDepartmentBtn" data-department-id="${response.data.id}"
                                    data-bs-toggle="modal" data-bs-target="#editModal">Edit</a>

                                <a class="btn btn-danger btn-sm" role="button" id="deleteDepartmentBtn"
                                    data-department-id="${response.data.id}" data-bs-toggle="modal"
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
