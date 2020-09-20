<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Coverphotos;
use App\User;

class CoverphotoController extends Controller
{
    public function index()
    {
        $coverphotos = auth()->user()->coverphotos;
        
     
 
        return response()->json([
            'success' => true,
            'data' => $coverphotos
        ]);
    }
  
    public function show($id)
    {
        $coverphotos = auth()->user()->coverphotos()->find($id);
 
        if (!$coverphotos) {
            return response()->json([
                'success' => false,
                'message' => 'Coverphoto with id ' . $id . ' not found'
            ], 400);
        }
 
        return response()->json([
            'success' => true,
            'data' => $coverphotos->toArray()]
            , 200);
    }
    public function covershowbyid($id)
    {
        $coverphotos = Coverphotos::where('user_id','=',$id)->get();
 
        if (!$coverphotos) {
            return response()->json([
                'success' => false,
                'message' => 'coverphotos with id ' . $id . ' not found'
            ], 400);
        }
 
        return response()->json([
            'success' => true,
            'data' => $coverphotos->toArray()
        ], 200);
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            
            
            'coverphoto' => 'required',
            'coverphoto' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
          
          
           
            
        ]);
        $coverphotos = new Coverphotos();
        if ($request->hasFile('coverphoto')) {
           $imageName = time().'.'.$request->coverphoto->getClientOriginalExtension();
            $image = $request->file('coverphoto');
            $t = Storage::disk('s3')->put($imageName, file_get_contents($image), 'public');
            $imageName = Storage::disk('s3')->url($imageName);
            $coverphotos->coverphoto=$imageName;
         }
        
       
        
        
      
        
 
        if (auth()->user()->coverphotos()->save($coverphotos))
            return response()->json([
                'success' => true,
                'data' => $coverphotos->toArray()
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'coverphoto could not be added'
            ], 500);

            
            
           
    }



    public function UpdateByID(Request $request,$id)
    {
      
        $coverphotos=Coverphotos::find($id);
        $coverphotos->coverphoto=$request->input('coverphoto');
        
       
        $coverphotos->save();
        return response()->json($coverphotos);
    
    }
    public function destroy($id)
    {
        $coverphotos= Coverphoto::find($id);
        $coverphotos->delete();
        return response()->json( $coverphotos);
    }

  
}
