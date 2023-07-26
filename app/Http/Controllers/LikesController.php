<?php

namespace App\Http\Controllers;

use App\Models\Component;
use App\Models\User;
use App\Models\Likes;
use Illuminate\Http\Request;

class LikesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function getUserLikedComponent($itemId)
    {
        // Get the item by ID
        $user = User::find($itemId);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Get components the user liked
        $liked_items = $user->likes()
                    ->join('component', 'likes.component_id', '=', 'component.id')
                    ->join('users', 'users.id', '=', 'component.user_id')
                    ->leftJoin('user_images', 'component.user_id', '=', 'user_images.user_id')
                    ->select('component.*','users.firstname','user_images.filename')
                    ->get();
        $liked_ids = $user->likes()
                    ->join('component', 'likes.component_id', '=', 'component.id')
                    ->select('component.id')
                    ->get()->pluck('id');

        return response()->json([
            'likedComponents' => $liked_items,
            'likedComponentsId'=>$liked_ids
    ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request )
    {
        $item_id = $request->input('id');
        $user_id = $request->input('user_id');
        $like = $request->input('like');
        $likedItem = Likes::where('component_id', $item_id)
        ->where('user_id', $user_id)
        ->first();
        if ($like) {
            if (!$likedItem) {
                $likedItem = new Likes();
                $likedItem->component_id = $item_id;
                $likedItem->user_id = $user_id;
                $likedItem->save();
                return response()->json(['status' => 'liked']);
            }
        } else {
            if ($likedItem) {
                $likedItem->delete();
                return response()->json(['status' => 'unliked']);
            }
        }
       

        return response()->json(['status' => 'no change']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Likes  $likes
     * @return \Illuminate\Http\Response
     */
    public function show(Likes $likes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Likes  $likes
     * @return \Illuminate\Http\Response
     */
    public function edit(Likes $likes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Likes  $likes
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Likes $likes)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Likes  $likes
     * @return \Illuminate\Http\Response
     */
    public function destroy(Likes $likes)
    {
        //
    }
}
