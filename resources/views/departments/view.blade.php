<div class="container">
    <div class="row">
        <p class="col-md-6"><b>English Name:</b> {{ $department->en_name }}</p>
        <p class="col-md-6"><b>Arabic Name:</b> {{ $department->ar_name }}</p>
    </div>

    <div class="row">
        <p class="col-md-6"><b>Created at:</b> {{ $department->created_at }}</p>
        <p class="col-md-6"><b>Updated at:</b> {{ $department->updated_at }}</p>
    </div>

    <div class="row">
        <div class="col-md-12">
            <p><b>Faculty:</b> {{ $department->faculty->en_name }}</p>
        </div>
    </div>


</div>

