<form id="CreateForm">
    @csrf
    <input type="hidden" id="departmentId" name="department_id" value="{{ $data['department_id'] }}">

    <div class="form-floating mb-3">
        <select class="form-select" id="headOfDepartment" name="head_of_department">
            @if ($data['selectedHeadOfDepartment'])
                <option disabled selected value>Select Members</option>
                <option value="{{ $data['selectedHeadOfDepartment']->value('user_id') }}" selected>
                    {{ $data['selectedHeadOfDepartment']->value('user_name') }}</option>
            @else
                <option disabled selected value>Select head of department</option>
            @endif
            @foreach ($data['headOfDepartment'] as $userId => $name)
                <option value="{{ $userId }}"> {{ $name }}</option>
            @endforeach
        </select>
        <label for="headOfDepartment">Head of department</label>
    </div>

    <div class="form-floating mb-3">
        <select class="form-select" id="secretaryOfDepartmentCouncil" name="secretary_of_department_council">
            @if ($data['selectedSecretaryOfDepartmentCouncil'])
                <option disabled selected value>Select Members</option>
                <option value="{{ $data['selectedSecretaryOfDepartmentCouncil']->value('user_id') }}" selected>
                    {{ $data['selectedSecretaryOfDepartmentCouncil']->value('user_name') }}</option>
            @else
                <option disabled selected value>Select secretary of department council</option>
            @endif
            @foreach ($data['secretaryOfDepartmentCouncil'] as $userId => $name)
                <option value="{{ $userId }}"> {{ $name }}</option>
            @endforeach
        </select>
        <label for="secretaryOfDepartmentCouncil">Secretary of department council</label>
    </div>

    <div class="form-floating mb-3">
        <select class="form-select" id="memberId" name="member_id">
            @if ($data['selectedCouncilMembers'])
                <option disabled selected value>Select Members</option>
                <option value="{{ $data['selectedCouncilMembers']->value('user_id') }}" selected>
                    {{ $data['selectedCouncilMembers']->value('user_name') }}</option>
            @else
                <option disabled selected value>Select Members</option>
            @endif
            @foreach ($data['members'] as $userId => $name)
                <option value="{{ $userId }}"> {{ $name }}</option>
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
        var departmentId = document.getElementById('departmentId').value;

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
            url: `/department-councils/formate/${departmentId}`,
            data: {
                department_id: formData.department_id,
                head_of_department: formData.head_of_department,
                secretary_of_department_council: formData.secretary_of_department_council,
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
