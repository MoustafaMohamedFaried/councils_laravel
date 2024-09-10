<div class="container">

    <div class="row">
        <p class="col-md-6"><b>Code:</b> {{ $agenda->code }}</p>
        <p class="col-md-6"><b>Order:</b> {{ $agenda->order }}</p>
    </div>

    <div class="row">
        <p class="col-md-6"><b>Created By: </b> {{ $agenda->uploader->name }}</p>
        <p class="col-md-6"><b>Status: </b>
            @if ($agenda->status == 1)
                <span class="badge rounded-pill text-bg-success">Accepted</span>
            @elseif ($agenda->status == 2)
                <span class="badge rounded-pill text-bg-danger">Rejected</span>
            @else
                <span class="badge rounded-pill text-bg-primary">Pending</span>
            @endif
        </p>
    </div>

    <div class="row">
        <p class="col-md-12"><b>Topic title: </b> {{ $agenda->topic->title }}</p>
    </div>

    <div class="row">
        <p class="col-md-6"><b>Faculty:</b> {{ $agenda->department->faculty->ar_name }}</p>
        <p class="col-md-6"><b>Department:</b> {{ $agenda->department->ar_name }}</p>
    </div>

    <div class="row">
        <p class="col-md-6"><b>Created at:</b> {{ $agenda->created_at }}</p>
        <p class="col-md-6"><b>Updated at:</b> {{ $agenda->updated_at }}</p>
    </div>

</div>
