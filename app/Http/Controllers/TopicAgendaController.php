<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTopicAgendaRequest;
use App\Http\Requests\UpdateTopicAgendaRequest;
use App\Models\{
    Department,
    TopicAgenda,
    Faculty,
    Topic
};
use Dotenv\Exception\ValidationException;
use Illuminate\Database\QueryException;

class TopicAgendaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('is_active');
        // $this->middleware('is_super_or_system_admin')->except('index', 'show', 'getFacultiesByHeadquarter');
        $this->middleware('ajax_only')->except('index');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $agendas = TopicAgenda::paginate(10);
        return view('agendas.index', compact('agendas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $faculties = Faculty::get();
        $mainTopics = Topic::whereNull('main_topic_id')->get();

        $data = [
            'faculties' => $faculties,
            'mainTopics' => $mainTopics,
        ];

        return view('agendas.create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTopicAgendaRequest $request)
    {
        try {
            // let code contain tpc_ + random of 3 digit number
            $code = 'req_' . rand(100, 999);

            $latestRecord = TopicAgenda::latest('id')->first();

            $latestOrder = intval($latestRecord->order ?? '0');
            $newOrder = $latestOrder + 1;

            $departmentCode = Department::where('id', $request->department_id)->value('code');
            $topicTitle = Topic::where('id', $request->topic_id)->value('title');

            $agendaName = $newOrder . '/ ' . $topicTitle . ' /' . $departmentCode;

            $agenda = TopicAgenda::create([
                'created_by' => auth()->id(),
                'department_id' => $request->department_id,
                'topic_id' => $request->topic_id,
                'code' => $code,
                'order' => $newOrder,
                'name' => $agendaName
            ]);

            $data = [
                'agenda' => $agenda,
                'created_by' => $agenda->uploader->name,
                'topic_title' => $agenda->topic->title,
            ];

            return response()->json(['message' => 'Agenda created successfully', 'data' => $data], 200);
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
    public function show($agenda_id)
    {
        $agenda = TopicAgenda::findOrFail($agenda_id);
        return view('agendas.view', compact('agenda'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($agenda_id)
    {
        $agenda = TopicAgenda::findOrFail($agenda_id);
        $faculties = Faculty::get();
        $mainTopics = Topic::whereNull('main_topic_id')->get();

        $data = [
            'agenda' => $agenda,
            'faculties' => $faculties,
            'mainTopics' => $mainTopics,
        ];

        return view('agendas.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTopicAgendaRequest $request, $agenda_id)
    {
        try {
            $agenda = TopicAgenda::findOrFail($agenda_id);

            $newTopicTitle = Topic::where('id',$request->topic_id)->value('title');
            $newDepartmentCode = Department::where('id',$request->department_id)->value('code');

            $agendaName = $agenda->order . '/ ' . $newTopicTitle . ' /' . $newDepartmentCode;

            $agenda->update([
                'department_id' => $request->department_id,
                'topic_id' => $request->topic_id,
                'status' => $request->status,
                'name' => $agendaName
            ]);

            $data = [
                'agenda' => $agenda,
                'created_by' => $agenda->uploader->name,
                'topic_title' => $newTopicTitle,
            ];

            return response()->json(['message' => 'Agenda updated successfully', 'data' => $data], 200);
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
    public function destroy($agenda_id)
    {
        try {
            $agenda = TopicAgenda::findOrFail($agenda_id);
            $agenda->delete();

            return response()->json(['message' => 'Agenda deleted successfully'], 200);
        } catch (QueryException $e) {
            // Check if the error is due to a foreign key constraint
            if ($e->getCode() === '23000') {
                return response()->json([
                    'message' => "There's related data. The agenda cannot be deleted.",
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

}
