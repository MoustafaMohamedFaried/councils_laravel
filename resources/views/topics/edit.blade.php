<form id="editForm">
    @csrf
    <input type="hidden" id="topicId" name="topic_id" value="{{ $data['topic']->id }}">
    <div class="form-floating mb-3">
        <input type="text" class="form-control" id="topicTitle" name="title" placeholder="Topic Title"
            value="{{ $data['topic']->title }}">
        <label for="topicTitle">Title</label>
    </div>

    <div class="form-floating mb-3">
        <select class="form-select" id="topicId" name="main_topic_id">
            <option value>Select Main Topic</option>
            @if ($data['topic']->main_topic_id)
                <option value='{{ $data['topic']->main_topic_id }}' selected>{{ $data['topic']->mainTopic->title }}</option>
            @endif
            @foreach ($data['mainTopics'] as $mainTopic)
                <option value="{{ $mainTopic->id }}"> {{ $mainTopic->title }}</option>
            @endforeach
        </select>
        <label for="mainTopic">Main Topic</label>
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

        var topicId = document.getElementById('topicId').value;

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
            url: `/topics/${topicId}`,
            data: {
                topic_id: topicId,
                title: formData.title,
                main_topic_id: formData.main_topic_id,
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

                var topicContainer = $('#topicContainer');

                var topicRow = $('#topic_' + topicId);

                var thElement = topicRow.find('th[scope="row"]');

                var rowIndex = thElement.text();

                topicRow.html(
                    `
                        <th class="text-center" scope="row">${rowIndex}</th>
                        <td class="text-center">${response.data.code}</td>
                        <td class="text-center">
                            <span class="badge rounded-pill text-bg-${response.data.color}">${response.data.type}</span>
                        </td>
                        <!--
                        <td class="text-center">${response.data.order}</td>
                        <td class="text-center">${response.data.mainTopicTitle}</td> -->
                        <td class="text-center">${response.data.title}</td>
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
