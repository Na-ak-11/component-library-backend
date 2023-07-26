<?php

namespace App\Http\Controllers\API;
use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Component;

class ComponentController extends Controller
{
    public function getComponent(){
        $component = Component::join('users', 'users.id', '=', 'component.user_id')
        ->leftJoin('user_images', 'component.user_id', '=', 'user_images.user_id')
        ->select('component.*', 'user_images.filename')
         ->get();
   
        $numOfComponents = Component::count();
        $numOfPages = $numOfComponents / 10 ;
        return response()->json([
            'status'=> 200,
            'component'=>$component,
            'totalComponents' => $numOfComponents,
            'numOfPages' => $numOfPages,
            'component'=> $component,
        ]);
    }

    public function add(Request $request){
         // validation
         $formFields = $request->validate([
            'category_id'=> 'required',
            'user_id'=> 'required',
            'name' => ['required', 'min:3'],
            'code_referance',
            'viewes',
            'likes'
        ]);
       
        $fileName = time(). $formFields['name']. $formFields['user_id'];
        $string = str_replace(' ', '', $fileName);
        // $formFields['discription'] =  '';
        $formFields['code_referance'] =  $string;
        $formFields['viewes'] = 0;
        $formFields['likes'] = 0;
        $component = new Component;
        $component->category_id =  $formFields['category_id'];
        $component->user_id =  $formFields['user_id'];
        $component->code_referance =   $formFields['code_referance'];
        $component->discription =  $request->input('discription');
        $component->viewes =  $formFields['viewes'];
        $component->name =  $formFields['name'];
        $component->likes =  $formFields['likes'];
        if($component->save()){
            Storage::disk('public')->put('/Js/'.$string.'.js' , $request->input('code'));
            Storage::disk('public')->put('/Css/'.$string.'.css' , $request->input('css'));
            Storage::disk('public')->put('/Html/'.$string.'.html' , $request->input('html'));
        };

        return response()-> json([
            'status'=> 200,
            'message'=>'component added successfully'
        ]);

    }
    public function singleComponent($id){
        $component = Component::join('users', 'users.id', '=', 'component.user_id')
        ->leftJoin('user_images', 'component.user_id', '=', 'user_images.user_id')
        ->join('category', 'category.id', '=', 'component.category_id')
        ->where('component.id',$id)
        ->select('component.*','users.firstname', 'category.title', 'user_images.filename')
        ->get();

        if($component){
            // error_log("here".$component[0]->code_referance);

            $comId = $component[0]->code_referance;

            if(Storage::disk('public')->exists('/Html/'.$comId.'.html')){
                $componentHTML = Storage::disk('public')->get('/Html/'.$comId.'.html');
            }else{
                $componentHTML = "";
            }
            if(Storage::disk('public')->exists('/Css/'.$comId.'.css')){
                $componentCSS = Storage::disk('public')->get('/Css/'.$comId.'.css');
            }else{
                $componentCSS = "";
            }
            if(Storage::disk('public')->exists('/Js/'.$comId.'.js')){
                $componentJS = Storage::disk('public')->get('/Js/'.$comId.'.js');
            }else{
                $componentJS = "";
            }
    
            $componentCode = [
                "HTML" => $componentHTML,
                "CSS" => $componentCSS,
                "JS" => $componentJS,
            ];
    
            return response()-> json([
                'status'=> 200,
                'message'=> $component,
                'code' => $componentCode,
            ]);
        }else{
            return response()-> json([
                'status'=> 200,
                'message'=> "",
                'code' => "",
            ]);
        }

    }
    public function getCode($id){
        if(Storage::disk('public')->exists('/Html/'.$id.'.html')){
            $componentHTML = Storage::disk('public')->get('/Html/'.$id.'.html');
        }else{
            $componentHTML = "";
        }
        if(Storage::disk('public')->exists('/Css/'.$id.'.css')){
            $componentCSS = Storage::disk('public')->get('/Css/'.$id.'.css');
        }else{
            $componentCSS = "";
        }
        if(Storage::disk('public')->exists('/Js/'.$id.'.js')){
            $componentJS = Storage::disk('public')->get('/Js/'.$id.'.js');
        }else{
            $componentJS = "";
        }

        $component = [
            "HTML" => $componentHTML,
            "CSS" => $componentCSS,
            "JS" => $componentJS,
        ];

        return response()-> json([
            'status'=> 200,
            'message'=> $component,
        ]);
    }

