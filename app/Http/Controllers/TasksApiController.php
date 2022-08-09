<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Tasks;
use App\Models\User;

class TasksApiController extends Controller
{
    public function add(Request $request) {

        $user = User::where('api_token', $request->input('token','')) -> first();

        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|max:255|min:3',
                'note' => 'required',
                'status' => 'required|in:cancel,success,retarded,delete,doing,planned',
                'date' => 'required|date_format:Y-m-d',
                'time' => 'required|date_format:H:i:s',
                'user_id' => 'required|integer|exists:users,id'
            ]
        );

        if ($validator->fails())
        {
            // The given data did not pass validation
            return response()->json(['errors' => $validator->messages() ], 403);
        }


        $task = Tasks::create([
            'name' => $request->input('name'),
            'note' => $request->input('note'),
            'status' => $request->input('status'),
            'date' => $request->input('date'),
            'time' => $request->input('time'),
            'user_id' => $request->input('user_id'),
            'create_by_id' => $user->id
        ]);

        return response()->json(['result' => __('Tasks created successfully') , 'id' => $task], 200);
    }

    public function update(Request $request, $id) {

        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'max:255|min:3',
                'status' => 'in:cancel,success,retarded,delete,doing,planned',
                'date' => 'date_format:Y-m-d',
                'time' => 'date_format:H:i:s',
                'user_id' => 'integer|exists:users,id'
            ]
        );

        if ($validator->fails())
        {
            // The given data did not pass validation
            return response()->json(['errors' => $validator->messages() ], 403);
        }

        $task = Tasks::find($id);

        if(!$task) {
            return  response()->json(['error' => __('Tasks not found')], 404);
        }

        $task->name = $request->input('name',$task->name);
        $task->note = $request->input('note',$task->note);
        $task->status = $request->input('status',$task->status);
        $task->date = $request->input('date',$task->date);
        $task->time = $request->input('time',$task->time);
        $task->user_id = $request->input('user_id',$task->user_id );

        $task->save();

        return response()->json(['result' => $task], 200);
    }

    public function setStatus(Request $request, $id) {

        $validator = Validator::make(
            $request->all(),
            [
                'status' => 'in:cancel,success,retarded,doing,planned',
            ]
        );

        if ($validator->fails())
        {
            // The given data did not pass validation
            return response()->json(['errors' => $validator->messages() ], 403);
        }


        $user = User::where('api_token', $request->input('token',''))->first();
        $task = Tasks::where('id',$id)->where('user_id',$user->id)->first();

        if(!$task) {
            return  response()->json(['error' => __('Tasks not found')], 404);
        }

        $task->status = $request->input('status',$task->status);

        $task->save();

        return response()->json(['result' => $task], 200);
    }

    public function delete($id) {
        $task = Tasks::find($id);

        if(!$task) {
            return response()->json(['error' => __('Tasks not found')], 404);
        }

        $task->delete();

        return response()->json(['result' => __('Tasks deleted successfully')], 200);
    }
}
