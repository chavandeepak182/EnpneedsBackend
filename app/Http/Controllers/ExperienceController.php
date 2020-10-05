<?php
 
namespace App\Http\Controllers;
 
use App\Experience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
 
class ExperienceController extends Controller
{
    public function index()
    {
        $experience = auth()->user()->experiences;
 
        return response()->json([
            'success' => true,
            'data' => $experience
        ]);
    }
    public function show($id)
    {
        $experience = auth()->user()->experiences()->find($id);
 
        if (!$experience) {
            return response()->json([
                'success' => false,
                'message' => 'experience with id ' . $id . ' not found'
            ], 400);
        }
 
        return response()->json([
            'success' => true,
            'data' => $experience->toArray()
        ], 200);
    }
 
    public function experienceshowbyid($id)
    {
        $experience = Experience::where('user_id','=',$id)->orderBy('id','desc')->get();
 
        if (!$experience) {
            return response()->json([
                'success' => false,
                'message' => 'experience with id ' . $id . ' not found'
            ], 400);
        }
 
        return response()->json([
            'success' => true,
            'data' => $experience->toArray()
        ], 200);
    }
 
 
    public function store(Request $request)
    {
        $this->validate($request, [
            
            'position' =>'required',
            'company' =>'required',
            'location' =>'required',
            'from' => 'required',
            'to' => 'required',
          
         
        ]);
 
        $experience = new Experience();
        $experience->company = $request->company;
        $experience->location = $request->location;
        $experience->position= $request->position;
        $experience->from= $request->from;
        $experience->to = $request->to;

        if (auth()->user()->experiences()->save($experience))
            return response()->json([
                'success' => true,
                'data' => $experience->toArray()
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Experience could not be added'
            ], 500);
    }
 
    public function update(Request $request, $id)
    {
        $experience = auth()->user()->experiences()->find($id);
         
  
         if (!$experience) {
             return response()->json([
                 'success' => false,
                 'message' => 'request with id ' . $id . ' not found'
             ], 400);
         }
 
         $updated = $experience->fill($request->all())->save();
  
         if ($experience)
             return response()->json([
                 'success' => true,
                 'data'=>$experience
             ]);
         else
             return response()->json([
                 'success' => false,
                 'message' => 'experience could not be updated'
             ], 500);
    }
 
    public function destroy($id)
    {
        $experience = auth()->user()->experiences()->find($id);
 
        if (!$experience) {
            return response()->json([
                'success' => false,
                'message' => 'Experience with id ' . $id . ' not found'
            ], 400);
        }
 
        if ($experience->delete()) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Experience could not be deleted'
            ], 500);
        }
    }
}