    public function singleCategoryComponent($id){
        $component = Component::join('users', 'users.id', '=', 'component.user_id')
        ->leftJoin('user_images', 'component.user_id', '=', 'user_images.user_id')
        ->join('category', 'category.id', '=', 'component.category_id')
        ->where('component.category_id',$id)
        ->orderBy('component.viewes', 'desc')
        ->select('component.*','users.firstname', 'category.title', 'user_images.filename')
        ->get();
        return response()-> json([
            'status'=> 200,
            'message'=> $component,
        ]);
    }

    public function singleUserComponent($id){
        $component = Component::join('users', 'users.id', '=', 'component.user_id')
        ->leftJoin('user_images', 'component.user_id', '=', 'user_images.user_id')
        ->join('category', 'category.id', '=', 'component.category_id')
        ->where('component.user_id',$id)
        ->select('component.*','users.firstname', 'category.title', 'user_images.filename')
        ->get();
        return response()-> json([
            'status'=> 200,
            'message'=> $component,
        ]);
    }
    public function updateComponentview(Request $request, $id){
        $component = Component::find($id);
        $component->viewes =  $component->viewes + 1 ;
        $component->update($request->only('viewes'));
        return response()-> json([
            'status'=> 200,
            'message'=>'component updated successfully'
        ]);
    }
    
    public function updateComponentlike(Request $request, $id){
        $component = Component::find($id);
        $component->likes =  $component->likes + 1 ;
        $component->update($request->only('likes'));
        return response()-> json([
            'status'=> 200,
            'message'=>'component updated successfully'
        ]);
    }

    public function updateComponent(Request $request, $id){
        $component = Component::find($id);
        $component->category_id = empty($request->input('category_id'))? $component->category_id : $request->input('category_id');
        $component->user_id = empty($request->input('user_id'))? $component->user_id : $request->input('user_id');
        $component->name = empty($request->input('name'))? $component->name: $request->input('name');
        $component->discription = empty($request->input('discription'))? $component->discription: $request->input('discription');
        $component->viewes = empty($request->input('viewes'))? $component->viewes: $component->viewes + 1 ;
        $component->likes = empty($request->input('likes'))? $component->likes: $request->input('likes');
        
        $fileName = time(). $component->name. $component->user_id;
        $string = str_replace(' ', '', $fileName);
        if($request->input('code')){
            if(Storage::disk('public')->exists('/js/'.$component->code_referance.'.js')){
                Storage::disk('public')->put('/js/'.$component->code_referance.'.js', $request->input('code'));
                $component->code_referance = $component->code_referance;
            }else{
                Storage::disk('public')->put('/Js/'.$string.'.js' , $request->input('code'));
                $component->code_referance = $string;
            }
        }
        if($request->input('css')){
            if(Storage::disk('public')->exists('/Css/'.$component->code_referance.'.css')){
                Storage::disk('public')->put('/Css/'.$component->code_referance.'.css', $request->input('css'));
                $component->code_referance = $component->code_referance;
            }else{
                Storage::disk('public')->put('/Css/'.$string.'.css' , $request->input('css'));
                $component->code_referance = $string;
            }
        }
        if($request->input('html')){
            if(Storage::disk('public')->exists('/Html/'.$component->code_referance.'.html')){
                Storage::disk('public')->put('/Html/'.$component->code_referance.'.html', $request->input('html'));
                $component->code_referance = $component->code_referance;
            }else{
                Storage::disk('public')->put('/Html/'.$string.'.html' , $request->input('html'));
                $component->code_referance = $string;
            }
        }
        
        $component->update();

        return response()-> json([
            'status'=> 200,
            'message'=>'component updated successfully'
        ]);

    }
    public function deleteComponent($id){
        $component = Component::find($id);
        if($component){
            if(Storage::disk('public')->exists('/js/'.$component->code_referance.'.js')){
                Storage::disk('public')->delete('/js/'.$component->code_referance.'.js');
            }
            if(Storage::disk('public')->exists('/Css/'.$component->code_referance.'.css')){
                Storage::disk('public')->delete('/Css/'.$component->code_referance.'.css');
            }
            if(Storage::disk('public')->exists('/Html/'.$component->code_referance.'.html')){
                Storage::disk('public')->delete('/Html/'.$component->code_referance.'.html');
            }
            $component->delete();
                return response()-> json([
                    'status'=> 200,
                    'message'=>'component delated successfully'
                ]);
        }else{
            return response()-> json([
                'status'=> 404,
                'message'=>'not able to find the component'
            ]);
        }
    }
   
}
