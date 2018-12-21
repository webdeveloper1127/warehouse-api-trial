<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Teacher;
use App\Student;
use App\User;

class UserController extends Controller
{
    public function __construct(){        
    }

    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' 		 => 'required',
            'email' 	 => 'required|email',
            'password'   => 'required',
            'c_password' => 'required|same:password',
            'address'	 => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 400); // HTTP_BAD_REQUEST 
        }

        $input = $request->all();
        $user  = User::where('email', '=', $input['email'])->first();
        if ($user !== null) {
            $fail['msg'] =  'User is already exist';
            return response()->json(['error'=>$fail], 400); // HTTP_BAD_REQUEST
        }

        $input['password'] = bcrypt($input['password']); //$input['password'] = Hash::make($input['password']);        
        
        $user = User::create($input);
        $input['user_id'] = $user->id;
        if (!isset($input['role']) || $input['role'] == 'Student') {
        	// Save Student	
        	Student::create($input);
        }else{
        	// Save Teacher
        	Teacher::create($input);
        }        

        $success['id']    =  $user->id;
        $success['name']  =  $user->name;
        $success['role']  =  $user->role;
        $success['mail']  =  $user->email;
        $success['token'] =  $user->createToken('MyApp')->accessToken;

        return response()->json(['success'=>$success], 201); // HTTP_CREATED
    }

    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(){
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
            $user = Auth::user();
            $success['id']    =  $user->id;
            $success['role']  = $user->role;
            $success['name']  =  $user->name;
            $success['token'] =  $user->createToken('MyApp')->accessToken;
            return response()->json(['success' => $success], 200); // HTTP_OK 
        }
        else{
            return response()->json(['error'=>'Unauthorised'], 401); // HTTP_UNAUTHORIZED
        }
    }

    /**
     * show all users
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	$user = Auth::user();
    	if ($user->role != 'Admin') return response()->json(['error' => 'Forbidden'], 403); // HTTP_FORBIDDEN  
        
        $users = User::where(function ($query) {
                $query->has('Student')
                ->orHas('Teacher');
            })
            ->with([
                'student',
                'teacher',                
            ])->get();

        return response()->json(['success' => $users], 200); // HTTP_OK 
    }
    
    /**
     * show user details
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::where('id', $id)
            ->with([
                'student',
                'teacher',
            ])->first();
            
        if (!isset($user)) {            
            return response()->json(['error'=>'Invalid id'], 400); // HTTP_BAD_REQUEST
        } 
        return response()->json(['success' => $user], 200); // HTTP_OK 
    }
    
    /**
     * Update user api
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        if ($user->role != 'Admin') return response()->json(['error' => 'Forbidden'], 403); // HTTP_FORBIDDEN 

        // Set default validations
        $validation_rules = [
            'id' => 'required',
            'name' => 'required',
            'email' => 'required|email|unique:users'
        ];
        $input = $request->all();
        
        // Password Validations
        if(isset($input['password']) && isset($input['c_password'])) {
            $validation_rules['c_password'] = 'same:password';
            $input['password'] = bcrypt($input['password']);
        }
        
        // Run Validator
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        // Get the user
        $user = User::find($input['id']); 
        if (!isset($user)) {            
            return response()->json(['error'=>'Invalid id'], 400); // HTTP_BAD_REQUEST
        }       

        $user->name = $input['name'];
        $user->email = $input['email'];
        if(isset($input['password'])) {
            $user->password = $input['password'];
        }
        // Save User
        $user->save();

        // Return the user
        return response()->json(['success' => $user], 200); // HTTP_OK 
    }
}
