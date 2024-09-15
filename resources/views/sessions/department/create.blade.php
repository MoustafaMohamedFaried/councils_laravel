@extends('layouts.app')

@section('title')
    Create Session
@endsection

@section('content')
    <section class="mt-4">
        <div class="container">
            <form class="card" id="CreateForm" action="">
                <div class="card-header">
                    <nav class="nav nav-pills nav-fill">
                        <a class="nav-link tab-pills">
                            <div class="step-circle">1</div>
                            Topics
                        </a>
                        <a class="nav-link tab-pills">
                            <div class="step-circle">2</div>
                            Invitations
                        </a>
                        <a class="nav-link tab-pills">
                            <div class="step-circle">3</div>
                            Company Details
                        </a>
                        <a class="nav-link tab-pills">
                            <div class="step-circle">4</div>
                            Finish
                        </a>
                    </nav>
                </div>
                <div class="card-body">
                    {{-- step 1 --}}
                    <div class="tab d-none">
                        {{-- department section --}}
                        @if (count($data['departments']) == 1)
                            @foreach ($data['departments'] as $department_id => $department_name)
                                <input type="hidden" id="Departments" name="department_id" value="{{ $department_id }}">
                            @endforeach
                        @else
                            <div class="mb-3">
                                <label for="Departments" class="form-label">Department</label>
                                <select class="form-select" id="Departments" name="department_id">
                                    <option disabled selected vlaue>Choose department</option>
                                    @foreach ($data['departments'] as $department_id => $department_name)
                                        <option value="{{ $department_id }}">{{ $department_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        {{-- topic secion --}}
                        <div class="mb-3">
                            <label for="Agendas" class="form-label">Topics</label>
                            <select class="form-select" id="Agendas" name="agenda_id">
                                <option disabled selected vlaue>Choose topics</option>
                                {{-- @foreach ($data['agendas'] as $agenda)
                                    <option value="{{ $agenda->id }}">{{ $agenda->name }}</option>
                                @endforeach --}}
                            </select>
                        </div>
                    </div>

                    {{-- step 2 --}}
                    <div class="tab d-none">
                        {{-- invitations secion --}}
                        <div class="mb-3">
                            <label for="Invitations" class="form-label">Invitations</label>
                            <select class="form-select" id="Invitations" name="user_id">
                                <option disabled selected vlaue>Choose users</option>
                            </select>
                        </div>
                    </div>

                    <div class="tab d-none">
                        <div class="mb-3">
                            <label for="company_name" class="form-label">Company Name</label>
                            <input type="text" class="form-control" name="company_name" id="company_name"
                                placeholder="Please enter company name">
                        </div>
                        <div class="mb-3">
                            <label for="company_address" class="form-label">Company Address</label>
                            <textarea class="form-control" name="company_address" id="company_address" placeholder="Please enter company address"></textarea>
                        </div>
                    </div>

                    <div class="tab d-none">
                        <p>All Set! Please submit to continue. Thank you</p>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <div class="d-flex">
                        <button type="button" id="back_button" class="btn btn-secondary" onclick="back()">Back</button>
                        <button type="button" id="next_button" class="btn btn-primary ms-auto"
                            onclick="next()">Next</button>
                        <button type="submit" id="submitCreateForm" class="btn btn-success d-none ms-auto">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <style>
        .nav-pills .nav-link {
            display: flex;
            flex-direction: column;
            align-items: center;
            font-weight: bold;
            color: #333;
        }

        .step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #007bff;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            margin-bottom: 5px;
        }

        .nav-link.active .step-circle {
            background-color: #28a745;
            /* Change circle color when active */
        }

        .nav-link.active {
            color: #28a745;
        }
    </style>

    <script>
        var current = 0;
        var tabs = $(".tab");
        var tabs_pill = $(".tab-pills");

        loadFormData(current);

        function loadFormData(n) {
            $(tabs_pill[n]).addClass("active");
            $(tabs[n]).removeClass("d-none");

            // Disable the back button on the first tab
            $("#back_button").attr("disabled", n == 0 ? true : false);

            // If it's the last step, hide the Next button and show the Submit button
            if (n == tabs.length - 1) {
                $("#next_button").addClass("d-none");
                $("#submitCreateForm").removeClass("d-none");
            } else {
                $("#next_button").removeClass("d-none");
                $("#submitCreateForm").addClass("d-none");
            }
        }

        function next() {
            $(tabs[current]).addClass("d-none");
            $(tabs_pill[current]).removeClass("active");

            current++;
            loadFormData(current);
        }

        function back() {
            $(tabs[current]).addClass("d-none");
            $(tabs_pill[current]).removeClass("active");

            current--;
            loadFormData(current);
        }



        var DepartmentsId = document.getElementById('Departments').value;
        // if one department
        if (DepartmentsId) {
            // return users of department council
            $.ajax({
                url: `/sessions-departments/getInvitationFromDepartmentId/${DepartmentsId}`,
                type: 'GET',
                success: function(invitations) {
                    // Clear the previous options
                    $('#Invitations').empty().append(
                        '<option disabled selected vlaue>Choose users</option>');

                    // Populate the invitations select field with new options
                    $.each(invitations, function(user_id, user_name) {
                        $('#Invitations').append(
                            `<option value="${user_id}">${user_name}</option>`
                        );
                    });
                },
                error: function() {
                    console.error('Failed to fetch users');
                }
            });

            // return topics from department_id
            $.ajax({
                url: `/agendas/getAgendasByDepartment/${DepartmentsId}`,
                type: 'GET',
                success: function(agendas) {
                    // Clear the previous options
                    $('#Agendas').empty().append(
                        '<option disabled selected vlaue>Choose topics</option>');

                    // Populate the agendas select field with new options
                    $.each(agendas, function(agenda_id, agenda_name) {
                        $('#Agendas').append(
                            `<option value="${agenda_id}">${agenda_name}</option>`
                        );
                    });
                },
                error: function() {
                    console.error('Failed to fetch agendas');
                }
            });
        }

        // if more than one department
        $('#Departments').change(function() {
            var departmentId = $(this).val(); // Get selected department ID

            if (departmentId) {
                // return users of department council
                $.ajax({
                    url: `/sessions-departments/getInvitationFromDepartmentId/${departmentId}`, // Corrected variable
                    type: 'GET',
                    success: function(invitations) {
                        // Clear previous options
                        $('#Invitations').empty().append(
                            '<option disabled selected value>Choose users</option>');

                        // Populate invitations select field with new options
                        $.each(invitations, function(user_id, user_name) {
                            $('#Invitations').append(
                                `<option value="${user_id}">${user_name}</option>`
                            );
                        });
                    },
                    error: function() {
                        console.error('Failed to fetch users');
                    }
                });

                // return topics from department_id
                $.ajax({
                    url: `/agendas/getAgendasByDepartment/${departmentId}`,
                    type: 'GET',
                    success: function(agendas) {
                        // Clear the previous options
                        $('#Agendas').empty().append(
                            '<option disabled selected vlaue>Choose topics</option>');

                        // Populate the agendas select field with new options
                        $.each(agendas, function(agenda_id, agenda_name) {
                            $('#Agendas').append(
                                `<option value="${agenda_id}">${agenda_name}</option>`
                            );
                        });
                    },
                    error: function() {
                        console.error('Failed to fetch agendas');
                    }
                });
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
                url: "{{ route('sessions-departments.store') }}",
                data: {
                    department_id: formData.department_id,
                    place: formData.place,
                    start_time: formData.start_time,
                    decision_by: formData.decision_by,
                    total_hours: formData.total_hours,
                    agenda_id: formData.agenda_id,
                    user_id: formData.user_id,
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
