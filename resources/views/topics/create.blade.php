<form id="CreateForm">
    @csrf
    <div class="form-floating mb-3">
        <input type="text" class="form-control" id="topicTitle" name="title" placeholder="Topic Title">
        <label for="topicTitle">Title</label>
    </div>

    <div class="form-floating mb-3">
        <select class="form-select" id="topicId" name="main_topic_id">
            <option value>Select Main Topic</option>
            @foreach ($mainTopics as $mainTopic)
                <option value="{{ $mainTopic->id }}"> {{ $mainTopic->title }}</option>
            @endforeach
        </select>
        <label for="mainTopic">Main Topic</label>
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
            url: "{{ route('topics.store') }}",
            data: {
                title: formData.title,
                main_topic_id: formData.main_topic_id,
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

                // Ensure topicContainer is a jQuery object
                var topicContainer = $('#topicContainer');

                // Check if there are any existing rows
                var existingRowsCount = topicContainer.find('tr').length;

                // Calculate the next index count based on the number of rows
                var nextIndex = existingRowsCount > 0 ? existingRowsCount + 1 : 1;

                // Append the new row to the faculty container (which should be a <tbody>)
                topicContainer.append(`
                        <tr id="topic_${response.data.id}">
                            <th class="text-center" scope="row">${nextIndex}</th>
                            <td class="text-center">${response.data.code}</td>
                            <td class="text-center">
                                <span class="badge rounded-pill text-bg-${response.data.color}">${response.data.type}</span>
                            </td>
                            <td class="text-center">${response.data.order}</td>
                            <td class="text-center">${response.data.mainTopicTitle}</td>
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
