@extends('layouts.app')

@section('title')
    Topics
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">

            <div class="card">
                <div class="card-header row">
                    <h6 class="col-md-11">topics</h6>
                    @if (auth()->user()->hasRole('Super Admin'))
                        <a class="col-md-1 btn btn-success btn-sm" id="createTopicBtn" type="button" role="button"
                            data-bs-toggle="modal" data-bs-target="#createModal">Create</a>
                    @endif
                </div>

                <div class="card-body">
                    @if ($topics->isNotEmpty())
                        <table class="table table-striped table-hover">
                            <thead class="text-center">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Code</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">order</th>
                                    <th scope="col">Main Topic</th>
                                    <th scope="col">Title</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="table_body" id="topicContainer">
                                @php $x = 0; @endphp
                                @foreach ($topics as $topic)
                                    @php $x++; @endphp
                                    <tr id="topic_{{ $topic->id }}">
                                        <th class="text-center" scope="row">{{ $x }}</th>
                                        <td class="text-center">{{ $topic->code }}</td>
                                        <td class="text-center">
                                            @if (!$topic->main_topic_id)
                                                <span class="badge rounded-pill text-bg-success">Main Topic</span>
                                            @else
                                                <span class="badge rounded-pill text-bg-primary">Sup Topic</span>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $topic->order }}</td>
                                        <td class="text-center">{{ $topic->mainTopic->title ?? '_______' }}</td>
                                        <td class="text-center">{{ $topic->title }}</td>
                                        <td class="text-center">
                                            <a class="btn btn-secondary btn-sm" role="button" id="viewTopicBtn"
                                                data-topic-id="{{ $topic->id }}" data-bs-toggle="modal"
                                                data-bs-target="#viewModal">View</a>
                                            @if (auth()->user()->hasRole('Super Admin'))
                                                <a class="btn btn-primary btn-sm" role="button" id="editTopicBtn"
                                                    data-topic-id="{{ $topic->id }}" data-bs-toggle="modal"
                                                    data-bs-target="#editModal">Edit</a>

                                                <a class="btn btn-danger btn-sm" role="button" id="deleteTopicBtn"
                                                    data-topic-id="{{ $topic->id }}" data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal">Delete</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $topics->links('pagination::bootstrap-5') }}
                    @else
                        <p class="text-center text-danger">No topics</p>
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

                <!-- Create Modal -->
                <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="createModalLabel">Create topic</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" id="closeCreateModal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body" id="createFormContent">
                                <!-- create topic form -->

                            </div>
                        </div>
                    </div>
                </div>

                <!-- View Modal -->
                <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="viewModalLabel">View topic</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body" id="viewFormContent">
                                <!-- view topic form -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Modal -->
                <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="editModalLabel">Edit topic</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" id="closeEditModal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body" id="editFormContent">
                                <!-- edit topic form -->
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

        $("#createTopicBtn").click(function(e) {
            e.preventDefault();
            $.ajax({
                type: "GET",
                url: "{{ route('topics.create') }}",
                success: function(response) {
                    $("#createFormContent").html(response);
                }
            });
        });
    </script>

@endsection
