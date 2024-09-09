<form id="CreateForm">
    @csrf

    <div class="form-floating mb-3">
        <select class="form-select" id="facultyId" name="faculty_id">
            <option disabled selected value>Select Faculty</option>
            @foreach ($data['faculties'] as $faculty)
                <option value="{{ $faculty->id }}"> {{ $faculty->ar_name }}</option>
            @endforeach
        </select>
        <label for="facultyId">Faculty</label>
    </div>

    <div class="form-floating mb-3">
        <select class="form-select" id="departmentId" name="department_id" disabled>
            <option disabled selected value>Choose Department</option>
        </select>
        <label for="departmentId">Department</label>
    </div>

    <div class="form-floating mb-3">
        <select class="form-select" id="mainTopic" name="main_topic">
            <option disabled selected value>Select Main Topic</option>
            @foreach ($data['mainTopics'] as $mainTopic)
                <option value="{{ $mainTopic->id }}"> {{ $mainTopic->title }}</option>
            @endforeach
        </select>
        <label for="mainTopic">Main Topic</label>
    </div>

    <div class="form-floating mb-3">
        <select class="form-select" id="supTopic" name="topic_id" disabled>
            <option disabled selected value>Select Sup Topic</option>
        </select>
        <label for="supTopic">Position</label>
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

    // let department dependance on faculty
    $('#facultyId').change(function() {
        var facultyId = $(this).val();

        if (facultyId) {
            $('#departmentId').prop('disabled', false); // Enable the department select field

            // Make an AJAX request to get departments related to the selected faculty
            $.ajax({
                url: `/departments/get_departments_by_faculty_id/${facultyId}`,
                type: 'GET',
                success: function(departments) {
                    // Clear the previous options
                    $('#departmentId').empty().append(
                        '<option disabled selected value>Select Department</option>');

                    // Populate the departments select field with new options
                    $.each(departments, function(index, department) {
                        $('#departmentId').append(
                            `<option value="${department.id}">${department.ar_name}</option>`
                        );
                    });
                },
                error: function() {
                    console.error('Failed to fetch departments');
                }
            });
        } else {
            $('#departmentId').prop('disabled', true).empty().append(
                '<option disabled selected value>Select Departmen</option>'
            ); // Disable and reset department select field
        }
    });

    // let sup_topic dependance on main_topic
    $('#mainTopic').change(function() {
        var mainTopic = $(this).val();

        if (mainTopic) {
            $('#supTopic').prop('disabled', false); // Enable the department select field

            // Make an AJAX request to get supTopics related to the selected faculty
            $.ajax({
                url: `/topics/get_sup_topics_by_main_topic/${mainTopic}`,
                type: 'GET',
                success: function(supTopics) {
                    // Clear the previous options
                    $('#supTopic').empty().append(
                        '<option disabled selected value>Select Sup Topic</option>');

                    // Populate the supTopics select field with new options
                    $.each(supTopics, function(index, supTopic) {
                        $('#supTopic').append(
                            `<option value="${supTopic.id}">${supTopic.title}</option>`
                        );
                    });
                },
                error: function() {
                    console.error('Failed to fetch sup topics');
                }
            });
        } else {
            $('#departmentId').prop('disabled', true).empty().append(
                '<option disabled selected value>Select Departmen</option>'
            ); // Disable and reset department select field
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
            url: "{{ route('agendas.store') }}",
            data: {
                main_topic: formData.main_topic,
                topic_id: formData.topic_id,
                department_id: formData.department_id,
                faculty_id: formData.faculty_id,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#closeCreateModal').click();
console.log(response);

                toastr.options = {
                    "closeButton": true,
                    "progressBar": true,
                    "positionClass": "toast-top-right",
                    "timeOut": "3500",
                    "preventDuplicates": true,
                    "extendedTimeOut": "1000"
                };

                toastr.success(response.message);

                // Ensure agendaContainer is a jQuery object
                var agendaContainer = $('#agendaContainer');

                // Check if there are any existing rows
                var existingRowsCount = agendaContainer.find('tr').length;

                // Calculate the next index count based on the number of rows
                var nextIndex = existingRowsCount > 0 ? existingRowsCount + 1 : 1;

                // Append the new row to the faculty container (which should be a <tbody>)
                agendaContainer.append(`
                        <tr id="topic_${response.data.id}">
                            <th class="text-center" scope="row">${nextIndex}</th>
                            <td class="text-center">${response.data.agenda.code}</td>
                            <td class="text-center">${response.data.agenda.order}</td>
                            <td class="text-center">${response.data.topic_title}</td>
                            <td class="text-center">${response.data.created_by}</td>

                            <td class="text-center">
                                <a class="btn btn-secondary btn-sm" role="button" id="viewTopicBtn"
                                    data-topic-id="${response.data.id}" data-bs-toggle="modal"
                                    data-bs-target="#viewModal">View</a>

                                <a class="btn btn-primary btn-sm" role="button"
                                    id="editTopicBtn" data-topic-id="${response.data.id}"
                                    data-bs-toggle="modal" data-bs-target="#editModal">Edit</a>

                                <a class="btn btn-danger btn-sm" role="button" id="deleteTopicBtn"
                                    data-topic-id="${response.data.id}" data-bs-toggle="modal"
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
                            errorMessage += `<div class="container">${message}<br></div>`;
                        });
                    });

                    // Display all error messages in a single toastr notification
                    toastr.error(errorMessage);
                }
            }
        });
    });
</script>
