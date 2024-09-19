<form id="editForm">
    @csrf
    <input type="hidden" id="headquarterId" name="faculty_id" value="{{ $headquarter->id }}">
    <div class="form-floating mb-3">
        <input type="text" class="form-control" id="enName" name="en_name" value="{{ $headquarter->en_name }}"
            placeholder="English Name">
        <label for="enName">English Name</label>
    </div>
    <div class="form-floating mb-3">
        <input type="text" class="form-control" id="arName" name="ar_name" value="{{ $headquarter->ar_name }}"
            placeholder="Arabic Name">
        <label for="arName">Arabic Name</label>
    </div>
    <div class="form-floating mb-3">
        <textarea type="text" class="form-control" id="Address" name="address" placeholder="Address"> {{ $headquarter->address }} </textarea>
        <label for="Address">Address</label>
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

        var headquarterId = document.getElementById('headquarterId').value;

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
            url: `/headquarters/${headquarterId}`,
            data: {
                faculty_id: headquarterId,
                ar_name: formData.ar_name,
                en_name: formData.en_name,
                address: formData.address,
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

                var headquarterContainer = $('#headquarterContainer');

                var headquarterRow = $('#headquarter_' + headquarterId);

                var thElement = headquarterRow.find('th[scope="row"]');

                var rowIndex = thElement.text();

                headquarterRow.html(
                    `
                        <th class="text-center" scope="row">${rowIndex}</th>
                        <td class="text-center">${response.data.code}</td>
                        <td class="text-center">${response.data.en_name}</td>
                        <td class="text-center">${response.data.ar_name}</td>
                        <td class="text-center">
                            <a class="btn btn-secondary btn-sm" role="button" id="viewHeadquarterBtn"
                                data-headquarter-id="${response.data.id}" data-bs-toggle="modal"
                                data-bs-target="#viewModal">View</a>

                            <a class="btn btn-primary btn-sm" role="button"
                                id="editHeadquarterBtn" data-headquarter-id="${response.data.id}"
                                data-bs-toggle="modal" data-bs-target="#editModal">Edit</a>

                            <a class="btn btn-danger btn-sm" role="button" id="deleteHeadquarterBtn"
                                data-headquarter-id="${response.data.id}" data-bs-toggle="modal"
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
