<?php

namespace App\Http\Controllers;

use App\post;
use App\comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\postimages;
use App\postvideo;
use App\postlike;
use App\postdislike;
use App\Profile;
use App\reply;
use App\User;
use DB;
use App\Profileimgs;

class PostController extends Controller
{
    public function index()
    {
        // $posts = auth()->user()->posts;
 
       // $posts = post::get();
       $posts = post::orderBy('id','desc')->paginate(3);
       foreach($posts as $post){
       
        
        $post['commentcount']=comment::where('post_id','=',$post->id)->count(); 
       }
        foreach($posts as $post){
            $post['Images'] = postimages::where('post_id','=',$post->id)->get();
          
        }
      
        foreach($posts as $post){
            $post['videos'] = postvideo::where('post_id','=',$post->id)->get();
           
        }
        $post['commentcount'] = comment::where('post_id','=',$post->id)->get();
        return response()->json([
            'success' => true,
            'data' => $posts
        ]);
    }
   

    public function postshowbyuserid()
  {
      $currentuser = Auth::user();
     
      $post = post::where('user_id', '=', $currentuser->id)
                 ->get();

      return response()
        ->json([
          'post' => $post
        ]);
  }
    
    public function show($id)
    {
        $post = auth()->user()->posts()->find($id);
 
        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post with id ' . $id . ' not found'
            ], 400);
        }
        
        $post['Images'] = postimages::where('post_id','=',$post->id)->get();
        $post['videos'] = postvideo::where('post_id','=',$post->id)->get();
        return response()->json([
            'success' => true,
            'data' => $post->toArray()
        ], 200);
    }
    
    
    public function store(Request $request)
    {    

        $file_type = 0;
        if ($request->hasFile('uploadfile')) {
            $file_type = 1;
            $this->validate($request, [
                'uploadfile.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            
            $file_count = count($request->uploadfile);
            if($file_count > 5){
                return response()->json([
                    'success' => false,
                    'message' => ' upload max 5 images'
                ], 500);
            }
        }
        if ($request->hasFile('uploadvideo')) {
            if($file_type == "1"){
                $file_type = 3;
            }else{
                $file_type = 2;
            }
            
            $this->validate($request, [
                'uploadvideo.*' => 'mimes:mpeg,ogg,mp4,webm,3gp,mov,flv,avi,wmv,ts|max:20048',
            ]);

            $video_count = count($request->uploadvideo);
            if($video_count > 5){
                return response()->json([
                    'success' => false,
                    'message' => ' upload max 5 videos'
                ], 500);
            }
        }

        
        
       
 
        $imageName = "";
        $videoName = "";
        $description = "";

        if ($request->has('description')) {
            $description = $request->description;
        }


        $posts = new Post();
        $posts->description = $description;
        // $post->uploadfile = $imageName;
        // $post->uploadvideo = $videoName;
     
        $posts->file_type = $file_type;
        $posts->likecount = 0;
        $posts->dislikecount = 0;
 
        if (auth()->user()->posts()->save($posts)){

            $res = $posts->toArray();

            if ($request->hasFile('uploadfile')) {
        
                $image = $request->uploadfile;

                $count = 1;
                foreach($image as $img){
                    $count++;
                    $imageName = time().$count.'.'.$img->getClientOriginalExtension();
    
                    $t = Storage::disk('s3')->put($imageName, file_get_contents($img), 'public');
                    $imageName = Storage::disk('s3')->url($imageName);

                    $insert_image['post_id'] = $res['id'];
                    $insert_image['uploadfile'] = $imageName;
                    postimages::create($insert_image);
                }

                

            }
            if ($request->hasFile('uploadvideo')) {
        
                $video = $request->uploadvideo;

                
                $countt = 1;
                foreach($video as $vid){
                    $countt++;
                    $videoName = time().$countt.'.'.$vid->getClientOriginalExtension();
    
                    $t = Storage::disk('s3')->put($videoName, file_get_contents($vid), 'public');
                    $videoName = Storage::disk('s3')->url($videoName);

                    $insert_video['post_id'] = $res['id'];
                    $insert_video['uploadvideo'] = $videoName;
                    postvideo::create($insert_video);
                }

            }
          
            $books = post::with('comments.users','comments.replies.users','comments.profileimgs','comments.replies.profileimgs','users','profileimgs','postimages','postvideos')
            ->withcount('comments')->where('id','=',$posts->id)->get();
            return response()->json([
                'success' => true,
                'data' => $books
                
            ]);
            }
        else{
            return response()->json([
                'success' => false,
                'message' => 'Post could not be added'
            ], 500);
        }
    }
 
    public function update(Request $request, $id)
    {
        Log::info('Update post: '.$id);
        Log::info('Request: '.$request);
        $post = auth()->user()->posts()->find($id);
 
        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post with id ' . $id . ' not found'
            ], 400);
        }
 
        $updated = $post->fill($request->all())->save();
 
        if ($updated)
            return response()->json([
                'success' => true
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Post could not be updated'
            ], 500);
    }
    public function postById($id)
{


$books =post::with('comments.replies','postimages','postvideos')->where('user_id','=',$id)->withcount('comments')->orderBy('id','desc')
->paginate(10);


foreach($books as $post){
$post['profileimg']=Profileimgs::where('user_id','=',$post->user_id)->select('profileimg')->get();
$post['user']=user::where('id','=',$post->user_id)->select('first_name','last_name')->get();
foreach($post['comments'] as $post){
$post['user']=user::where('id','=',$post->user_id)->select('first_name','last_name')->get();
$post['profileimg']=Profileimgs::where('user_id','=',$post->user_id)->select('profileimg')->get();
foreach($post['replies'] as $post){
$post['profileimg']=Profileimgs::where('user_id','=',$post->user_id)->select('profileimg')->get();
$post['user']=user::where('id','=',$post->user_id)->select('first_name','last_name')->get();
}
}


}


return response()->json([
'data' =>$books
], 200);



}

    
 
    public function destroy($id)
    {
        $post = auth()->user()->posts()->find($id);
        postimages::where('post_id',$id)->delete();
        postvideo::where('post_id',$id)->delete();
        comment::where('post_id',$id)->delete();
        reply::where('post_id',$id)->delete();
        

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post with id ' . $id . ' not found'
            ], 400);
        }
 
        if ($post->delete()) {
            return response()->json([
                'success' => true
            ],200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Post could not be deleted'
            ], 500);
        }
    }
   

    Public function like(Request $request){

        $post_id = $request->post_id;

        $userInfo = auth()->user();

        $check = postlike::where('post_id','=',$post_id)->where('user_id','=',$userInfo->id)->first();
        echo($check);
        if(empty($check)){

            $inser_data['post_id'] = $post_id;
            $inser_data['user_id'] = $userInfo->id;


            postlike::create($inser_data);

            $previousLike = DB::table('posts')->where('id',$post_id)->first();
            $previousLike = json_decode(json_encode($previousLike),true);
        
            $previousLikeCount = $previousLike['likecount'];
        

            $newLikeCount = $previousLikeCount + 1;

           $like=DB::table('posts')->where('id',$post_id)->update(['likecount'=>$newLikeCount]);
           return response()->json([
            'success' => true,
            'data' => $like
        ],200);
        }
        else{
            $previousLike = DB::table('posts')->where('id',$post_id)->get();
         
        echo($previousLike['likecount']);
         // $demo= $previousLike['likecount'];

        // $newLikeCount = $previousLike['likecount'] - 1;

        // $like1 =DB::table('posts')->where('id',$post_id)->update(['likecount'=>$newLikeCount]);
           return response()->json([
            'success' => true,
            'data' => $demo
        ],200);
        }


    }
    
    Public function dislike(Request $request)
    {

        $post_id = $request->post_id;
        $userInfo = auth()->user();

        $check = postdislike::where('post_id','=',$post_id)->where('user_id','=',$userInfo->id)->first();
        if(empty($check))
        {

            $inser_data['post_id'] = $post_id;
            $inser_data['user_id'] = $userInfo->id;


            postdislike::create($inser_data);

            $previousDisLike = DB::table('posts')->where('id',$post_id)->first();
            $previousDisLike = json_decode(json_encode($previousDisLike),true);
       
            $previousDisLikeCount = $previousDisLike['dislikecount'];
       

            $newDisLikeCount = $previousDisLikeCount + 1;

            DB::table('posts')->where('id',$post_id)->update(['dislikecount'=>$newDisLikeCount]);
        }

    }
    public function postByIdauth($id)
{

    $post = auth()->user()->posts()->find($id);
$books =post::with('comments.replies','postimages','postvideos')->where('user_id','=',$id)->withcount('comments')->orderBy('id','desc')
->paginate(10);


foreach($books as $post){
$post['profileimg']=Profileimgs::where('user_id','=',$post->user_id)->select('profileimg')->get();
$post['user']=user::where('id','=',$post->user_id)->select('first_name','last_name')->get();
foreach($post['comments'] as $post){
$post['user']=user::where('id','=',$post->user_id)->select('first_name','last_name')->get();
$post['profileimg']=Profileimgs::where('user_id','=',$post->user_id)->select('profileimg')->get();
foreach($post['replies'] as $post){
$post['profileimg']=Profileimgs::where('user_id','=',$post->user_id)->select('profileimg')->get();
$post['user']=user::where('id','=',$post->user_id)->select('first_name','last_name')->get();
}
}


}


return response()->json([
'data' =>$books
], 200);



}

    
    
}