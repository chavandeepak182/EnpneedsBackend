<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use App\Unit_rigs;
use App\Unit_rigsImg;
use Illuminate\Http\Request;

class Unit_rigsImgController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Unit_rigsImg=  Unit_rigsImg::all();
        return response()->json($Unit_rigsImg);
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
        $file_count = count($request->image);
        if($file_count > 5){
            return response()->json([
                'success' => false,
                'message' => ' upload max 5 images'
            ], 500);
        }
    
        $imageName = "";
       
        $Image = new Unit_rigsImg();
      
        $Image->image= $imageName;
      
     
            if ($request->hasFile('image')) {
        
                $image = $request->image;
              
                $count = 1;
                $response = [];
                foreach($image as $img){
                    $count++;
                    $unit_rigs =$request->input('unit_rigs_id');
                    $imageName = time().$count.'.'.$img->getClientOriginalExtension();
                   $t = Storage::disk('s3')->put($imageName, file_get_contents($img), 'public');
                    $imageName = Storage::disk('s3')->url($imageName);
                    $imageCreate=  Unit_rigsImg::create([
                        'unit_rigs_id' => $unit_rigs,
                        'image' => $imageName
                    ]);
                    array_push ( $response, [
                        'id' =>$imageCreate->id,
                        'image'=> $imageName
                    ]);
                }
                
             }
               return response()->json([
                'success' => true,
              'data' => $response
            ]);


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $Unit_rigsImg= Unit_rigsImg::find($id);
        if(is_null($Unit_rigsImg)){
            return response()->json("record not found",404);
        }
        return response()->json($Unit_rigsImg,200);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $Unit_rigsImg= Unit_rigsImg::where('unit_rigs_id','=', $id);
        $Unit_rigsImg->delete();
        return response()->json( $Unit_rigsImg);
    }
    public function destroyById($id)
    {
        $Unit_rigsImg= Unit_rigsImg::find($id);
        $Unit_rigsImg->delete();
        return response()->json( $Unit_rigsImg);
    }
}
