<?php
 
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;

use App\Profile;
use App\Profileimgs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
 
class ProfileController extends Controller
{
    public function index()
    {
        $profile = Profile::all();
        foreach($profile as $com){
        $com['profileimg'] = Profileimgs::where('user_id','=', $com['id'])->get();
       
        }
        return response()->json([
            'success' => true,
            'data' => $profile
        ]);
    }
    public function show($id)
    {
        $profile = auth()->user()->profiles()->find($id);
 
        if (!$profile) {
            return response()->json([
                'success' => false,
                'message' => 'profile with id ' . $id . ' not found'
            ], 400);
        }
 
        return response()->json([
            'success' => true,
            'data' => $profile->toArray()
        ], 200);
    }
  
    public function profileshowbyid($id)
    {
        $profile = Profile::where('user_id','=',$id)->get();
 
        if (!$profile) {
            return response()->json([
                'success' => false,
                'message' => 'Product with id ' . $id . ' not found'
            ], 400);
        }
 
        return response()->json([
            'success' => true,
            'data' => $profile->toArray()
        ], 200);
    }
 
    public function store(Request $request)
    {
        $this->validate($request, [
            
            
            'mobile_no' => 'required',
            'address' =>'required',
            'postal_code' =>'required',
            'designation'=>'required',
            
        ]);
        
        
        $profile = new Profile();
        $profile->mobile_no = $request->mobile_no;
        $profile->address = $request->address;
        $profile->postal_code = $request->postal_code;
        $profile->designation =$request->designation;
        
            
        if (auth()->user()->profile()->save($profile))
        {
           
            return response()->json([
                'success' => true,
                'data' => $profile->toArray()
            ]);}
        else
            return response()->json([
                'success' => false,
                'message' => 'Profile could not be added'
            ], 500);
    }
 
    public function update(Request $request, $id)
    {
        Log::info('Update profile: '.$id);
        Log::info('Request: '.$request);
        $profile = auth()->user()->profiles()->find($id);
 
        if (!$profile) {
            return response()->json([
                'success' => false,
                'message' => 'Profile with id ' . $id . ' not found'
            ], 400);
        }
 
        $updated = $profile->fill($request->all())->save();
 
        if ($updated)
            return response()->json([
                'success' => true,
                'data'=>$profile
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Profile could not be updated'
            ], 500);
    }
    public function profileshowbyidauth()
    {   
        $id = auth()->user();
        
        $profile = Profile::where('user_id','=',$id->id)->get();
 
        if (!$profile) {
            return response()->json([
                'success' => false,
                'message' => 'Product with id ' . $id . ' not found'
            ], 400);
        }
 
        return response()->json([
            'success' => true,
            'data' => $profile->toArray()
        ], 200);
    }
 
    public function destroy($id)
    {
        $profile = auth()->user()->profiles()->find($id);
 
        if (!$profile) {
            return response()->json([
                'success' => false,
                'message' => 'Profile with id ' . $id . ' not found'
            ], 400);
        }
 
        if ($profile->delete()) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Profile could not be deleted'
            ], 500);
        }
    }
}