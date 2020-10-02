<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Profileimgs;
use App\User;

class profileimgController extends Controller
{

        public function index()
    {
        $profile =Profileimgs::all();
        return response()->json([
            'success' => true,
            'data' => $profile
        ]);
    }
    public function profileimage()
    {

        $profile = auth()->user()->profileimgs;
        return response()->json([
            'success' => true,
            'data' => $profile
        ]);
    }
    public function profileimageByUser($id)
    {

        $profile =Profileimgs::where('user_id','=',$id)->get();
        return response()->json([
            'success' => true,
            'data' => $profile
        ]);
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'profileimg' => 'required',
           
            'profileimg.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            
        ]);
        
    
        $imageName = "";
       
        $Image = new Profileimgs();
      
        $Image->profileimg= $imageName;
      
     
        if ($request->hasFile('profileimg')) {
           
            $imageName = time().'.'.$request->profileimg->getClientOriginalExtension();
            $image = $request->file('profileimg');
            $t = Storage::disk('s3')->put($imageName, file_get_contents($image), 'public');
            $imageName = Storage::disk('s3')->url($imageName);
            $upload_file=Profileimgs::create(['user_id'=>auth()->user()->id,
                  'profileimg' => $imageName]);
         }
         

            return response()->json([
                'success' => true,
                'data' =>$upload_file
            ]);
       }
       public function destroy($id)
       {
           $profile=Profileimgs::where('id','=', $id);
           $profile->delete();
           return response()->json( $profile);
       }
}
