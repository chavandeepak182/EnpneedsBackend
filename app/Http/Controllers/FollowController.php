<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\User;
use App\Follow;

class FollowController extends Controller
{
    public function Follow(Request $request)
    {  
        $Follow = new Follow();
        $id=$request->input('company_id');
         $user=auth()->user();
         $followed_id=Follow::where('company_id',$id)->get();
         $count=0;
       if($followed_id == null )
        {
            $Follow->company_id=$id;
            if (auth()->user()->follows()->save($Follow))
              return response()->json(['success'=>'sucessfully followed']);
        }
        elseif($followed_id !=null)   
        {
            
            for($i=0;$i<count($followed_id);$i++)   
             {
                $follow_data=$followed_id[$i];
                  $user_data= $follow_data->user_id;
                   if($user->id == $user_data)
                     {
                      $count++;
                      }
            }
            if($count==0){
                    $Follow->company_id=$id;
                    if (auth()->user()->follows()->save($Follow))
                     return response()->json(['success'=>'sucessfully followed']);
            }

            else{
                return response()->json(['sorry'=>'you are already followed ']);
             }
           
          
        }
        else{
            return response()->json(['sorry'=>'you are already followed ']);
         }
       
    }
    
    public function showbycompanyid($id)
    {
       
        $Follow=Follow::where('company_id','=', $id)->get();
       
        if(count($Follow)<1){
            return response()->json([
                'success' => false,
                'message' => ' comment with id ' . $id . ' not found'
            ]);
        }
       
             
        return response()->json([
            'success' => true,
            'data' => $Follow
        ], 200);
    
    }
    public  function deletebycompanyid($id)
    {
       
        $Follow=Follow::where('company_id','=', $id);
     
      $Follow->delete();
            return response()->json([
                'success' => true,
                'message' => ' deleted'
            ]);
       
       
             
       
    }
}
