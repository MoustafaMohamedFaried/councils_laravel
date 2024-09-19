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
                            Date & Place
                        </a>
                        <a class="nav-link tab-pills">
                            <div class="step-circle">4</div>
                            Decision
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
                            <select class="form-select select2" id="Agendas" name="agenda_id[]" multiple="multiple">
                                {{-- options of agendas --}}
                            </select>
                        </div>
                    </div>

                    {{-- step 2 --}}
                    <div class="tab d-none">
                        {{-- invitations secion --}}
                        <div class="mb-3">
                            <label for="Invitations" class="form-label">Invitations</label>
                            <select class="form-select select2" id="Invitations" name="user_id[]" multiple="multiple">
                                {{-- options of users --}}
                            </select>
                        </div>
                    </div>

                    {{-- step 3 --}}
                    <div class="tab d-none">
                        <div class="mb-3">
                            <label for="Start_Time" class="form-label">Start Time</label>
                            <input type="datetime-local" class="form-control" name="start_time" id="Start_Time">
                        </div>
                        <div class="mb-3">
                            <label for="Total_Hours" class="form-label">Total Hours</label>
                            <input class="form-control" type="number" name="total_hours" id="Total_Hours">
                        </div>
                        <div class="mb-3">
                            <label for="Place" class="form-label">Place</label>
                            <input class="form-control" type="text" name="place" id="Place">
                        </div>
                    </div>

                    {{-- step 4 --}}
                    <div class="tab d-none">
                        <div class="mb-3">
                            <label for="Decision" class="form-label">Decision By</label>
                            <select class="form-select" id="Decision" name="decision_by">
                                <option disabled selected value>Select option</option>
                                <option value="0">Members</option>
                                <option value="1">Secretary of the Department Council</option>
                            </select>
                        </div>
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

        // Reset the form fields on page load
        $(document).ready(function() {
            $('#CreateForm')[0].reset(); // This resets all fields, including text inputs

            // Clear Select2 selections
            $('#Agendas').val(null).trigger('change'); // Clear selections in Agendas
            $('#Invitations').val(null).trigger('change'); // Clear selections in Invitations

            // Manually clear specific text inputs (if needed)
            $('#Start_Time').val(''); // Clear datetime-local input
            $('#Total_Hours').val(''); // Clear number input
            $('#Place').val(''); // Clear text input
            $('#Decision').val(''); // Clear text input
        });

        // Function to initialize Select2 multi-select and hide selected options
        function intializeMuiltiSelect(inputId, placeholder) {
            $('#' + inputId).select2({
                placeholder: placeholder, // Adjust the placeholder as needed
                templateResult: function(data) {
                    // Hide selected options in the dropdown
                    if ($.inArray(data.id, $('#' + inputId).val()) !== -1) {
                        return null; // Return null to hide the option
                    }
                    return data.text; // Return the text for non-selected options
                }
            });

            // Listen to select2:select and select2:unselect to refresh the dropdown
            $('#' + inputId).on('select2:select select2:unselect', function() {
                $(this).trigger('change.select2'); // Refresh the dropdown
            });
        }

        // Initialize both multi-select elements
        $(document).ready(function() {
            intializeMuiltiSelect('Agendas', 'select topics'); // Initialize for Agendas
            intializeMuiltiSelect('Invitations', 'select invitations'); // Initialize for Invitations
        });


        var DepartmentsId = document.getElementById('Departments').value;
        // if one department
        if (DepartmentsId) {
            // return users of department council
            $.ajax({
                url: `/sessions-departments/getInvitationFromDepartmentId/${DepartmentsId}`,
                type: 'GET',
                success: function(invitations) {
                    // Clear the previous options
                    $('#Invitations').empty();

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
                    $('#Agendas').empty();

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
                        $('#Invitations').empty();

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
                        $('#Agendas').empty();

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

            $.ajax({
                type: "POST",
                url: `{{ route('sessions-departments.store') }}`,
                data: {
                    department_id: $('#Departments').val(),
                    place: $('#Place').val(),
                    total_hours: $('#Total_Hours').val(),
                    start_time: $('#Start_Time').val(),
                    decision_by: $('#Decision').val(),
                    agenda_id: $('#Agendas').val(), // Since it's a multiselect, it will return an array
                    user_id: $('#Invitations').val(),
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    toastr.options = {
                        "closeButton": true,
                        "progressBar": true,
                        "positionClass": "toast-top-right",
                        "timeOut": "1500",
                        "preventDuplicates": true,
                        "extendedTimeOut": "1000"
                    };

                    toastr.success(response.message);

                    // Redirect after a short delay to allow the toastr to be visible
                    setTimeout(function() {
                        window.location.href = "{{ route('sessions-departments.index') }}";
                    }, 1500); // Delay in milliseconds (match this with the timeOut value)
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
