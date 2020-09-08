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
            echo($id);
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
   
    
}
