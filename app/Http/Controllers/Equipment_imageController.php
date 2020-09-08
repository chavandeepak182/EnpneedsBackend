<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use App\Equipment_image;
use App\Equipment;
use Illuminate\Http\Request;

class Equipment_imageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Equipment_image=  Equipment_image::all();
        return response()->json($Equipment_image);
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
       
        $Image = new Equipment_image();
      
        $Image->image= $imageName;
      
     
            if ($request->hasFile('image')) {
        
                $image = $request->image;
              
                $count = 1;
                $response = [];
                foreach($image as $img){
                    $count++;
                    $equipment =$request->input('equipment_id');
                    $imageName = time().$count.'.'.$img->getClientOriginalExtension();
                   $t = Storage::disk('s3')->put($imageName, file_get_contents($img), 'public');
                    $imageName = Storage::disk('s3')->url($imageName);
                   $imageCreate= Equipment_image::create([
                        'equipment_id' => $equipment,
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
              'data' =>  $response
              

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
        $Equipment_image=  Equipment_image::find($id);
        if(is_null($Equipment_image)){
            return response()->json("record not found",404);
        }
        return response()->json( $Equipment_image,200);
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
        $Equipment_image= Equipment_image::where('equipment_id','=', $id);
        $Equipment_image->delete();
        return response()->json( $Equipment_image);
    }
    public function destroyById($id)
    {
        $Equipment_image= Equipment_image::find($id);
        $Equipment_image->delete();
        return response()->json( $Equipment_image);
    }
}
