<form id="CreateForm">
    @csrf
    <input type="hidden" id="facultyId" name="faculty_id" value="{{ $data['faculty_id'] }}">

    <div class="form-floating mb-3">
        <input type="hidden" name="dean_of_college" value="{{ $data['deanOfCollege']->value('id') }}">
        <input type="text" class="form-control" id="deanOfCollege" placeholder="Dean of college"
            value="{{ $data['deanOfCollege']->value('name') }}" readonly>
        <label for="deanOfCollege">Dean of college</label>
    </div>

    <div class="form-floating mb-3">
        <input type="hidden" name="secretary_of_college_council"
            value="{{ $data['secretaryOfCollegeCouncil']->value('id') }}">
        <input type="text" class="form-control" id="secretaryOfCollegeCouncil" readonly
            value="{{ $data['secretaryOfCollegeCouncil']->value('name') }}" placeholder="Secretary of college council">
        <label for="secretaryOfCollegeCouncil">Secretary of college council</label>
    </div>

    <div class="form-floating mb-3">
        <select class="form-select" id="memberId" name="member_id">
            @if ($data['selectedCouncilMembers'])
                <option disabled selected value>Select Members</option>
                <option value="{{ $data['selectedCouncilMembers']->value('id') }}" selected>
                    {{ $data['selectedCouncilMembers']->value('name') }}</option>
            @else
                <option disabled selected value>Select Members</option>
            @endif
            @foreach ($data['members'] as $member)
                <option value="{{ $member->id }}"> {{ $member->name }}</option>
            @endforeach
        </select>
        <label for="memberId">Members</label>
    </div>

    <div class="form-floating mb-3">
        <button type="submit" class="btn btn-primary" id="submitCreateForm">Submit</button>
    </div>

</form>


<script>
    $("#submitCreateForm").click(function(e) {
        e.preventDefault();
        var facultyId = document.getElementById('facultyId').value;

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
            url: `/faculty-councils/formate/${facultyId}`,
            data: {
                faculty_id: formData.faculty_id,
                dean_of_college: formData.dean_of_college,
                secretary_of_college_council: formData.secretary_of_college_council,
                members: formData.member_id,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#closeCouncilModal').click();

                toastr.options = {
                    "closeButton": true,
                    "progressBar": true,
                    "positionClass": "toast-top-right",
                    "timeOut": "3500",
                    "preventDuplicates": true,
                    "extendedTimeOut": "1000"
                };

                toastr.success(response.message);

                // Ensure councilContainer is a jQuery object
                var councilContainer = $('#councilContainer');

                // Clear the existing content before appending new rows
                councilContainer.html('');

                // Loop through each item in the response data
                $.each(response.data, function(index, council) {
                    // Create a new row for each council member
                    var newRow = `
                        <tr id="councilRow">
                            <th class="text-center" scope="row">${index + 1}</th>
                            <td class="text-center">${council.user_name}</td>
                            <td class="text-center">${council.position}</td>
                        </tr>
                    `;

                    // Append the new row to the council container (which should be a <tbody>)
                    councilContainer.append(newRow);
                });
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
