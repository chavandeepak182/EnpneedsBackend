<?php
 
namespace App\Http\Controllers;
 
use App\Ads;
use App\Adsimg;
use App\Category;
use App\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
 
class AdsController extends Controller
{
    public function index()
    {
        //$ads =Ads::all();
        $ads =Ads::paginate(10);
        $tmp_data = [];
        foreach($ads as $ad){
            $ad['Images'] = Adsimg::where('ads_id','=',$ad->id)->get();
            $tmp_data[] = $ad;
        }
     
        return response()->json([
            'success' => true,
            'data' => $ads
        ]);
    }
 
    public function show($id)
    {
        $ads = auth()->user()->ads()->find($id);

 
        if (!$ads) {
            return response()->json([
                'success' => false,
                'message' => 'ads with id ' . $id . ' not found'
            ], 400);
        }
        $ads['Images'] = Adsimg::where('ads_id','=',$ads->id)->get();
        return response()->json([
            'success' => true,
            'data' => $ads->toArray()
        ], 400);
    }
    public function adsshowbyuserid()
    {
        $currentuser = Auth::user();
       
        $ads =Ads::where('user_id', '=', $currentuser->id)
                   ->get();
  
        return response()
          ->json([
            'post' => $ads
          ]);
    }
 
    public function store(Request $request)
    {
        $this->validate($request, [
            'category_id'=>'required',
            'subcategory_id'=>'required',
            'description' => 'required',
            'title' => 'required',
            'email' => 'required',
            'mobileno' => 'required',
            'address' => 'required',
            'company_name' => 'required',
            'ads_img' => 'required',
            'ads_img.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            
            
        ]);
        $file_count = count($request->ads_img);
        if($file_count > 5){
            return response()->json([
                'success' => false,
                'message' => ' upload max 5 images'
            ], 500);
        }
    
        $imageName = "";
       
        $ads = new ads();
        $ads->category_id=$request->category_id;
        $ads->subcategory_id=$request->subcategory_id;
        $ads->description = $request->description;
        $ads->title = $request->title;
        $ads->email = $request->email;
        $ads->mobileno = $request->mobileno;
        $ads->address = $request->address;
        $ads->company_name = $request->company_name;
        $ads->ads_img = $imageName;
         
 
        if (auth()->user()->ads()->save($ads)){
            $res = $ads->toArray();
            if ($request->hasFile('ads_img')) {
        
                $image = $request->ads_img;
                $count = 1;
                foreach($image as $img){
                    $count++;
                    $imageName = time().$count.'.'.$img->getClientOriginalExtension();
    
                    $t = Storage::disk('s3')->put($imageName, file_get_contents($img), 'public');
                    $imageName = Storage::disk('s3')->url($imageName);
                    $insert_image['ads_id'] = $res['id'];
                  
                    $insert_image['ads_img'] = $imageName;
                    Adsimg::create($insert_image);
                }
                
            }
                return response()->json([
                'success' => true,
                'data' => $ads->toArray()
            ]);
        }
        else{
            return response()->json([
                'success' => false,
                'message' => 'ads could not be added'
            ], 500);
        }
    }

       
 
       
        
      
    public function update(Request $request, $id)
    {
        Log::info('Update ads: '.$id);
        Log::info('Request: '.$request);
        $ads = auth()->user()->ads()->find($id);
 
        if (!$ads) {
            return response()->json([
                'success' => false,
                'message' => 'ads with id ' . $id . ' not found'
            ], 400);
        }
 
        $updated = $ads->fill($request->all())->save();
 
        if ($updated)
            return response()->json([
                'success' => true
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'ads could not be updated'
            ], 500);
    }
 
    public function destroy($id)
    {
        $ads = auth()->user()->ads()->find($id);
 
        if (!$ads) {
            return response()->json([
                'success' => false,
                'message' => 'ads with id ' . $id . ' not found'
            ], 400);
        }
 
        if ($ads->delete()) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'ads could not be deleted'
            ], 500);
        }
    }
}