<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Validator;

class TaskController extends Controller
{
    public function index()
    {
        $task =  Task::all(); 
        $data = [
            'status'=>200, 
            'tasks'=>$task
        ];
        return response()->json($data,200);

    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:Low,Medium,High',
            'deadline' => 'required|date',
        ]);

        if($validator->fails()){
            $data =[
                'status'=>422,
                'message'=>$validator->messages()
            ];
            return response()->json($data,422);
        }else{
            $task = new Task();
            $task->title = $request->title;
            $task->description = $request->description;
            $task->priority = $request->priority;
            $task->deadline = $request->deadline;
            $task->status = $request->status;
            $task->save();

            $data = [
                'status'=>201,
                'task'=>$task
            ];

            return response()->json($data,201);
        }
    }

    public function filterByPriority(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'priority' => 'required|in:Low,Medium,High',
        ]);

        if ($validator->fails()) {
            $data = [
                'status' => 422,
                'message' => $validator->messages()
            ];
            return response()->json($data, 422);
        } else {
            $tasks = Task::where('priority', $request->priority)->get();
            $data = [
                'status' => 200,
                'tasks' => $tasks
            ];
            return response()->json($data, 200);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),
        [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:Low,Medium,High',
            'deadline' => 'required|date',
        ]);

        if($validator->fails()){
            $data =[
                'status'=>400,
                'message'=>$validator->messages()
            ];
            return response()->json($data,400);
        }else{
            $task = Task::find($id);
            $task->title = $request->title;
            $task->description = $request->description;
            $task->priority = $request->priority;
            $task->deadline = $request->deadline;
            $task->save();

            $data = [
                'status'=>201,
                'task'=>$task
            ];

            return response()->json($data,201);
        }
    }

    public function delete($id)
    {
        $task = Task::find($id);
        $task->delete();

        $data = [
            'status'=>200,
            'message'=>'Task deleted successfully'
        ];

        return response()->json($data,200);
    }
}
