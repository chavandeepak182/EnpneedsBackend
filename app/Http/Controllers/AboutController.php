<?php
 
namespace App\Http\Controllers;
 
use App\About;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
 
class AboutController extends Controller
{
    public function index()
    {
        $about = auth()->user()->abouts;
 
        return response()->json([
            'success' => true,
            'data' => $about
        ]);
    }
    public function show($id)
    {
        $about = auth()->user()->abouts()->find($id);
 
        if (!$about) {
            return response()->json([
                'success' => false,
                'message' => 'about with id ' . $id . ' not found'
            ], 400);
        }
 
        return response()->json([
            'success' => true,
            'data' => $about->toArray()
        ], 200);
    }
    public function aboutshowbyid($id)
    {
        $about = About::where('user_id','=',$id)->get();
 
        if (!$about) {
            return response()->json([
                'success' => false,
                'message' => 'about with id ' . $id . ' not found'
            ], 400);
        }
 
        return response()->json([
            'success' => true,
            'data' => $about->toArray()
        ], 400);
    }
 
 
    public function store(Request $request)
    {
        $this->validate($request, [
            
            
            'headline' =>'required',
            
            'industry'=>'required',
            
            'description'=>'required',
            
            
         
        ]);
 
        $about = new About();
       
        $about->headline = $request->headline;
       
        $about->industry= $request->industry;
        
        $about->description= $request->description;
        
        
        
 
        if (auth()->user()->abouts()->save($about))
            return response()->json([
                'success' => true,
                'data' => $about->toArray(),

            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'About could not be added'
                
            ], 500);
    }
 
    public function update(Request $request, $id)
    {
        Log::info('Update about: '.$id);
        Log::info('Request: '.$request);
        $about = auth()->user()->abouts()->find($id);
 
        if (!$about) {
            return response()->json([
                'success' => false,
                'message' => 'About with id ' . $id . ' not found'
            ], 400);
        }
 
        $updated = $about->fill($request->all())->save();
 
        if ($updated)
            return response()->json([
                'success' => true
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'About could not be updated'
            ], 500);
    }
 
    public function destroy($id)
    {
        $about = auth()->user()->abouts()->find($id);
 
        if (!$about) {
            return response()->json([
                'success' => false,
                'message' => 'About with id ' . $id . ' not found'
            ], 400);
        }
 
        if ($about->delete()) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'About could not be deleted'
            ], 500);
        }
    }
}
