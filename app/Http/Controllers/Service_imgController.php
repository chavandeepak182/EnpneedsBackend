<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use App\Service_image;
use App\Service;
use Illuminate\Http\Request;

class Service_imgController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $service_image=  service_image::all();
        return response()->json($service_image);
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
       
        $Image = new Service_image();
      
        $Image->image= $imageName;
      
     
            if ($request->hasFile('image')) {
        
                $image = $request->image;
              
                $count = 1;
                $response = [];

                foreach($image as $img){
                    $count++;
                    $service =$request->input('service_id');
                    $imageName = time().$count.'.'.$img->getClientOriginalExtension();
                   $t = Storage::disk('s3')->put($imageName, file_get_contents($img), 'public');
                    $imageName = Storage::disk('s3')->url($imageName);
                    $imageCreate= Service_image::create([
                        'service_id' => $service,
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
        $service_image= service_image::find($id);
        if(is_null($service_image)){
            return response()->json("record not found",404);
        }
        return response()->json($service_image,200);
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
        $service_image= Service_image::where('service_id','=', $id);
        $service_image->delete();
        return response()->json( $service_image);
    }
    public function destroyById($id)
    {
        $service_image= Service_image::find($id);
        $service_image->delete();
        return response()->json( $service_image);
    }
}
