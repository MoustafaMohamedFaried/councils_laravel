<form id="editForm">
    @csrf
    <input type="hidden" id="facultyId" name="faculty_id" value="{{ $faculty->id }}">
    <div class="form-floating mb-3">
        <input type="text" class="form-control" id="enName" name="en_name" value="{{ $faculty->en_name }}"
            placeholder="English Name">
        <label for="enName">English Name</label>
    </div>
    <div class="form-floating mb-3">
        <input type="text" class="form-control" id="arName" name="ar_name" value="{{ $faculty->ar_name }}"
            placeholder="Arabic Name">
        <label for="arName">Arabic Name</label>
    </div>
    <div class="form-floating mb-3">
        <select class="form-select" id="headquarterId" name="headquarter_id">
            <option disabled selected value>Open this select menu</option>
            <option value="{{ $faculty->headquarter->id }}" selected> {{ $faculty->headquarter->en_name }}</option>
            @foreach ($headquarters as $headquarter)
                <option value="{{ $headquarter->id }}"> {{ $headquarter->en_name }}</option>
            @endforeach
        </select>
        <label for="headquarterId">Works with selects</label>
    </div>
    <div class="form-floating mb-3">
        <button type="submit" class="btn btn-primary" id="submitEditForm">Submit</button>
    </div>

</form>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("#submitEditForm").click(function(e) {
        e.preventDefault();

        var facultyId = document.getElementById('facultyId').value;

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
</script>
