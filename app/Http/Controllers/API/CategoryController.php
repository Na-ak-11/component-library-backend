<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function getCategory(){
        $category = Category::all();
        return response()-> json([
            'status'=> 200,
            'category'=> $category
        ]);
    }
    public function store(Request $request){
        $category = new Category;
        $category->title = $request->input('title');
        $category->discription = $request->input('discription');
        $category->save();

        return response()-> json([
            'status'=> 200,
            'message'=>'category added successfully'
        ]);

    }
    public function singleCategory($id){
        $category = Category::find($id);
        return response()-> json([
            'status'=> 200,
            'message'=> $category
        ]);
    }
    public function update(Request $request, $id){
        $category = Category::find($id);
        $category->title = $request->input('title');
        $category->discription = $request->input('discription');
        $category->update();

        return response()-> json([
            'status'=> 200,
            'message'=>'category updated successfully'
        ]);

    }
    public function delete($id){
        $category = Category::find($id);
        $category->delete();
        return response()-> json([
            'status'=> 200,
            'message'=>'category delated successfully'
        ]);
    }
    public function searchCategory($searchQuery){
        $category = Category::where([
            ['title', '!=', NULL],[
                function($query) use($searchQuery){
                        $query->orWhere('title', 'LIKE', '%'.$searchQuery.'%')->get();
                }
            ]
        ])
        ->orderBy("id", "desc")
        ->paginate(10);

        return response()-> json([
            'status'=> 200,
            'message'=>$category
        ]);
    }
}
