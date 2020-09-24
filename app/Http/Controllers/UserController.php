<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Profile;
use App\Profileimgs;
use Auth;
use DB;

class UserController extends Controller
{
    
    public function show()
        {   
            $id = Auth::user()->id;
            
            $users = User::select('first_name','last_name','id')->where('id','!=',$id)->with('profileimg')->get();
         

            return response()->json([
                'success' => true,
                'data' => $users
                
            ]);
        }
    public function getUser($id)
    {
        $users=User::with('education','experiences','profileimg','abouts')->where('id','=',$id)->get();
       
     
        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }
    public function update(Request $request)
    {
       
        
        $users = auth()->user();
        
        if (!$users) {
            return response()->json([
                'success' => false,
                'message' => 'User with id ' . $id . ' not found'
            ], 400);
        }
 
        $updated = $users->fill($request->all())->save();
 
        if ($updated)
            return response()->json([
                'success' => true,
                'data'=>$users
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'user could not be updated'
            ], 500);
    }
    public function getUserphoto()
    {
         
            $id = auth()->user();
        $photos=User::with('profilephoto','coverphotos','postimages')->where('id','=',$id->id)->get();
        $videos=User::with('postvideos')->where('id','=',$id->id)->get();
     
        return response()->json([
            'success' => true,
            'data' => $photos,
            'video'=>$videos
        ]);
    
}
   
    
}
