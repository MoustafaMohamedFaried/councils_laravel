<div class="container">

    <div class="row">
        <p class="col-md-6"><b>Code:</b> {{ $topic->code }}</p>
        <p class="col-md-6"><b>Order:</b> {{ $topic->order }}</p>
    </div>

    <div class="row">
        <p class="col-md-6"><b>Title: </b> {{ $topic->title }}</p>
        @if ($topic->main_topic_id)
            <p class="col-md-6"><b>Main topic title: </b> {{ $topic->mainTopic->title }}</p>
        @endif
    </div>

    <div class="row">
        <p class="col-md-6"><b>Created at:</b> {{ $topic->created_at }}</p>
        <p class="col-md-6"><b>Updated at:</b> {{ $topic->updated_at }}</p>
    </div>


</div>
