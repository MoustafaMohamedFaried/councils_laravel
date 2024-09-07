@extends('layouts.app')

@section('title')
    Register
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Register') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="row mb-3">
                                <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                                <div class="col-md-6">
                                    <input id="name" type="text"
                                        class="form-control @error('name') is-invalid @enderror" name="name"
                                        value="{{ old('name') }}" required autocomplete="name" autofocus>

                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>


                            <div class="row mb-3">
                                <label for="email"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') }}" required autocomplete="email">

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>


                            <div class="row mb-3">
                                <label for="headquarterId" class="col-md-4 col-form-label text-md-end">Headquarter</label>

                                <div class="col-md-6">

                                    <select class="form-select" id="headquarterId" name="headquarter_id">
                                        <option disabled selected value>Choose headquarter</option>
                                        @foreach ($data['headquarters'] as $headquarter)
                                            <option value="{{ $headquarter->id }}">{{ $headquarter->ar_name }}</option>
                                        @endforeach
                                    </select>

                                    @error('headquarter_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>


                            <div class="row mb-3">
                                <label for="facultyId" class="col-md-4 col-form-label text-md-end">Faculty</label>

                                <div class="col-md-6">

                                    <select class="form-select" id="facultyId" name="faculty_id" disabled>
                                        <option disabled selected value>Choose Faculty</option>
                                        @foreach ($data['faculties'] as $faculty)
                                            <option value="{{ $faculty->id }}">{{ $faculty->ar_name }}</option>
                                        @endforeach
                                    </select>

                                    @error('faculty_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>


                            <div class="row mb-3">
                                <label for="departmentId" class="col-md-4 col-form-label text-md-end">Department</label>

                                <div class="col-md-6">

                                    <select class="form-select" id="departmentId" name="department_id" disabled>
                                        <option disabled selected value>Choose Department</option>
                                        @foreach ($data['departments'] as $department)
                                            <option value="{{ $department->id }}">{{ $department->ar_name }}</option>
                                        @endforeach
                                    </select>

                                    @error('department_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>


                            <div class="row mb-3">
                                <label for="password"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        required autocomplete="new-password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>


                            <div class="row mb-3">
                                <label for="password-confirm"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control"
                                        name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Register') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // let faculty dependance on headquarter
        $('#headquarterId').change(function() {
            var headquarterId = $(this).val();

            if (headquarterId) {
                $('#facultyId').prop('disabled', false); // Enable the faculty select field

                // Make an AJAX request to get faculties related to the selected headquarter
                $.ajax({
                    url: `/faculties/get-faculties/${headquarterId}`,
                    type: 'GET',
                    success: function(faculties) {
                        // Clear the previous options
                        $('#facultyId').empty().append(
                            '<option disabled selected value>Select Faculty</option>');

                        // Populate the faculties select field with new options
                        $.each(faculties, function(index, faculty) {
                            $('#facultyId').append(
                                `<option value="${faculty.id}">${faculty.ar_name}</option>`);
                        });
                    },
                    error: function() {
                        console.error('Failed to fetch faculties');
                    }
                });
            } else {
                $('#facultyId').prop('disabled', true).empty().append(
                    '<option disabled selected value>Select Faculty</option>'
                ); // Disable and reset faculty select field
            }
        });


        // let department dependance on faculty
        $('#facultyId').change(function() {
            var facultyId = $(this).val();

            if (facultyId) {
                $('#departmentId').prop('disabled', false); // Enable the department select field

                // Make an AJAX request to get departments related to the selected faculty
                $.ajax({
                    url: `/departments/get-departments/${facultyId}`,
                    type: 'GET',
                    success: function(departments) {
                        // Clear the previous options
                        $('#departmentId').empty().append(
                            '<option disabled selected value>Select Department</option>');

                        // Populate the departments select field with new options
                        $.each(departments, function(index, department) {
                            $('#departmentId').append(
                                `<option value="${department.id}">${department.ar_name}</option>`);
                        });
                    },
                    error: function() {
                        console.error('Failed to fetch departments');
                    }
                });
            } else {
                $('#departmentId').prop('disabled', true).empty().append(
                    '<option disabled selected value>Select department</option>'
                ); // Disable and reset department select field
            }
        });

    </script>
@endsection
