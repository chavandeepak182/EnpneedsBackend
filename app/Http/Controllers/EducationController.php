<?php
 
namespace App\Http\Controllers;
 
use App\Education;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
 
class EducationController extends Controller
{
    public function index()
    {
        $educations = auth()->user()->education;
 
        return response()->json([
            'success' => true,
            'data' => $educations
        ]);
    }
    public function show($id)
    {
        $educations = auth()->user()->education()->find($id);
 
        if (!$educations) {
            return response()->json([
                'success' => false,
                'message' => 'education with id ' . $id . ' not found'
            ], 400);
        }
 
        return response()->json([
            'success' => true,
            'data' => $educations->toArray()
        ], 200);
    }
 
    public function edushowbyid($id)
    {
        $educations = Education::where('user_id','=',$id)->get();
 
        if (!$educations) {
            return response()->json([
                'success' => false,
                'message' => 'education with id ' . $id . ' not found'
            ], 400);
        }
 
        return response()->json([
            'success' => true,
            'data' => $educations->toArray()
        ], 400);
    }
 
    public function store(Request $request)
    {
        $this->validate($request, [
            
            'school' => 'required',
            'degree' =>'required',
            'field_of_study' =>'required',
            'start_year' => 'required',
            'end_year' => 'required',
            'activities_and_societies' => 'required',
            
        ]);
 
        $educations = new Education();
        $educations->school= $request->school;
        $educations->degree = $request->degree;
        $educations->field_of_study = $request->field_of_study;
        $educations->start_year = $request->start_year;
        $educations->end_year = $request->end_year;
        
        $educations->activities_and_societies= $request->activities_and_societies;
       
        if (auth()->user()->education()->save($educations))
            return response()->json([
                'success' => true,
                'data' => $educations->toArray()
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'education could not be added'
            ], 500);
    }
 
    public function update(Request $request, $id)
    {
        Log::info('Update education: '.$id);
        Log::info('Request: '.$request);
        $educations = auth()->user()->education()->find($id);
 
        if (!$educations) {
            return response()->json([
                'success' => false,
                'message' => 'education with id ' . $id . ' not found'
            ], 400);
        }
 
        $updated = $educations->fill($request->all())->save();
 
        if ($updated)
            return response()->json([
                'success' => true,
                'data'=>$educations
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'education could not be updated'
            ], 500);
    }
 
    public function destroy($id)
    {
        $educations = auth()->user()->education()->find($id);
 
        if (!$educations) {
            return response()->json([
                'success' => false,
                'message' => 'education with id ' . $id . ' not found'
            ], 400);
        }
 
        if ($educations->delete()) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'education could not be deleted'
            ], 500);
        }
    }
}