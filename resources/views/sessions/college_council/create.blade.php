<form id="CreateForm">
    @csrf

    <div class="form-floating mb-3">
        <select class="form-select" id="session" name="session_id">
            <option disabled selected value>Select Session Code</option>
            @foreach ($data['sessions'] as $session)
                <option value="{{ $session->id }}"> {{ $session->code }}</option>
            @endforeach
        </select>
        <label for="session">Session Code</label>
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

    var userPositionId = `{{ auth()->user()->position_id }}`;

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
            url: "{{ route('college-councils.store') }}",
            data: {
                session_id: formData.session_id,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // console.log(response.data);

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

                // Ensure collegeCouncilContainer is a jQuery object
                var collegeCouncilContainer = $('#collegeCouncilContainer');

                // Check if there are any existing rows
                var existingRowsCount = collegeCouncilContainer.find('tr').length;

                // Calculate the next index count based on the number of rows
                var nextIndex = existingRowsCount > 0 ? existingRowsCount + 1 : 1;

                // Append the new row to the container
                var newRow = `
                    <tr id="collegeCouncil_${response.data.id}">
                        <th class="text-center" scope="row">${nextIndex}</th>
                        <td class="text-center">${response.data.session.code}</td>
                        <td class="text-center">${response.data.session.place}</td>
                        <td class="text-center">${response.data.session.responsible.name}</td>
                        <td class="text-center">
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="visually-hidden">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item text-secondary" role="button" id="viewcollegeCouncilBtn" data-session-id="${response.data.session_id}" data-bs-toggle="modal" data-bs-target="#viewModal">View</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item text-info" role="button" href="/sessions-departments/report-details/${response.data.session_id}">Report details</a>
                                    </li>`;

                // Only show edit/delete options if the user is the dean of the college (position_id = 5)
                if (userPositionId === 5) {
                    newRow += `
                            <li>
                                <a class="dropdown-item text-primary" role="button" href="/college-councils/${response.data.id}/edit">Edit</a>
                            </li>
                            <li>
                                <a class="dropdown-item text-danger" role="button" data-college-council-id="${response.data.id}" data-session-id="${response.data.session_id}" data-bs-toggle="modal" data-bs-target="#deleteModal">Delete</a>
                            </li>`;
                }

                newRow += `
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    `;

                collegeCouncilContainer.append(newRow);
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
