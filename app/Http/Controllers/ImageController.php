<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Http\Response;

class ImageController extends Controller
{
    public function storeUserImage(Request $request, $id) 
{
    $image = $request->file('image');
    
    
    if (!$image) {
        return response()->json(['message' => 'No image file uploaded'], 400);
    }

    
    if (!$image->isValid()) {
        return response()->json(['message' => 'Invalid image file'], 400);
    }

    $inputImage = $id.time().$image->getClientOriginalName();
    $filename = str_replace(' ', '', $inputImage);
    $oldFile = UserImage::where('user_id', $id)->first();
    if($oldFile){
        try {
            if(Storage::disk('public')->exists('images/'.$filename)){
                $oldFile->filename = $filename;
                
            }elseif (Storage::disk('public')->exists('images/'.$oldFile->filename)) {
                Storage::disk('public')->delete('images/'.$oldFile->filename);
                Storage::disk('public')->put('images/' . $filename, file_get_contents($image));
                $oldFile->filename = $filename;
            }else{
                Storage::disk('public')->put('images/' . $filename, file_get_contents($image));
                $oldFile->filename = $filename;
        
            }
            $oldFile->update(); 
        } catch (\Throwable $th) {
            return response()->json(['message' => 'err'.$th]);
        }
       
    }else{
        Storage::disk('public')->put('images/' . $filename, file_get_contents($image));
        $userImage = new UserImage;
        $userImage->filename = $filename;
        $userImage->user_id = $id;
        $userImage->save();
    }
   

    

    return response()->json(['message' => 'Image uploaded successfully'], 200);
}

public function getImage($id){
    $user = UserImage::where('user_id', $id)->first();
    if(!$user){
        return response()->json(['message' => 'user image not found'], 400);
    }
    $image_id = $user->filename;

    if(Storage::disk('public')->exists('images/'.$image_id)){
        $filePath = Storage::disk('public')->get('images/'.$image_id);
        $response = new Response($filePath, 200);
        $response->header('Content-Type', 'image/jpeg');
        
        return Storage::url('images/'.$image_id);
        // return $response;
    }else{
        return response()->json([], 500);
    }
  
  
}

}
