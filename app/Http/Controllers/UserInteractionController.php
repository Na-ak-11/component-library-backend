<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;



use App\Models\UserInteraction;
use Illuminate\Http\Request;
use App\Models\Component;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use App\Service\test;

class UserInteractionController extends Controller
{
   
    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        $userInteraction = UserInteraction::where('user_id', $request->input('user_id'))->first();
        if (!$userInteraction) {
            $userInteraction = new UserInteraction;
            $userInteraction->user_id =  $request->input('user_id');
            $userInteraction->interactions = $request->input('component_id');
        }else{
            $newValue = $request->input('component_id');
            $oldValue= $userInteraction->interactions;
            $Iarray = explode(",", $oldValue);
            $updateInteraction = $Iarray ? (in_array($newValue, $Iarray) ? $oldValue : $oldValue. ',' .$newValue) : $newValue;
            $userInteraction->interactions = $updateInteraction;
        }
        $userInteraction->save();
        
        return response()-> json([
            'status'=> 200,
            'message'=>'successfully'
        ]);
        
    }
    public function singleUserInteraction($id){
        $userInteraction = UserInteraction::join('users', 'users.id', '=', 'user_Interactions.user_id')
        ->where('user_Interactions.user_id',$id)
        ->get(['user_Interactions.interactions']);
        
    
        return response()->json([
            'status'=> 200,
            'message'=>$userInteraction
        ]);
    }
    public function singleUserRecommendation($id){
        if (!is_numeric($id)) {
            return response()->json(['message' => 'Invalid user ID']);
        }
        
        $user_id = intval($id);
        $interaction  = UserInteraction::where('user_id', $user_id)->value('interactions');
        $userInteractions = UserInteraction::where('user_id', $user_id)->get();
        if (empty($interaction)) {
            return response()->json(['message' => 'No previous interactions found for this user']);
        }
          // extract component IDs from interaction
        $componentIds = explode(',', $interaction);
        $componentIds = array_map('intval', $componentIds);
        // $component = Component::whereIn('id', $componentIds)->get();

         // Get all the components that the user hasn't interacted with yet
         $unseenComponents = Component::whereNotIn('id', $componentIds)->get();

        try {
          $recommendedComponents = collect();
        foreach ($unseenComponents as $component) {
            $similarity = $this->computeSimilarity($component, $userInteractions);
            $recommendedComponents->put($component->id, $similarity);
        }

        // Sort the recommended components by similarity and return the top 5
        $recommendedComponents = $recommendedComponents->sortByDesc(function ($similarity) {
            return $similarity;
        })->take(5);

        // Get the details of the top recommended components
        $components = Component::whereIn('id', $recommendedComponents->keys()->toArray())->get();


        } catch (\Throwable $th) {
           return response()->json(['message'=>'da'.$th]);
        }



        return response()->json([
            'status'=> 200,
            'message'=> $components
        ]);
    }
    private function computeSimilarity($component, $userInteractions)
    {
        // Compute the similarity between the component and the components the user has interacted with
        $similarity = 0;
        foreach ($userInteractions as $interaction) {
            $componentIds = explode(',', $interaction->interactions);
            if (in_array($component->id, $componentIds)) {
                $similarity += similar_text($component->description, $interaction->component->description);
            }
        }
        return $similarity;
    }

}
