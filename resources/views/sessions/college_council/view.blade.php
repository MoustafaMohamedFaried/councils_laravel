{{-- Session details secion --}}
<div class="card mb-3">
    <div class="card-header">
        <h5 class="card-title">
            <span>Session Details</span>
        </h5>
    </div>

    <div class="card-body">

        <div class="row">
            <div class="col-md-4">
                <p><b>Code:</b> {{ $data['session']->code }}</p>
            </div>

            <div class="col-md-4">
                <p><b>Place:</b> {{ $data['session']->place }}</p>
            </div>

            <div class="col-md-4">
                <p><b>Responsible:</b> {{ $data['session']->responsible->name }}</p>
            </div>
        </div>

    </div>
</div>

{{-- Topics secion --}}
<div class="card mb-3">
    <div class="card-header">
        <h5 class="card-title">
            <span>Topics</span>
        </h5>
    </div>

    <div class="card-body">

        <div class="row">
            @foreach ($data['collegeCouncil'] as $collegeCouncil)
                <div class="col-md-6">
                    <p><b>Title:</b> {{ $collegeCouncil->agenda->name }}</p>
                </div>
                <div class="col-md-3">
                    <p><b>Status:</b>
                        @if ($collegeCouncil->status == 0)
                            <span class="badge rounded-pill text-bg-primary">Pending</span>
                        @elseif ($collegeCouncil->status == 1)
                            <span class="badge rounded-pill text-bg-success">Accepted</span>
                        @else
                            <span class="badge rounded-pill text-bg-danger">Rejected</span>
                        @endif
                    </p>
                </div>
                <div class="col-md-3">
                    <p><b>Reject reason:</b> <span class="text-danger">{{ $collegeCouncil->reject_reason ?? 'لا يوجد' }}</span></p>
                </div>
            @endforeach
        </div>

    </div>
</div>
