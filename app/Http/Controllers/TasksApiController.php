<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Tasks;
use App\Models\User;

class TasksApiController extends Controller
{
    protected function failedValidation(Validator $validator)
    {
        throw (new ValidationException($validator))
                    ->errorBag($this->errorBag)
                    ->redirectTo($this->getRedirectUrl());
    }

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

        $task = Tasks::find($id);

        if(!$task) {
            return  response()->json(['error' => __('Tasks not found')], 404);
        }

        $task->name = $request->input('name');
        $task->note = $request->input('note');
        $task->status = $request->input('status');
        $task->date = $request->input('date');
        $task->time = $request->input('time');
        $task->user_id = $request->input('user_id');

        $task->save();

        return response()->json(['result' => $task], 201);
    }

    // function delete($id) {
    //     $note = Note::find($id);

    //     if(!$note) {
    //         response()->json(['error' => 'item not found'], 404);
    //     }

    //     $note->delete();

    //     return response()->json(['result' => 'item deleted successfully'], 201);
    // }
}
