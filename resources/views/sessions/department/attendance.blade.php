<div class="container">
    <div class="d-flex row">
        @foreach ($data['invitations'] as $user_id => $user_name)
            <div class="col-md-4">
                <label class="form-label">{{ $user_name }}</label>
            </div>
            <div class="col-md-4">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="status[{{ $user_id }}]"
                        @if (isset($data['attendance'][$user_id]) && $data['attendance'][$user_id] == 1) checked @endif id="attend_{{ $user_id }}"
                        value="attend_{{ $user_id }}">
                    <label class="form-check-label" for="attend_{{ $user_id }}">Attend</label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="status[{{ $user_id }}]"
                        @if (isset($data['attendance'][$user_id]) && $data['attendance'][$user_id] == 2) checked @endif id="absent_{{ $user_id }}"
                        value="absent_{{ $user_id }}">
                    <label class="form-check-label" for="absent_{{ $user_id }}">Absent</label>
                </div>
            </div>
        @endforeach
    </div>
</div>


<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    <button type="button" class="btn btn-primary" id="saveAttendance">Save</button>
</div>


<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var sessionId = `{{ $data['session']->id }}`;

    $("#saveAttendance").click(function(e) {
        e.preventDefault();

        var sessionId = `{{ $data['session']->id }}`;
        var attendanceData = [];

        // Loop through each user to get the selected status and user_id
        $("input[type=radio]:checked").each(function() {
            var userId = $(this).attr('name').match(/\d+/)[
                0]; // Extract user ID from the 'name' attribute
            var status = 0;

            // Check the id of the selected radio button to determine the status
            if ($(this).attr('id').includes('attend')) {
                status = 1; // Attend status
            } else if ($(this).attr('id').includes('absent')) {
                status = 2; // Absent status
            }

            attendanceData.push({
                user_id: userId,
                status: status
            });
        });

        $.ajax({
            type: "POST",
            url: `/sessions-departments/save-attendance/${sessionId}`,
            data: {
                attendance: attendanceData
            },
            success: function(response) {
                $('#closeAttendanceModal').click();

                toastr.options = {
                    "closeButton": true,
                    "progressBar": true,
                    "positionClass": "toast-top-right",
                    "timeOut": "1500",
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
