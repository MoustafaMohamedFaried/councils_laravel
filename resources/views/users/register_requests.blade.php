@extends('layouts.app')

@section('title')
    Register Requests
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">

            <div class="card">
                <div class="card-header row">
                    <h6 class="col-md-11">Register Requests</h6>
                </div>

                <div class="card-body">
                    @if ($users->isNotEmpty())
                        <table class="table table-striped table-hover">
                            <thead class="text-center">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="table_body" id="userContainer">
                                @php $x = 0; @endphp
                                @foreach ($users as $user)
                                    @php $x++; @endphp
                                    <tr id="user_{{ $user->id }}">
                                        <th class="text-center" scope="row">{{ $x }}</th>
                                        <td class="text-center">{{ $user->name }}</td>
                                        <td class="text-center">{{ $user->email }}</td>
                                        <td class="text-center">
                                            <a class="btn btn-secondary btn-sm" role="button" id="viewUserBtn"
                                                data-user-id="{{ $user->id }}" data-bs-toggle="modal"
                                                data-bs-target="#viewModal">View</a>

                                            <a class="btn btn-success btn-sm decisionBtn" role="button"
                                                data-user-id="{{ $user->id }}" data-decision="1">Accept</a>

                                            <a class="btn btn-danger btn-sm decisionBtn" role="button"
                                                data-user-id="{{ $user->id }}" data-decision="2">Reject</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $users->links('pagination::bootstrap-5') }}
                    @else
                        <p class="text-center text-danger">No register requests</p>
                    @endif
                </div>

                <!-- View Modal -->
                <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="viewModalLabel">View User</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body" id="viewFormContent">
                                <!-- view User form -->
                            </div>
                        </div>
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

        $(document).on('click', '#viewUserBtn', function() {

            var userId = $(this).data('user-id'); // Get the user ID from the clicked button
            $.ajax({
                type: "GET",
                url: `/users/${userId}`,
                success: function(response) {
                    $('#viewFormContent').html(response);
                }
            });
        });

        $(document).ready(function() {
            $('.decisionBtn').click(function(e) {
                e.preventDefault();
                var userId = $(this).data('user-id');
                var decision = $(this).data('decision');

                // Conditional check based on decision (1 for Accept, 2 for Reject)
                if (decision == 1) {
                    var message = "Are you sure you want to accept this user?";
                } else if (decision == 2) {
                    var message = "Are you sure you want to reject this user?";
                }

                // Confirm action before sending AJAX request
                if (confirm(message)) {
                    // Get the route from Blade and replace the userId in the URL
                    var url = `{{ route('users.registerRequestDecision', ':id') }}`;
                    url = url.replace(':id', userId);

                    // Proceed with AJAX request after confirmation
                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: {
                            user_id: userId,
                            decision: decision,
                            _token: "{{ csrf_token() }}" // Laravel CSRF token for security
                        },
                        success: function(response) {

                            $(`#user_${userId}`).remove();

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
                            alert(
                                'An error occurred. Please try again.'
                                ); // Handle error response
                        }
                    });
                }
            });
        });
    </script>

@endsection
