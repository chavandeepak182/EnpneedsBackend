<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use App\PostImg;
use App\post;
use Illuminate\Http\Request;

class PostImgController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   
        public function index()
    {
        $posts = PostImg::all()->toArray();
        return $posts;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'uploadfile' => 'required',
           
            'uploadfile.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            
        ]);
        $file_count = count($request->uploadfile);
        if($file_count > 5){
            return response()->json([
                'success' => false,
                'message' => ' upload max 5 images'
            ], 500);
        }
    
        $imageName = "";
        $posts = new post();
       
        $post->uploadfile = $imageName;
         
 
        if (auth()->user()->postimages()->save($posts)){
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
                    PostImg::create($insert_image);
                }
                
            }
                return response()->json([
                'success' => true,
                'data' => $posts->toArray()
            ]);
        }
        else{
            return response()->json([
                'success' => false,
                'message' => 'postimage could not be added'
            ], 500);
        }
    }
    public function destroy($id)
    {
        $posts= PostImg::where('post_id','=', $id);
        $posts->delete();
        return response()->json( $posts);
    }
   
       
}

  
 
