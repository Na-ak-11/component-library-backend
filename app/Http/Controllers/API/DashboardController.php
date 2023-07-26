<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Category;
use App\Models\Component;

class DashboardController extends Controller{
    public function limitedComponents(Request $request){
       $components = Component::join('users', 'users.id', '=', 'component.user_id')
       ->leftJoin('user_images', 'component.user_id', '=', 'user_images.user_id')
        ->select('component.*','users.firstname', 'user_images.filename')
        ->orderBy('component.viewes', 'desc')
        ->get(); 
        
        
        return $components;
    }

    public function dashboard(Request $request){
        $users = User::all();
        $components = Component::join('users', 'users.id', '=', 'component.user_id')
        ->leftJoin('user_images', 'component.user_id', '=', 'user_images.user_id')
        ->select('component.*','users.firstname', 'user_images.filename')
        ->get();
        $userCount = User::count();
        $categoryCount = Category::count();
        $componentCount = Component::count();

        
        $data =[
            'users'=> $users,
            'components'=> $components,
            'userCount'=> $userCount,
            'categoryCount'=> $categoryCount,
            'componentCount'=> $componentCount,
        ];
       return $data;
    }
   
}
