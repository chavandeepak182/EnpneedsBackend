<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use App\Supplier_img;
use App\Supplier;
use Illuminate\Http\Request;

class Supplier_imgController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Supplier_img=  Supplier_img::all();
        return response()->json($Supplier_img);
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
       
        $Image = new Supplier_img();
      
        $Image->image= $imageName;
      
     
            if ($request->hasFile('image')) {
        
                $image = $request->image;
              
                $count = 1;
                $response = [];
                foreach($image as $img){
                    $count++;
                    $Supplier =$request->input('supplier_id');
                    $imageName = time().$count.'.'.$img->getClientOriginalExtension();
                   $t = Storage::disk('s3')->put($imageName, file_get_contents($img), 'public');
                    $imageName = Storage::disk('s3')->url($imageName);
                    $imageCreate= Supplier_img::create([
                        'supplier_id' => $Supplier,
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
        $Supplier_img= Supplier_img::find($id);
        if(is_null($Supplier_img)){
            return response()->json("record not found",404);
        }
        return response()->json($Supplier_img,200);
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
        $Supplier_img= Supplier_img::where('supplier_id','=', $id);
        $Supplier_img->delete();
        return response()->json( $Supplier_img);
    }
    public function destroyById($id)
    {
        $Supplier_img= Supplier_img::find($id);
        $Supplier_img->delete();
        return response()->json( $Supplier_img);
    }
}
