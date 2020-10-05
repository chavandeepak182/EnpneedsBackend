<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Profile;
use App\Profileimgs;
use Auth;
use App\Friend;
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
        $users=User::where('id','=',$id)->get();
       
     
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
        $photos=User::with('profilephoto','coverphotos')->where('id','=',$id->id)->select('id')->get();
        $videos=User::with('post.postvideos')->where('id','=',$id->id)->select('id')->get();
     
        return response()->json([
            'success' => true,
            'data' => $photos,
            'video'=>$videos
        ]);
    
}
public function friend_listByUser($id)
{
 
                    $e = DB::table('users')
                            ->Leftjoin('profileimgs', 'profileimgs.user_id', '=', 'users.id')
                            ->Leftjoin('profiles', 'profiles.user_id', '=', 'users.id')    
                            ->where(function ($query) use ($id) {
                              
                                 $query->whereIn('users.id', DB::table('friends')->where(['user_id' => $id, 'status' => 'Accepted'])->pluck('request_person_id')->toArray())
                                        ->orwhereIn('users.id', DB::table('friends')->where(['request_person_id' => $id, 'status' => 'Accepted'])->pluck('user_id')->toArray());
                                  })->select(DB::raw('CONCAT(users.first_name, " ", users.last_name) AS full_name'), 'users.id', 'users.id as user_id','profileimgs.profileimg','profiles.designation')
                                  ->get();                        
    
    
    
    return response()->json([
        'success' => true,
        
        'list' => $e
    ]);

    }
   
    
}
