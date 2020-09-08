<?php

namespace App\Http\Controllers;
use App\comment;
use App\post;
use DB;
use App\User;
use App\Profileimgs;
use App\reply;
use App\postimages;
use App\postvideo;
use App\commentlike;
use App\commentdislike;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    public function index()
    {
       // $comments = auth()->user()->comments;
       $comments ::orderBy('created_at', 'desc')->paginate(2);

        return response()->json([
            'success' => true,
            'data' => $comments
        ]);
    }
 

    public function show($id)
    {
        $comment = auth()->user()->comments()->find($id);
 
        if (!$comment) {
            return response()->json([
                'success' => false,
                'message' => 'Comment with id ' . $id . ' not found'
            ], 400);
        }
 
        return response()->json([
            'success' => true,
            'data' => $comment->toArray()
        ], 200);
    }
 
    public function store(Request $request)
    {
        $this->validate($request, [
            'comment' => 'required',
            'post_id' => 'required',
            
        ]);
 
        $comment = new comment();
        $comment->comment = $request->comment;
        $comment->post_id = $request->post_id;
        $comment->commentlikecount = 0;
        $comment->commentdislikecount = 0;

        if (auth()->user()->comments()->save($comment))
        {
            $books = comment::with('replies','profiles','users')->where('id','=',$comment->id)->get();
            return response()->json([
                'success' => true,
                'data' => $books
                
            ]);
            }
    
        else
            return response()->json([
                'success' => false,
                'message' => 'Comment could not be added'
            ], 500);
    }
 
    public function update(Request $request, $id)
    {
        Log::info('Update comment: '.$id);
        Log::info('Request: '.$request);
        $comment = auth()->user()->comments()->find($id);
 
        if (!$comment) {
            return response()->json([
                'success' => false,
                'message' => 'Comment with id ' . $id . ' not found'
            ], 400);
        }
 
        $updated = $comment->fill($request->all())->save();
 
        if ($updated)
            return response()->json([
                'success' => true
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Comment could not be updated'
            ], 500);
    }
 
    public function destroy($id)
    {
        $comment = auth()->user()->comments()->find($id);
 
        if (!$comment) {
            return response()->json([
                'success' => false,
                'message' => 'Comment with id ' . $id . ' not found'
            ], 400);
        }
 
        if ($comment->delete()) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Comment could not be deleted'
            ], 500);
        }
    }
    public function showbypostid($id)
    {
       
        $comment=comment::where('post_id','=', $id)->get();
       
        if(count($comment)<1){
            return response()->json([
                'success' => false,
                'message' => ' comment with id ' . $id . ' not found'
            ]);
        }
       
             
        return response()->json([
            'success' => true,
            'data' => $comment
        ], 200);
    
    }

    Public function likes(Request $request){

        $comment_id = $request->comment_id;
        $userInfo = auth()->user();

        $check = commentlike::where('comment_id','=',$comment_id)->where('user_id','=',$userInfo->id)->first();
        if(empty($check))
        {

            $inser_data['comment_id'] = $comment_id;
            $inser_data['user_id'] = $userInfo->id;


            commentlike::create($inser_data);

            $previousCommentLike = DB::table('comments')->where('id',$comment_id)->first();
            $previousCommentLike = json_decode(json_encode($previousCommentLike),true);
            
            $previousCommentLikeCount = $previousCommentLike['commentlikecount'];
       

          $newCommentLikeCount = $previousCommentLikeCount + 1;


            DB::table('comments')->where('id',$comment_id)->update(['commentlikecount'=>$newCommentLikeCount]);
            $count=DB::table('comments')->where('id',$comment_id)->get();
            return response()->json([
                'success' => true,
                'data' => $count[0]->commentlikecount
            ], 200);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'already liked',
                
            ], 500);
        

          $count= DB::table('comments')->where('id',$comment_id)->update(['commentlikecount'=>$newCommentLikeCount]);
            return response()->json([
                'success' => false,
                'replylikecount' => $count
            ], 200);

        }

    }
    Public function dislikes(Request $request){

        $comment_id = $request->comment_id;
        $userInfo = auth()->user();

        $check = commentdislike::where('comment_id','=',$comment_id)->where('user_id','=',$userInfo->id)->first();
        if(empty($check))
        {

            $inser_data['comment_id'] = $comment_id;
            $inser_data['user_id'] = $userInfo->id;


            commentdislike::create($inser_data);

            $previousCommentDisLike = DB::table('comments')->where('id',$comment_id)->first();
            $previousCommentDisLike = json_decode(json_encode($previousCommentDisLike),true);
       
             $previousCommentDisLikeCount = $previousCommentDisLike['commentdislikecount'];
       

             $newCommentDisLikeCount = $previousCommentDisLikeCount + 1;

            DB::table('comments')->where('id',$comment_id)->update(['commentdislikecount'=>$newCommentDisLikeCount]);
        }

    }
    
    
}
