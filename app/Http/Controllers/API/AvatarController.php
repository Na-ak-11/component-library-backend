<?php

namespace App\Http\Controllers\API;

use App\Models\Avatar;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AvatarController extends Controller
{
    public function create(){

        $avatars=Avatar::all();
        return view('avatar', compact('avatars'));
    }

    public function store(Request $request)
    {
        $name=$request->file('avatar')->getClientOriginalName();
        $size=$request->file('avatar')->getSize();

        $request->file('avatar')->storeAs('public/avatar', $name);
        $avatar= new Avatar();
        $avatar->name=$name;
        $avatar->size=$size;
        $avatar->save();
        return redirect()->back();
    }

    public function view($id)
    {
        $data = Avatar::find($id);
        if($data)
        {
            return response()->json([
                'status'=> 200,
                'avatar' => $data,
            ]);
        }
        else
        {
            return response()->json([
                'status'=> 404,
                'message' => 'No  Avatar Found',
            ]);
        }

    }


    public function uploadImage(Request $request){
        if($request->hasFile('avatar')){
             $file= $request->file('avatar');
             $filename=$file->getClientOriginalName('avatar');
             $filename=date('his') .$filename;

             $request->file('avatar')->storeAs('avatar/', $filename, 'public');

             return response()->json(["message"=>"uploaded"]);

        }else{
            return response()->json(["message"=>"select image"]);
        }
    }
}
