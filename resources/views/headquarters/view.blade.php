<div class="container">

    <div class="row">
        <p class="col-md-6"><b>English Name:</b> {{ $headquarter->en_name }}</p>
        <p class="col-md-6"><b>Arabic Name:</b> {{ $headquarter->ar_name }}</p>
    </div>

    <div class="row">
        <p class="col-md-12"><b>Address: </b> {{ $headquarter->address }}</p>
    </div>

    <div class="row">
        <p class="col-md-6"><b>Created at:</b> {{ $headquarter->created_at }}</p>
        <p class="col-md-6"><b>Updated at:</b> {{ $headquarter->updated_at }}</p>
    </div>

    @if ($headquarter->faculties->isNotEmpty())
        <div class="row mb-3">
            <button type="button" class="btn btn-secondary" onclick="toggleVisibility('facultiesSecion')">
                Faculties
            </button>
        </div>

        <div class="row">
            <div class="dropdown-menu p-4 text-body" id="facultiesSecion">
                <p>
                <div class="row">
                    <div class="col-md-6">
                        <p>
                            <b>English Name:</b>
                            @foreach ($headquarter->faculties as $faculty)
                                <ul>
                                    <li>{{ $faculty->en_name }}</li>
                                </ul>
                            @endforeach
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p>
                            <b>Arabic Name:</b>
                            @foreach ($headquarter->faculties as $faculty)
                                <ul>
                                    <li>{{ $faculty->ar_name }}</li>
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

<script>
    function toggleVisibility(sectionId) {
        // Select the target div
        var section = document.getElementById(sectionId);

        // Toggle display property
        if (section.style.display === 'none' || section.style.display === '') {
            // Hide all sections first
            document.querySelectorAll('.dropdown-menu').forEach(function(div) {
                div.style.display = 'none';
            });

            // Then show the clicked section
            section.style.display = 'block';
        } else {
            // Hide the section if it's already visible
            section.style.display = 'none';
        }
    }
</script>
