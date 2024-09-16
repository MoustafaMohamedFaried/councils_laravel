@extends('layouts.app')

@section('title')
    View Session
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">

            <div class="card-body">
                {{-- Session details secion --}}
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title">Session Details</h5>
                    </div>

                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-4">
                                <p><b>Code:</b> {{ $data['session']->code }}</p>
                            </div>
                            <div class="col-md-4">
                                <p><b>Order:</b> {{ $data['session']->order }}</p>
                            </div>
                            <div class="col-md-4">
                                <p><b>Department:</b> {{ $data['session']->department->ar_name }}</p>
                            </div>
                            <div class="col-md-4">
                                <p><b>Place:</b> {{ $data['session']->place }}</p>
                            </div>
                            <div class="col-md-4">
                                <p>
                                    <b>Status:</b>
                                    @if ($data['session']->status == 0)
                                        <span class="badge rounded-pill text-bg-primary">Pending</span>
                                    @elseif ($data['session']->status == 1)
                                        <span class="badge rounded-pill text-bg-success">Accepted</span>
                                    @else
                                        <span class="badge rounded-pill text-bg-danger">Rejected</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-4">
                                <p><b>Reject reason:</b> {{ $data['session']->reject_reason ?? 'ﻻ يوجد' }}</p>
                            </div>
                            <div class="col-md-4">
                                <p>
                                    <b>Decision By:</b>
                                    @if ($data['session']->decision_by == 0)
                                        Members
                                    @else
                                        Secretary of the Department Council
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-4">
                                <p><b>Created by:</b> {{ $data['session']->createdBy->name }}</p>
                            </div>
                            <div class="col-md-4">
                                <p><b>Responsible:</b> {{ $data['session']->responsible->name }}</p>
                            </div>
                            <div class="col-md-4">
                                <p><b>Total hours:</b> {{ $data['session']->total_hours }}</p>
                            </div>
                            <div class="col-md-4">
                                <p><b>Start date:</b> {{ $data['session']->start_time }}</p>
                            </div>
                            <div class="col-md-4">
                                <p><b>Schedual end date:</b> {{ $data['session']->schedual_end_time }}</p>
                            </div>
                            <div class="col-md-4">
                                <p><b>Actual start date:</b> {{ $data['session']->actual_start_time ?? 'لم يحدد بعد' }}</p>
                            </div>
                            <div class="col-md-4">
                                <p><b>Actual end date:</b> {{ $data['session']->actual_end_time ?? 'لم يحدد بعد' }}</p>
                            </div>
                            <div class="col-md-4">
                                <p><b>Created at:</b> {{ $data['session']->created_at }}</p>
                            </div>
                            <div class="col-md-4">
                                <p><b>Updated at:</b> {{ $data['session']->updated_at }}</p>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Topics secion --}}
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title">Topics</h5>
                    </div>

                    <div class="card-body">
                        <div class="row mb-3">
                            @foreach ($data['topics'] as $topic)
                                <div class="col-md-6">
                                    <ul>
                                        <li>{{ $topic }}</li>
                                    </ul>
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>

                {{-- Users & invitations secion --}}
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title">Invitations</h5>
                    </div>

                    <div class="card-body">
                        <div class="row mb-3">
                            @foreach ($data['invitations'] as $user)
                                <div class="col-md-6">
                                    <ul>
                                        <li>{{ $user }}</li>
                                    </ul>
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </div>
@endsection
