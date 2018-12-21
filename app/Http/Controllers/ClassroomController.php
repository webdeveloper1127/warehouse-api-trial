<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Classroom;
use App\User;

class ClassroomController extends Controller
{
    public function __construct(){        
    }

    /**
     * show all classrooms
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {        
        $classrooms = Classroom::all();

        return response()->json(['success' => $classrooms], 200); // HTTP_OK 
    }

    /**
     * show classroom detail
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $classroom = Classroom::find($id);

        if (!isset($classroom)) {            
            return response()->json(['error'=>'Invalid id'], 400); // HTTP_BAD_REQUEST
        } 
        return response()->json(['success' => $classroom], 200); // HTTP_OK 
    }

    /**
     * Register classroom
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request){
    	$user = Auth::user();
    	if ($user->role != 'Admin') return response()->json(['error' => 'Forbidden'], 403); // HTTP_FORBIDDEN  

        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 400); // HTTP_BAD_REQUEST 
        }

        $input = $request->all();
        
        $classroom = Classroom::create($input);        

        return response()->json(['success'=>$classroom], 201); // HTTP_CREATED
    }

    /**
     * Update classroom
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        if ($user->role != 'Admin') return response()->json(['error' => 'Forbidden'], 403); // HTTP_FORBIDDEN 

        $validator = Validator::make($request->all(), [
            'id'   => 'required',	
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 400); // HTTP_BAD_REQUEST 
        }

        $input = $request->all();

        // Get the classroom
        $classroom = Classroom::find($input['id']); 
        if (!isset($classroom)) {            
            return response()->json(['error'=>'Invalid id'], 400); // HTTP_BAD_REQUEST
        }       

        $classroom->name = $input['name'];

        // Save classroom
        $classroom->save();

        // Return the classroom
        return response()->json(['success' => $classroom], 200); // HTTP_OK 
    }

    public function destroy($id)
    {
    	$user = Auth::user();
        if ($user->role != 'Admin') return response()->json(['error' => 'Forbidden'], 403); // HTTP_FORBIDDEN 

        // Get the classroom
        $classroom = Classroom::findOrfail($id);        
        $classroom->delete();

        return response()->json(['success' => 'Removed'], 200); // HTTP_OK  
    }
}
