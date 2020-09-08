<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Whitepaper;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class WhitepaperController extends Controller
{
    public function index()
    {
        $white = Whitepaper::paginate(10);
 
        return response()->json([
            'success' => true,
            'data' => $white
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'description'=>'required',
            'company_name' => 'required',
            'upload_file'=>'required|mimes:pdf,xlx,csv|max:2048'
           
        ]);
        $white = new Whitepaper();
        $white->title = $request->input('title');
        $white->description = $request->input('description');
        $white->company_name = $request->input('company_name');
        
      
     
     if ($request->hasFile('upload_file')) {
        //$this->validate($request, [
           // 'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        $imageName = time().'.'.$request->upload_file->getClientOriginalExtension();
        $image = $request->file('upload_file');
        $t = Storage::disk('s3')->put($imageName, file_get_contents($image), 'public');
        $imageName = Storage::disk('s3')->url($imageName);
        $white->upload_file= $imageName;
     }
     if (auth()->user()->whitepapers()->save($white))
     return response()->json([
         'success' => true,
         'data' => $white->toArray()
     ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Whitepaper could not be added'
            ], 500);
 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       // $companies = auth()->user()->companies()->find($id);

        $white=Whitepaper::find($id);
        if (!$Whitepaper) {
            return response()->json([
                'success' => false,
                'message' => 'Whitepaper with id ' . $id . ' not found'
            ], 400);
        }
 
        return response()->json([
            'success' => true,
            'data' => $white->toArray()
        ], 200);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      
       $white = auth()->user()->whitepapers()->find($id);
       
 
        if (!$white) {
            return response()->json([
                'success' => false,
                'message' => 'Whitepaper with id ' . $id . ' not found'
            ], 400);
        }

        $white->update($request->all());  
       $white->save();
 
        if ($white)
            return response()->json([
                'success' => true,
                'data'=>$white
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Whitepaper could not be updated'
            ], 500);
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $white = auth()->user()->whitepapers()->find($id);
 
        if (!$white) {
            return response()->json([
                'success' => false,
                'message' => 'Whitepaper with id ' . $id . ' not found'
            ], 400);
        }
 
        if ($white->delete()) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Whitepaper could not be deleted'
            ], 500);
        }
    }

}
