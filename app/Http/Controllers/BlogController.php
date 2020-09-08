<?php
 
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;

use App\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
 
class BlogController extends Controller
{
    public function index()
    {
        $blog = auth()->user()->blogs;
 
        return response()->json([
            'success' => true,
            'data' => $blog
        ]);
    }
  
    public function show($id)
    {
        $blog = auth()->user()->blogs()->find($id);
 
        if (!$blog) {
            return response()->json([
                'success' => false,
                'message' => 'blog with id ' . $id . ' not found'
            ], 400);
        }
 
        return response()->json([
            'success' => true,
            'data' => $blog->toArray()
        ], 200);
    }
 
    public function store(Request $request)
    {
        $this->validate($request, [
            
            'title' => 'required',
            'image' =>'required',
            'description' =>'required',
            
            
            
        ]);
        if ($request->hasFile('image')) {
            //$this->validate($request, [
               // 'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            $imageName = time().'.'.$request->image->getClientOriginalExtension();
            $image = $request->file('image');
            $t = Storage::disk('s3')->put($imageName, file_get_contents($image), 'public');
            $imageName = Storage::disk('s3')->url($imageName);
         }
 
        $blog = new Blog();
        $blog->title = $request->title;
        $blog->image = $imageName;
        $blog->description = $request->description;
       
        
        
 
        if (auth()->user()->blogs()->save($blog))
            return response()->json([
                'success' => true,
                'data' => $blog->toArray()
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'blog could not be added'
            ], 500);
    }
 
    public function update(Request $request, $id)
    {
        Log::info('Update blog: '.$id);
        Log::info('Request: '.$request);
        $blog = auth()->user()->blogs()->find($id);
 
        if (!$blog) {
            return response()->json([
                'success' => false,
                'message' => 'blog with id ' . $id . ' not found'
            ], 400);
        }
 
        $updated = $blog->fill($request->all())->save();
 
        if ($updated)
            return response()->json([
                'success' => true
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'blog could not be updated'
            ], 500);
    }
 
    public function destroy($id)
    {
        $blog = auth()->user()->blogs()->find($id);
 
        if (!$blog) {
            return response()->json([
                'success' => false,
                'message' => 'blog with id ' . $id . ' not found'
            ], 400);
        }
 
        if ($blog->delete()) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'blog could not be deleted'
            ], 500);
        }
    }
}
