<?php

namespace App\Http\Controllers;
use Config;
use App\post;
use App\comment;
use App\reply;
use App\postimages;
use App\postvideo;
use App\postlike;
use App\postdislike;
use App\User;

use App\Profileimgs;
use Illuminate\Http\Request;
class PostCommentReplyController extends Controller
{  
    public function index()
    {
   
    $books = post::with('comments.users','comments.replies.users','comments.profileimgs','comments.replies.profileimgs','users','profileimgs','postimages','postvideos')
    ->withcount('comments')->orderBy('id','desc')
    ->paginate(20);
    
   
    return response()->json([
    'data' => $books
    ], 200);
    
    
    }
    public function show()
    {
        $id = auth()->user();
       
        $books = post::with('comments.users','comments.replies.users','comments.profileimgs','comments.replies.profileimgs','users','profileimgs','postimages','postvideos')
        ->withcount('comments')->orderBy('id','desc')->where('user_id','=',$id->id)
        ->paginate(20);
        
       
        return response()->json([
        'data' => $books
        ], 200);
   
}
public function showby($id)
{
    
   
    $books = post::with('comments.users','comments.replies.users','comments.profileimgs','comments.replies.profileimgs','users','profileimgs','postimages','postvideos')
    ->withcount('comments')->orderBy('id','desc')->where('user_id','=',$id)
    ->paginate(20);
    
   
    return response()->json([
    'data' => $books
    ], 200);

}
}


