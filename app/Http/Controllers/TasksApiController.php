<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tasks;
use App\Models\User;

class TasksApiController extends Controller
{

    function add(Request $request) {

        $user = User::where('api_token', $request->input('token','')) -> first();

        $this->validate($request, [
            'name' => 'required|max:255|min:3',
            'note' => 'required',
            'state' => 'required|in:cancel,success,retarded,delete,doing',
            'date' => 'required|date_format:Y-m-d',
            'time' => 'required|date_format:H:i:s',
            'user_id' => 'required|integer'
        ]);


        $task = Tasks::create([
            'name' => $request->input('name'),
            'note' => $request->input('note'),
            'state' => $request->input('state'),
            'date' => $request->input('date'),
            'time' => $request->input('time'),
            'user_id' => $request->input('user_id'),
            'create_by_id' => $user->id
        ]);

        return response()->json(['result' => __('Tasks created successfully') , 'id' => $task], 200);
    }

    // function update(Request $request, $id) {
    //     $this->validate($request, [
    //         'title' => 'required|max:64|min:3',
    //         'content' => 'required|max:256'
    //     ]);

    //     $note = Note::find($id);

    //     if(!$note) {
    //         response()->json(['error' => 'item not found'], 404);
    //     }

    //     $note->title = $request->input('title');
    //     $note->content = $request->input('content');

    //     $note->save();

    //     return response()->json(['result' => $note], 201);
    // }

    // function delete($id) {
    //     $note = Note::find($id);

    //     if(!$note) {
    //         response()->json(['error' => 'item not found'], 404);
    //     }

    //     $note->delete();

    //     return response()->json(['result' => 'item deleted successfully'], 201);
    // }
}
