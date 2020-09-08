<?php
 
namespace App\Http\Controllers;
 
use App\Cdetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CdetailsController extends Controller
{
    public function index()
    {
        $cdetails = auth()->user()->cdetails;
 
        return response()->json([
            'success' => true,
            'data' => $cdetails
        ]);
    }
 
    public function show($id)
    {
        $cdetails = auth()->user()->cdetails()->find($id);
 
        if (!$cdetails) {
            return response()->json([
                'success' => false,
                'message' => 'Cdetails with id ' . $id . ' not found'
            ], 400);
        }
 
        return response()->json([
            'success' => true,
            'data' => $cdetails->toArray()
        ], 400);
    }
 
    public function store(Request $request)
    {
        $this->validate($request, [
            
            'c_name'=>'required',
            'joined'=>'required',
            'c_location'=>'required',
            'profile_id'=>'required'
        ]);
 
        $cdetails = new Cdetails();
        
        $cdetails->c_name = $request->c_name;
        $cdetails->joined = $request->joined;
        $cdetails->c_location = $request->c_location;
        $cdetails->profile_id= $request->profile_id;
        
 
        if (auth()->user()->cdetails()->save($cdetails))
            return response()->json([
                'success' => true,
                'data' => $cdetails->toArray()
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Cdetails could not be added'
            ], 500);
    }
 
    public function update(Request $request, $id)
    {
        Log::info('Update cdetails: '.$id);
        Log::info('Request: '.$request);
        $cdetails = auth()->user()->cdetails()->find($id);
 
        if (!$cdetails) {
            return response()->json([
                'success' => false,
                'message' => 'Cdetails with id ' . $id . ' not found'
            ], 400);
        }
 
        $updated = $cdetails->fill($request->all())->save();
 
        if ($updated)
            return response()->json([
                'success' => true
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Cdetails could not be updated'
            ], 500);
    }
 
    public function destroy($id)
    {
        $cdetails = auth()->user()->cdetails()->find($id);
 
        if (!$cdetails) {
            return response()->json([
                'success' => false,
                'message' => 'Cdetails with id ' . $id . ' not found'
            ], 400);
        }
 
        if ($cdetails->delete()) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Cdetails could not be deleted'
            ], 500);
        }
    }
}

