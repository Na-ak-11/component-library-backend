<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class CommentController extends Controller
{
    public function store(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'body' => 'required|string',
        //     'component_id' => 'required|integer',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json(['errors' => $validator->errors()], 422);
        // }

        $comment = new Comment();

        $comment->user_id = $request->input('user_id');


        $comment->body = $request->input('body');
        $comment->component_id = $request->input('component_id');
        $comment->save();

        return response()->json(['comment' => $comment]);
    }

    public function index($id)
    {
        // $comments = Comment::where('component_id', $id)->get();
        $comments = Comment::with('user')->where('component_id', $id)
        ->leftJoin('user_images', 'comments.user_id', '=', 'user_images.user_id')
        ->get();

        foreach ($comments as $comment) {
            $comment->user_data = $comment->user;
            unset($comment->user);
        }

        return response()->json(['comments' => $comments]);


        // return response()->json(['comments' => $comments]);
    }
}
