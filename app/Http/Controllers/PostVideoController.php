<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use App\postvideo;
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
        $posts = Postvideo::all()->toArray();
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
            'uploadvideo' => 'required',
           
            'uploadvideo.*' => 'mimes:mpeg,ogg,mp4,webm,3gp,mov,flv,avi,wmv,ts|max:20048'
            
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
         
 
        if (auth()->user()->postvideo()->save($posts)){
            $res = $posts->toArray();
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
                return response()->json([
                'success' => true,
                'data' => $posts->toArray()
            ]);
        }
        else{
            return response()->json([
                'success' => false,
                'message' => 'postvideo could not be added'
            ], 500);
        }
    }
    public function destroy($id)
    {
        $posts= postvideo::where('post_id','=', $id);
        $posts->delete();
        return response()->json( $posts);
    }
   
       
}

  
 
