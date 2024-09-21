<div class="container">
    @foreach ($data['topics'] as $topic_id => $topic_title)
        <div class="mb-3">
            <p>
                <b class="text-danger">Topic title:</b>
                {{ $topic_title }}
            </p>

            <div class="form-floating">
                <textarea class="form-control" name="decision[{{ $topic_id }}]" id="Decision" placeholder="Add decision"
                    style="height: 100px">{{ trim($data['decision'][$topic_id] ?? '') }}</textarea>
                <label for="Decision">Decision</label>
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

        var sessionId = `{{ $data['session']->id }}`;

        // Clear the array before collecting the values
        decisionsArray = [];

        // Loop through all the textareas with the name attribute "decision[...]"
        $('textarea[name^="decision["]').each(function() {
            // Get the value of the textarea
            var decisionValue = $(this).val();

            // Get the name attribute and extract the topic_id
            var nameAttr = $(this).attr('name');
            var topicId = nameAttr.match(/\[(.*?)\]/)[1]; // Extract the topic_id

            // Create an object with topic_id and decisionValue
            var decisionData = {
                agenda_id: topicId,
                decision: decisionValue
            };

            // Add the decisionData to the array
            decisionsArray.push(decisionData);
        });

        $.ajax({
            type: "POST",
            url: `/sessions-departments/save-decision/${sessionId}`,
            data: {
                decisions: decisionsArray
            },
            success: function(response) {
                $('#closeDecisionModal').click();

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
