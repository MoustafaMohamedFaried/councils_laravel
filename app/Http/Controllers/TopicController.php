<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Http\Requests\StoreTopicRequest;
use App\Http\Requests\UpdateTopicRequest;

class TopicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $topics = Topic::paginate(10);
        return view('topics.index', compact('topics'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $mainTopics = Topic::whereNull('main_topic_id')->get();
        return view('topics.create', compact('mainTopics'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTopicRequest $request)
    {
        // let code contain tpc_ + random of 3 digit number
        $code = 'tpc_' . rand(100,999);

        $latestRecord = Topic::latest('id')->first();

        $latestOrder = intval($latestRecord->order ?? '0');
        $newOrder = $latestOrder + 1;

        $topic = Topic::create([
            'title' => $request->title,
            'code' => $code,
            'order' => $newOrder,
            'main_topic_id' => $request->main_topic_id,
        ]);

        $mainTopicTitle = Topic::where('id', $topic->main_topic_id)->value('title') ?? '_______';
        $type = $topic->main_topic_id ? 'Sub Topic' : 'Main Topic';
        $color = $type == 'Main Topic' ? 'success' : 'primary';

        $data = array_merge($topic->toArray(), ['color' => $color, 'type' => $type, 'mainTopicTitle' => $mainTopicTitle]);

        return response()->json(['message' => 'Topic created successfully', 'data' => $data], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Topic $topic)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Topic $topic)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTopicRequest $request, Topic $topic)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Topic $topic)
    {
        //
    }
}
