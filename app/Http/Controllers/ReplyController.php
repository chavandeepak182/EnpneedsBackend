<?php

namespace App\Http\Controllers;
use App\comment;
use App\post;
use App\reply;
use DB;
use App\postimages;
use App\postvideo;
use App\replylike;
use App\replydislike;
use App\User;
use App\Profileimgs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReplyController extends Controller
{
    public function index()
    {
        //$replies = auth()->user()->replies;
        $replies = reply::get();
        return response()->json([
            'success' => true,
            'data' => $replies
        ]);
    }
 
    public function show($id)
    {
        $reply = auth()->user()->replies()->find($id);
        
        if (!$reply) {
            return response()->json([
                'success' => false,
                'message' => 'Reply with id ' . $id . ' not found'
            ], 400);
        }
 
        return response()->json([
            'success' => true,
            'data' => $reply->toArray()
        ], 200);
    }
 
    public function store(Request $request)
    {
        $this->validate($request, [
            'comment_id' => 'required',
            'post_id' => 'required',
            'reply' => 'required',
            
        ]);
 
        $reply = new reply();
        $reply->comment_id = $request->comment_id;
        $reply->post_id = $request->post_id;
        $reply->reply = $request->reply;
        $reply->replylikecount =0;
        $reply->replydislikecount =0;

        
 
         
        if (auth()->user()->replies()->save($reply)){
            $books = reply::with('profiles','users')->where('id','=',$reply->id)->get();
           
            return response()->json([
                'success' => true,
                'data' => $books
            ]);
        }
        else
            return response()->json([
                'success' => false,
                'message' => 'Reply could not be added'
            ], 500);
    }
 
    public function update(Request $request, $id)
    {
        Log::info('Update Reply: '.$id);
        Log::info('Request: '.$request);
        $reply = auth()->user()->replies()->find($id);
 
        if (!$reply) {
            return response()->json([
                'success' => false,
                'message' => 'Reply with id ' . $id . ' not found'
            ], 400);
        }
 
        $updated = $reply->fill($request->all())->save();
 
        if ($updated)
            return response()->json([
                'success' => true
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Reply could not be updated'
            ], 500);
    }
 
    public function destroy($id)
    {
        $reply = auth()->user()->replies()->find($id);
 
        if (!$reply) {
            return response()->json([
                'success' => false,
                'message' => 'Reply with id ' . $id . ' not found'
            ], 400);
        }
 
        if ($reply->delete()) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Reply could not be deleted'
            ], 500);
        }
    }

    Public function like(Request $request){

        $reply_id = $request->reply_id;

        $userInfo = auth()->user();

        $check = postlike::where('reply_id','=',$reply_id)->where('user_id','=',$userInfo->id)->first();
        echo($check);
        if(empty($check)){

            $inser_data['reply_id'] = $reply_id;
            $inser_data['user_id'] = $userInfo->id;


            postlike::create($inser_data);

            $previousLike = DB::table('replies')->where('id',$reply_id)->first();
            $previousLike = json_decode(json_encode($previousLike),true);
        
            $previousLikeCount = $previousLike['likecount'];
        

            $newLikeCount = $previousLikeCount + 1;

           $like=DB::table('replies')->where('id',$reply_id)->update(['likecount'=>$newLikeCount]);
           return response()->json([
            'success' => true,
            'data' => $like
        ],200);
        }
        else{
            $previousLike = DB::table('replies')->where('id',$reply_id)->get();
          //  $previousLike = json_decode(json_encode($previousLike),true);
        
          $demo= $previousLike['likecount'];

         //   $newLikeCount = $previousLike['likecount'] - 1;

         //  $like1 =DB::table('posts')->where('id',$post_id)->update(['likecount'=>$newLikeCount]);
           return response()->json([
            'success' => true,
            'data' => $demo
        ],200);
        }
    }
    Public function DisLikes(Request $request){

        $reply_id = $request->reply_id;
        $userInfo = auth()->user();

        $check = replydislike::where('reply_id','=',$reply_id)->where('user_id','=',$userInfo->id)->first();
        if(empty($check))
        {

            $inser_data['reply_id'] = $reply_id;
            $inser_data['user_id'] = $userInfo->id;


            replydislike::create($inser_data);

        
            $previousReplyDisLike = DB::table('replies')->where('id',$reply_id)->first();
            $previousReplyDisLike = json_decode(json_encode($previousReplyDisLike),true);
       
            $previousReplyDisLikeCount = $previousReplyDisLike['replydislikecount'];
       

            $newReplyDisLikeCount = $previousReplyDisLikeCount + 1;

            DB::table('replies')->where('id',$reply_id)->update(['replydislikecount'=>$newReplyDisLikeCount]);
        }

    }
    
}
