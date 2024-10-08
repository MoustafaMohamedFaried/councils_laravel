<div class="container">
    @foreach ($data['decision'] as $decision)
        <div class="mb-3">
            <p>
                <b class="text-danger">Topic title:</b>
                {{ $decision['topic_title'] }}
            </p>

            {{-- @dump($decision['decision_id']) --}}

            <div class="form-floating mb-3">
                <textarea class="form-control" id="Decision" placeholder="Decision" readonly style="height: 100px">{{ $decision['decision'] }}</textarea>
                <label for="Decision">Decision</label>
            </div>

            <div class="d-flex row">
                @foreach ($data['invitations'] as $user_id => $user_name)
                    <div class="col-md-6">
                        <label class="form-label">{{ $user_name }}</label>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio"
                                name="status[{{ $user_id }}','{{ $decision['decision_id'] }}]"
                                @if (isset($data['vote'][$decision['decision_id'] . ',' . $user_id]) &&
                                        $data['vote'][$decision['decision_id'] . ',' . $user_id] == 1) checked @endif id="accept_{{ $user_id }}"
                                value="accept_{{ $user_id }}">
                            <label class="form-check-label" for="accept_{{ $user_id }}">Accept</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio"
                                name="status[{{ $user_id }}','{{ $decision['decision_id'] }}]"
                                @if (isset($data['vote'][$decision['decision_id'] . ',' . $user_id]) &&
                                        $data['vote'][$decision['decision_id'] . ',' . $user_id] == 2) checked @endif id="reject_{{ $user_id }}"
                                value="reject_{{ $user_id }}">
                            <label class="form-check-label" for="reject_{{ $user_id }}">Reject</label>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    @endforeach
</div>


<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    <button type="button" class="btn btn-primary" id="saveDecision">Save</button>
</div>


<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var sessionId = `{{ $data['session']->id }}`;

    $("#saveDecision").click(function(e) {
        e.preventDefault();

        var voteData = [];

        // Loop through each selected radio button to get the selected status, user_id, and corresponding decision_id
        $("input[type=radio]:checked").each(function() {
            // Extract user_id and decision_id from the 'name' attribute
            var nameAttr = $(this).attr('name');

            var matches = nameAttr.match(/status\[(\d+)\',\'(\d+)\]/);

            if (matches) {
                var userId = matches[1]; // user_id
                var decisionId = matches[2]; // decision_id

                // Check the id of the selected radio button to determine the status
                var status = 0;
                if ($(this).attr('id').includes('accept')) {
                    status = 1; // accept status
                } else if ($(this).attr('id').includes('reject')) {
                    status = 2; // reject status
                }

                // Push the vote data into the array
                voteData.push({
                    decision_id: decisionId,
                    user_id: userId,
                    status: status
                });
            }
        });


        $.ajax({
            type: "POST",
            url: `/sessions-departments/save-vote/${sessionId}`,
            data: {
                vote: voteData
            },
            success: function(response) {
                $('#closeVoteModal').click();

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
