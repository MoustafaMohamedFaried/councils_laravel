<div class="container">

    <div class="card mb-3 row">
        <div class="card-header">
            Faculty's Info
        </div>
        <div class="card-body row">
            <p class="col-md-6"><b>English Name:</b> {{ $faculty->en_name }}</p>
            <p class="col-md-6"><b>Arabic Name:</b> {{ $faculty->ar_name }}</p>
            <p class="col-md-6"><b>Headquarter:</b> {{ $faculty->headquarter->en_name }}</p>
            <p class="col-md-6"><b>Created at:</b> {{ $faculty->created_at }}</p>
            <p class="col-md-6"><b>Updated at:</b> {{ $faculty->updated_at }}</p>
        </div>
    </div>

    @if ($faculty->departments->isNotEmpty())
        <div class="row mb-3">
            <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown"
                data-bs-display="static" aria-expanded="false">
                Departments
            </button>
            <div class="dropdown-menu p-4 text-body">
                <p>
                <div class="row">
                    <div class="col-md-6">
                        <p>
                            <b>English Name:</b>
                            @foreach ($faculty->departments as $department)
                                <ul>
                                    <li>{{ $department->en_name }}</li>
                                </ul>
                            @endforeach
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p>
                            <b>Arabic Name:</b>
                            @foreach ($faculty->departments as $department)
                                <ul>
                                    <li>{{ $department->ar_name }}</li>
                                </ul>
                            @endforeach
                        </p>
                    </div>
                </div>
                </p>
            </div>
        </div>
    @endif

    @if ($faculty->users->isNotEmpty())
        <div class="row mb-3">
            <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown"
                data-bs-display="static" aria-expanded="false">
                Users
            </button>
            <div class="dropdown-menu p-4">
                <p>
                <div class="row">
                    <div class="col-md-6">
                        <p>
                            <b>Name:</b>
                            @foreach ($faculty->users as $user)
                                <ul>
                                    <li>{{ $user->name }}</li>
                                </ul>
                            @endforeach
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p>
                            <b>Position:</b>
                            @foreach ($faculty->users as $user)
                                <ul>
                                    <li>{{ $user->position->ar_name }}</li>
                                </ul>
                            @endforeach
                        </p>
                    </div>
                </div>
                </p>
            </div>
        </div>
    @endif

</div>

{{-- removeing the arrow icon from the dropdown button --}}
<style>
    .dropdown-toggle::after {
        display: none;
    }
</style>
