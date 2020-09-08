<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Userfollow;
use App\User;

class UserfollowController extends Controller
{
    public function userfollow(Request $request)
    {  
        $Follow = new Userfollow();
        $id=$request->input('request_user_id');
         $user=auth()->user();
         $followed_id=Userfollow::where('request_user_id',$id)->get();
         $count=0;
       if($followed_id == null )
        {
            $Follow->request_user_id=$id;
            if (auth()->user()->userfollows()->save($Follow))
              return response()->json(['sucess'=>'sucessfully followed']);
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
                    $Follow->request_user_id=$id;
                    if (auth()->user()->userfollows()->save($Follow))
                     return response()->json(['sucess'=>'sucessfully followed']);
            }

            else{
                return response()->json(['sorry'=>'you are already followed ']);
             }
           
          
        }
        else{
            return response()->json(['sorry'=>'you are already followed ']);
         }
       
    }
}
