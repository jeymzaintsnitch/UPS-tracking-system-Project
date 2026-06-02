<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{   
    public function index() 
    {
        $users = DB::table('users')
            ->leftJoin('user_types', 'users.user_types_id', 'user_types.id')
            ->select('users.*', 'user_types.name as user_type_name', 
            'user_types.display_name as user_type_display_name')
            ->get();
            // return $users;
        

        $user_types = DB::table('user_types')->get();
        return view('login', compact('users', 'user_types'));
    }

    public function store(Request $request)
    {
        Log::debug("============Please Login==============");
        
        $request->validate(
        [
            'firstname' => ['required'],
            'lastname'  => ['required'],
            'useremail' => ['required', 'email', 'ends_with:gmail.com'],
            'password'  => ['required']
        ], 
            [
            'firstname.required'=>'Kailangan mo ito inaka',
            'useremail.required'=>'Need ng email tangina mo!',
            'password.required'=>'Password ito'
            ]

        );
 
        DB::table('users')->insert([
            'first_name'  => $request->firstname,
            'middle_name' => $request->middlename,
            'last_name'   => $request->lastname,
            'email'       => $request->useremail,
            'status_id' => 1,
            'user_types_id' => $request->UserTypes,
            'created_at'  => now(),
            'password'    => Hash::make($request->password)
        ]);

        Log::info("Registered First Name: " . $request->firstname);
        Log::info("Registered Middle Name: " . $request->middlename);
        Log::info("Registered Last Name: " . $request->lastname);
        Log::info("Registered Email: " . $request->useremail);
        
        Log::debug("[User Registration] Process Completed Successfully");

        return redirect()->route('user.viewWeb');
    }
    public function formEdit($id) {
        Log::info("User ID: " . $id);
    
        $user = DB::table('users')->where('id', $id)->first();

        $user_types = DB::table('user_types')->get();
        return view('form-edit', compact('user_types', 'user'));







    }
}