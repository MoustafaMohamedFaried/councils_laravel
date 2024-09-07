<div class="container">
    <div class="card mb-3">
        <div class="card-header">
            Personal Info
        </div>
        <div class="card-body row">
            <p class="col-md-6"><b>Name:</b> {{ $user->name }}</p>
            <p class="col-md-6"><b>Email:</b> {{ $user->email }}</p>
            <p class="col-md-6"><b>Status:</b>
                @if ($user->is_active == 1)
                    <span class="badge rounded-pill text-bg-success">Active</span>
                @elseif ($user->is_active == 2)
                    <span class="badge rounded-pill text-bg-primary">Pending</span>
                @else
                    <span class="badge rounded-pill text-bg-danger">Not Active</span>
                @endif
            </p>
            <p class="col-md-6"><b>Created at:</b> {{ $user->created_at }}</p>
            <p class="col-md-6"><b>Updated at:</b> {{ $user->updated_at }}</p>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">
            Related Info
        </div>
        <div class="card-body row">
            <p class="col-md-6"><b>Role:</b> {{ $user->role->name ?? 'صلاحية' }}</p>
            <p class="col-md-6"><b>Position:</b> {{ $user->position->ar_name ?? 'بدون منصب' }}</p>
            <p class="col-md-6"><b>Faculty:</b> {{ $user->faculty->ar_name }}</p>
            <p class="col-md-6"><b>Headquarter:</b> {{ $user->headquarter->ar_name }}</p>
        </div>
    </div>

</div>

{{-- removeing the arrow icon from the dropdown button --}}
<style>
    .dropdown-toggle::after {
        display: none;
    }
</style>
