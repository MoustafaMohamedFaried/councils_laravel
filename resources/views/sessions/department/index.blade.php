@extends('layouts.app')

@section('title')
    Session Department
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">

            <div class="card">
                <div class="card-header row">
                    <h6 class="col-md-11">sessions</h6>
                    {{-- if user position is secretary of department council --}}
                    @if (auth()->user()->position_id == 2)
                        <a class="col-md-1 btn btn-success btn-sm" href="{{ route('sessions-departments.create') }}" type="button">Create</a>
                    @endif
                </div>

                <div class="card-body">
                    @if ($data['sessions']->isNotEmpty())
                        <table class="table table-striped table-hover">
                            <thead class="text-center">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Code</th>
                                    <th scope="col">Type</th>
                                    {{-- <th scope="col">order</th> --}}
                                    {{-- <th scope="col">Main session</th> --}}
                                    <th scope="col">Title</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="table_body" id="sessionContainer">
                                @php $x = 0; @endphp
                                @foreach ($data['sessions'] as $session)
                                    @php $x++; @endphp
                                    <tr id="session_{{ $session->id }}">
                                        <th class="text-center" scope="row">{{ $x }}</th>
                                        <td class="text-center">{{ $session->code }}</td>
                                        <td class="text-center">
                                            @if ($session->status == 0)
                                                <span class="badge rounded-pill text-bg-primary">Pending</span>
                                            @elseif ($session->status == 1)
                                                <span class="badge rounded-pill text-bg-success">Accepted</span>
                                            @else
                                                <span class="badge rounded-pill text-bg-danger">Rejected</span>
                                            @endif
                                        </td>
                                        {{-- <td class="text-center">{{ $session->order }}</td> --}}
                                        {{-- <td class="text-center">{{ $session->mainsession->title ?? '_______' }}</td> --}}
                                        <td class="text-center">{{ $session->title }}</td>
                                        <td class="text-center">
                                            <a class="btn btn-secondary btn-sm" role="button" id="viewsessionBtn"
                                                data-session-id="{{ $session->id }}" data-bs-toggle="modal"
                                                data-bs-target="#viewModal">View</a>
                                            @if (auth()->user()->hasRole('Super Admin'))
                                                <a class="btn btn-primary btn-sm" role="button" id="editsessionBtn"
                                                    data-session-id="{{ $session->id }}" data-bs-toggle="modal"
                                                    data-bs-target="#editModal">Edit</a>

                                                <a class="btn btn-danger btn-sm" role="button" id="deletesessionBtn"
                                                    data-session-id="{{ $session->id }}" data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal">Delete</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $data['sessions']->links('pagination::bootstrap-5') }}
                    @else
                        <p class="text-center text-danger">No sessions</p>
                    @endif
                </div>

                <!-- Delete Modal -->
                <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <b> Are you sure you want to delete this record? </b>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
@endsection
