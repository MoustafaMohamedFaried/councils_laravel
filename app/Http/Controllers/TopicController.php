<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Http\Requests\StoreTopicRequest;
use App\Http\Requests\UpdateTopicRequest;
use Dotenv\Exception\ValidationException;
use Illuminate\Database\QueryException;

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
        try {
            // let code contain tpc_ + random of 3 digit number
            $code = 'tpc_' . rand(100, 999);

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
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($topic_id)
    {
        $topic = Topic::findOrFail($topic_id);
        return view('topics.view', compact('topic'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($topic_id)
    {
        $topic = Topic::findOrFail($topic_id);

        $mainTopics = Topic::whereNull('main_topic_id')
            ->whereNot('id', $topic->main_topic_id)
            ->get();

        $data = [
            'topic' => $topic,
            'mainTopics' => $mainTopics,
        ];

        return view('topics.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTopicRequest $request, $topic_id)
    {
        try {
            $topic = Topic::findOrFail($topic_id);

            $topic->update([
                'title' => $request->title,
                'main_topic_id' => $request->main_topic_id,
            ]);
            $mainTopicTitle = Topic::where('id', $topic->main_topic_id)->value('title') ?? '_______';

            $type = $topic->main_topic_id ? 'Sub Topic' : 'Main Topic';
            $color = $type == 'Main Topic' ? 'success' : 'primary';

            $data = array_merge($topic->toArray(), ['color' => $color, 'type' => $type, 'mainTopicTitle' => $mainTopicTitle]);

            return response()->json(['message' => 'Topic updated successfully', 'data' => $data], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($topic_id)
    {
        try {
            $topic = Topic::findOrFail($topic_id);
            $topic->delete();

            return response()->json(['message' => 'Topic deleted successfully'], 200);
        } catch (QueryException $e) {
            // Check if the error is due to a foreign key constraint
            if ($e->getCode() === '23000') {
                return response()->json([
                    'message' => "There's related data. The topic cannot be deleted.",
                ], 400);
            }

            return response()->json([
                'message' => 'An error occurred',
                'errors' => $e->getMessage(),
            ], 500);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        }
    }
    public function getSupTopics($mainTopicId)
    {
        $supTopic = Topic::where('main_topic_id',$mainTopicId)->get();
        return response()->json($supTopic);
    }

    public function getMainTopics($supTopicId)
    {
        $mainTopic = Topic::where('main_topic_id',$supTopicId)->get();
        return response()->json($mainTopic);
    }
}
