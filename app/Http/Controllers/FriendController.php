<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use App\Friend;
use App\User;
use Mail;


class FriendController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');

            $this->middleware(['auth','verified']);

    }
  private function on_fail($error_code = "UNKNOWN_ERROR",$msg="")
  {

    $notification=array();

   $notification['status'] = false;

    $notification['message']='Something Wrong!!!.';

    $notification['texta']='error';

    $notification['title']='Error';

    echo json_encode($notification);

    exit();

}
private function on_success($response = array(),$msg="")
{

    $notification=array();

    $notification['status'] = true;

    $notification['message']=$msg;

    $notification['texta']='success';

    $notification['title']='Success';

     $notification['data']= $response;

    echo json_encode($notification);

        exit();

}

function sendmail($title,$name,$email,$subject)
{
   $data['title'] =$title ;
      $data['name'] = $name;
      Mail::send('like_email', $data, function($message) use ($email,$name,$subject) {
          $message->to($email,$name)->subject($subject);
      });

      if (Mail::failures()) 
      {
        echo "error";
       }
       else
       {
          echo "success";
       }

}
    function send_request(Request $request)
    {
        $user_id = Auth::user()->id;
        $id = $request->input('request_person_id');
        $data = array(
            'request_person_id' => $id,
            'user_id' => $user_id,
            'status' => 'Requested',
            'created_at' => date("Y-m-d")
        );
        $check_exist1 = DB::table('users')->where('id', $id)->first();
        if ($check_exist1) {
            $check_ecount = DB::table('friends')->where('request_person_id', $id)->where('user_id', $user_id)->count();
            if ($check_ecount > 0) {
                $json = (array('already send request'));
                echo json_encode($json);
            } else {
                $affected = DB::table('friends')->insert($data);
                $json = (array('succesfully'));
                echo json_encode($json);

  /* $title='You have receive connection request.';
  $name='By:'.Auth::user()->first_name.' '.Auth::user()->last_name;
  $email=$check_exist1->email;
  $subject='Enpneeds.com You have receive connection request notification.';
  $email=$this->sendmail($title,$name,$email,$subject);*/
                
              }
        } else {
            $json = (array('success' => 0));
            echo json_encode($json);
        }

    }
    Public function accept_request(Request $request)
    {
    	$id=$request->id;
    	 $user_id=Auth::user()->id;
    	$sql=DB::table('friends')->where(['friends.request_person_id'=>$user_id,'friends.user_id'=>$id])->update(['status'=>'Accepted']);
       echo($sql);
        $sqldelete=DB::table('friends')->where(['friends.request_person_id'=>$id,'friends.user_id'=>$user_id])->delete();
      
    	 if($sql)
    	 {
        $msg = "Request Accepted Successfully.";
       $this->on_success($msg);
		}
		else
		{
     	 $msg = "Something went wrong.";
      	 $this->on_fail('', $msg);
		}

    } 
    Public function request_detail(Request $request)
    {
        $id=Auth::user()->id;
        $e = DB::table('users')
        ->Leftjoin('profileimgs', 'profileimgs.user_id', '=', 'users.id')
        ->Leftjoin('profiles', 'profiles.user_id', '=', 'users.id')    
        ->where(function ($query) {
             $id = Auth::user()->id;
             $query->whereIn('users.id', DB::table('friends')->where(['user_id' => $id, 'status' => 'Requested'])->pluck('request_person_id')->toArray())
                    ->orwhereIn('users.id', DB::table('friends')->where(['request_person_id' => $id, 'status' => 'Requested'])->pluck('user_id')->toArray());
              })->select(DB::raw('CONCAT(users.first_name, " ", users.last_name) AS full_name'), 'users.id', 'users.id as user_id','profileimgs.profileimg','profiles.designation')
              ->get();                        



              return response()->json([
                'success' => true,
                
                'list' => $e
            ]);
        
    } 
    public function friend_list()
    {
       $id = Auth::user()->id;
        
      
        
                 $ids1 = DB::table('friends')->where(['user_id' => $id, 'status' => 'Accepted'])->pluck('request_person_id')->toArray();
                $ids2 = DB::table('friends')->where(['request_person_id' => $id, 'status' => 'Accepted'])->pluck('user_id')->toArray();
                        $e = DB::table('users')
                                ->Leftjoin('profileimgs', 'profileimgs.user_id', '=', 'users.id')
                                ->Leftjoin('profiles', 'profiles.user_id', '=', 'users.id')    
                                ->where(function ($query) {
                                     $id = Auth::user()->id;
                                     $query->whereIn('users.id', DB::table('friends')->where(['user_id' => $id, 'status' => 'Accepted'])->pluck('request_person_id')->toArray())
                                            ->orwhereIn('users.id', DB::table('friends')->where(['request_person_id' => $id, 'status' => 'Accepted'])->pluck('user_id')->toArray());
                                      })->select(DB::raw('CONCAT(users.first_name, " ", users.last_name) AS full_name'), 'users.id', 'users.id as user_id','profileimgs.profileimg','profiles.designation')
                                      ->get();                        
        
        
        
        return response()->json([
            'success' => true,
            
            'list' => $e
        ]);
    
        }
      
      
          

        function  send_conection(Request $request)
        {
            $user_id = Auth::user()->id;
            $id = $request->input('request_person_id');
            $check_exist = DB::table('friends')->where('request_person_id', $id)->where('user_id', $user_id)->get();
           
            $email_request = $request->input('email_request');
            $check_email = DB::table('users')->where('id', $id)->where('email', $email_request)->count();
            
            
            if ($check_email > 0) {
                $data = array(
                    'request_person_id' => $id,
                    'user_id' => $user_id,
                    'status' => 'Requested',
                    'created_at' => date("Y-m-d")
                );
                if (count($check_exist) > 0) {
                    $json = (array('success' => 1, 'msg' => "done..!"));
                    echo json_encode($json);
                } else {
                    $affected = DB::table('friends')->insert($data);
                    if ($affected) {
                        $json = (array('success' => 1, 'msg' => "done..!"));
                        echo json_encode($json);
                    } else {
                        $json = (array('success' => 0, 'msg' => "Something get Wrong..!"));
                        echo json_encode($json);
                    }
                }
            } else {
                $json = (array('success' => 2, 'msg' => "Invalid email id"));
                echo json_encode($json);
            }
        }
        Public function reject_request(Request $request)
        {
            $id=$request->id;
             $user_id=Auth::user()->id;
            $sql=DB::table('friends')->where(['friends.request_person_id'=>$user_id,'friends.user_id'=>$id])->delete();
               $count=DB::table('friends')
                ->join('users','users.id','=','friends.user_id')
                ->where(['friends.request_person_id'=>$user_id,'friends.status'=>'Requested'])
                ->count();
    
             if($sql)
             {
            $msg = "Request Rejected Successfully.";
           $this->on_success($count,$msg);
            }
            else
            {
              $msg = "Something went wrong.";
               $this->on_fail('', $msg);
            }
    
        }

        Public function suggestion_list()
        {
            $id = Auth::user()->id;
            $ids1 = DB::table('friends')->where('user_id', $id)->pluck('request_person_id')->toArray();
            $ids2 = DB::table('friends')->where('request_person_id', $id)->pluck('user_id')->toArray();
    
           
    
            $data1 = DB::table('users')
                ->Leftjoin('profileimg', 'profileimg.user_id', '=', 'users.id')
                ->select(DB::raw('CONCAT(first_name, " ", last_name) AS full_name'), DB::raw("(select status from friends where user_id=$id and request_person_id=users.id ) as followstatus"), 'users.id as user_id','profileimg.profileimg')
                ->where('users.id', "!=", $id)
                
                ->where('users.email_verified_at', "!=", null)
                ->whereNotIn('users.id', $ids1)
                ->whereNotIn('users.id', $ids2)
                ->orderBy('users.id', 'DESC')
                ->limit(15)
                ->get();
            echo json_encode($data1);
    
        }
       
    
 }