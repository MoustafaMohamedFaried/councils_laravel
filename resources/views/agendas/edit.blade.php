<form id="editForm">
    @csrf

    <input type="hidden" id="agendaId" name="agenda_id" value="{{ $data['agenda']->id }}">

    <div class="form-floating mb-3">
        <select class="form-select" id="facultyId" name="faculty_id">
            <option disabled selected value>Select Faculty</option>
            @foreach ($data['faculties'] as $faculty)
                <option @if ($data['agenda']->department->faculty_id == $faculty->id) value="{{ $faculty->id }}" selected  @endif
                    value="{{ $faculty->id }}"> {{ $faculty->ar_name }}</option>
            @endforeach
        </select>
        <label for="facultyId">Faculty</label>
    </div>

    <div class="form-floating mb-3">
        <select class="form-select" id="departmentId" name="department_id">
            <option disabled selected value>Choose Department</option>
        </select>
        <label for="departmentId">Department</label>
    </div>

    <div class="form-floating mb-3">
        <select class="form-select" id="mainTopic" name="main_topic">
            <option disabled selected value>Select Main Topic</option>
            @foreach ($data['mainTopics'] as $mainTopic)
                <option @if ($data['agenda']->topic->main_topic_id == $mainTopic->id) value="{{ $mainTopic->id }}" selected @endif
                    value="{{ $mainTopic->id }}"> {{ $mainTopic->title }}</option>
            @endforeach
        </select>
        <label for="mainTopic">Main Topic</label>
    </div>

    <div class="form-floating mb-3">
        <select class="form-select" id="supTopic" name="topic_id">
            <option disabled selected value>Select Sup Topic</option>
        </select>
        <label for="supTopic">Sup Topic</label>
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


    $(document).ready(function() {

        var selectedFacultyId = "{{ $data['agenda']->department->faculty_id }}";
        // let department selected by defualt
        if (selectedFacultyId) {
            // Make an AJAX request to get departments related to the selected faculty
            $.ajax({
                url: `/departments/get_departments_by_faculty_id/${selectedFacultyId}`,
                type: 'GET',
                success: function(departments) {
                    // Clear the previous options
                    $('#departmentId').empty().append(
                        '<option disabled selected value>Select Department</option>');

                    // Populate the departments select field with new options
                    $.each(departments, function(index, department) {
                        $('#departmentId').append(
                            `<option value="${department.id}" selected>${department.ar_name}</option>`
                        );
                    });
                },
                error: function() {
                    console.error('Failed to fetch departments');
                }
            });
        }

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
                            '<option disabled selected value>Select Department</option>'
                        );

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
            }
        });

        var selectedMainTopic = "{{ $data['agenda']->topic->main_topic_id }}";
        // let sup topic selected by defualt
        if (selectedMainTopic) {
            // Make an AJAX request to get supTopics related to the selected faculty
            $.ajax({
                url: `/topics/get_sup_topics_by_main_topic/${selectedMainTopic}`,
                type: 'GET',
                success: function(supTopics) {
                    // Clear the previous options
                    $('#supTopic').empty().append(
                        '<option disabled selected value>Select Sup Topic</option>');

                    // Populate the supTopics select field with new options
                    $.each(supTopics, function(index, supTopic) {
                        $('#supTopic').append(
                            `<option value="${supTopic.id}" selected>${supTopic.title}</option>`
                        );
                    });
                },
                error: function() {
                    console.error('Failed to fetch sup topics');
                }
            });
        }

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
            }
        });
    });


    $("#submitEditForm").click(function(e) {
        e.preventDefault();

        var agendaId = document.getElementById('agendaId').value;
        console.log(agendaId);

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
            url: `/agendas/${agendaId}`,
            data: {
                agendaId: agendaId,
                main_topic: formData.main_topic,
                topic_id: formData.topic_id,
                department_id: formData.department_id,
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

                var agendaContainer = $('#agendaContainer');

                var topicRow = $('#agenda_' + agendaId);

                var thElement = topicRow.find('th[scope="row"]');

                var rowIndex = thElement.text();

                topicRow.html(
                    `
                        <th class="text-center" scope="row">${rowIndex}</th>
                        <td class="text-center">${response.data.agenda.code}</td>
                        <td class="text-center">${response.data.agenda.order}</td>
                        <td class="text-center">${response.data.topic_title}</td>
                        <td class="text-center">${response.data.created_by}</td>

                        <td class="text-center">
                            <a class="btn btn-secondary btn-sm" role="button" id="viewAgendaBtn"
                                data-agenda-id="${response.data.agenda.id}" data-bs-toggle="modal"
                                data-bs-target="#viewModal">View</a>

                            <a class="btn btn-primary btn-sm" role="button"
                                id="editAgendaBtn" data-agenda-id="${response.data.agenda.id}"
                                data-bs-toggle="modal" data-bs-target="#editModal">Edit</a>

                            <a class="btn btn-danger btn-sm" role="button" id="deleteAgendaBtn"
                                data-agenda-id="${response.data.agenda.id}" data-bs-toggle="modal"
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
