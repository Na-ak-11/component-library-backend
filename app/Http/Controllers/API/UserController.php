<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\CssSelector\Node\FunctionNode;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function register(Request $request){
        // validation
        $formFields = $request->validate([
            'firstname'=> ['required', 'min:3'],
            'lastname'=> ['required', 'min:3'],
            'email'=> ['required', 'email', Rule::unique('users', 'email')],
            'password' => 'required|min:8',
            'role',
            'github',
            'linkedin',
        ]);

        // hash password
        $formFields['password'] = bcrypt($formFields['password']);
        // assign default value
        $formFields['role'] = 'user';
        $formFields['github'] = '';
        $formFields['linkedin'] = '';
        
        // create user 
        $user = User::create($formFields);
        // login
        if($user){
            // create token
            $token = $user->createToken('token')->plainTextToken;
    
            // create cookie
            $cookie = cookie('jwt', $token, 60);//1 day;
            return response($user)->withCookie($cookie);
        }
    }

    public function login(Request $request){
        if(!Auth::attempt($request->only('email', 'password'))){
            return response([
                'message' => 'Invalid credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = Auth::user();

        // create token
        $token = $user->createToken('token')->plainTextToken;

        // create cookie
        $cookie = cookie('jwt', $token, 60);//1 day;
        return response($user)->withCookie($cookie);
    }

    public function user(){
        return Auth::user();
    }
    public function users(){
        $numOfUsers = User::count();
        $numOfPages = $numOfUsers / 10 ;
        return response()->json([
            'users'=>User::all(),
            'totalUsers' => $numOfUsers,
            'numOfPages' => $numOfPages,
        ]);
    }
    // single user
    public function singleUser($id){
        $user = User::find($id);
        return response()->json($user);
    }
    public function logout(Request $request){
        $cookie = Cookie::forget('jwt');
        return response([
            'message'=> 'success'       
        ])->withCookie($cookie);
    }

    // public function edit($id)
    // {
    //     $data = User::find($id);
    //     if($data)
    //     {
    //         return response()->json([
    //             'status'=> 200,
    //             'user' => $data,
    //         ]);
    //     }
    //     else
    //     {
    //         return response()->json([
    //             'status'=> 404,
    //             'message' => 'No User ID Found',
    //         ]);
    //     }

    // }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'firstname'=>'required|max:191',
            'lastname'=>'required|max:191',
            'github'=>'required|email|max:191',
            'linkedin'=>'required|email|max:191',
            'email'=>'required|email|max:191',
            
        ]);

        $data = User::find($id);
        if($data)
        {
            $data->firstname= $request->input('firstname');
            $data->lastname= $request->input('lastname');
            $data->github= $request->input('github');
            $data->linkedin= $request->input('linkedin');
            // $data->password= $request->input('password');
            $data->email= $request->input('email');
            
            $data->update();

            return response()->json([
                'status'=> 200,
                'message'=>'User Updated Successfully',
            ]);
        }
        else
        {
            return response()->json([
                'status'=> 404,
                'message' => 'No user ID Found',
            ]);
        }
        if ($request->hasFile('uimage')) {
            $file = $request->file('uimage');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images'), $filename);
            $data->uimage = $filename;
        }

        $data->save();
        
        return response()->json([
            'status' => 200,
            'message' => 'Profile updated successfully'
        ]);
    }

    

    
    
}
