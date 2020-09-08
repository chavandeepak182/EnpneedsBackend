<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Supplier;
use App\Supplier_img;
use App\Category;
use App\Subcategory;
use App\user;
use App\supplier_brochure;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       // $Supplier=Supplier::all();
       $Supplier =Supplier::paginate(12);
    
        foreach($Supplier as $ser){
            $ser['Images'] = Supplier_img::where('supplier_id','=',$ser->id)->get();
            $ser['upload_file'] = supplier_brochure::where('supplier_id','=', $ser['id'])->get();
        }
        return response()->json([
            'success' => true,
            'data' => $Supplier
        ]);
    }


    public function Supplierdesc()
    {
        $Supplier = Supplier::orderBy('name', 'desc')->get();
        foreach($Supplier as $eq){
            $eq['Images'] =Supplier_img::where('supplier_id','=',$eq->id)->get();
            $tmp_data[] = $eq;
        }
       return response()->json([
           'success' => true,
           'data' => $Supplier
       ]);
   }
   public function Supplierasc()
   {
       $Supplier = Supplier::orderBy('name', 'asc')->get();
       foreach($Supplier as $eq){
        $eq['Images'] =Supplier_img::where('supplier_id','=',$eq->id)->get();
        $tmp_data[] = $eq;
    }
      return response()->json([
          'success' => true,
          'data' => $Supplier
      ]);
  }
  public function companyasc()
  {
      $Supplier = Supplier::orderBy('company', 'asc')->get();
      foreach($Supplier as $eq){
       $eq['Images'] =Supplier_img::where('supplier_id','=',$eq->id)->get();
       $tmp_data[] = $eq;
   }
     return response()->json([
         'success' => true,
         'data' => $Supplier
     ]);
 }
 public function companydesc()
 {
     $Supplier =Supplier::orderBy('company', 'desc')->get();
     foreach($Supplier as $eq){
      $eq['Images'] = Supplier_img::where('supplier_id','=',$eq->id)->get();
      $tmp_data[] = $eq;
  }
    return response()->json([
        'success' => true,
        'data' => $Supplier
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
            'name' => 'required',
            'contact_person' => 'required',
            'mobile'=>'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|numeric',
            'description' => 'required',
            'company'=>'required',
            'address' => 'required',
            'latitude'=>'required|string',
            'longitude' => 'required',
            'email'=>'required',
            'country_code' => 'required',
            'category_id'=>'required',
            'subcategory_id'=>'required',
            'image' => 'required',
            'image.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'admin'=>'required',
            'upload_file'=>'required|mimes:pdf,xlx,csv|max:2048'
           
        ]);
        $imageName = "";
         $Supplier = new Supplier();
         $Supplier->name = $request->input('name');
         $Supplier->contact_person = $request->input('contact_person');
         $Supplier->mobile = $request->input('mobile');
         $Supplier->description = $request->input('description');
         $Supplier->company = $request->input('company');
         $Supplier->country_code= $request->input('country_code');
         $Supplier->email = $request->input('email');
         $Supplier->alt_email= $request->input('alt_email');
         $Supplier->address= $request->input('address');
         $Supplier->latitude= $request->input('latitude');
         $Supplier->longitude= $request->input('longitude');
         $Supplier->admin= $request->input('admin');
         $Supplier->category_id=$request->category_id;
        $Supplier->subcategory_id=$request->subcategory_id;
         $Supplier->image= $imageName;
         $Supplier->upload_file= $imageName;
        
       
         $file_count = count($request->image);
         if($file_count > 5){
             return response()->json([
                 'success' => false,
                 'message' => ' upload max 5 images'
             ], 500);
            }
     
    
         if (auth()->user()->Supplier()->save($Supplier)){
             $res = $Supplier->toArray();
           
                if ($request->hasFile('image')) {
                  $image = $request->image;
                  $count = 1;
                      foreach($image as $img){
                         $count++;
                         $imageName = time().$count.'.'.$img->getClientOriginalExtension();
                         $t = Storage::disk('s3')->put($imageName, file_get_contents( $img), 'public');
                         $imageName= Storage::disk('s3')->url($imageName);
                         Supplier_img::create(['supplier_id' => $res['id'],
                                                 'image' => $imageName]);
     
                        }
                      
                 }
                 if ($request->hasFile('upload_file')) {
                    //$this->validate($request, [
                       // 'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                    $imageName = time().'.'.$request->upload_file->getClientOriginalExtension();
                    $image = $request->file('upload_file');
                    $t = Storage::disk('s3')->put($imageName, file_get_contents($image), 'public');
                    $imageName = Storage::disk('s3')->url($imageName);
                    supplier_brochure::create(['supplier_id' => $res['id'],
                                             'upload_file' => $imageName]);
                 }
                

         return response()->json([
                'success' => true,
                'data' => $Supplier->toArray()
            ]);
         }  
        else{
            return response()->json([
                'success' => false,
                'message' => 'Supplier could not be added'
            ], 500);
 
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showbyid($id)
    {
        $Supplier =  Supplier::find($id);

 
        if (!$Supplier) {
            return response()->json([
                'success' => false,
                'message' => ' Supplier with id ' . $id . ' not found'
            ]);
        }
        $Supplier['Images'] = Supplier_img::where('supplier_id','=', $Supplier->id)->get();
        $Supplier['upload_file'] = supplier_brochure::where('supplier_id','=', $Supplier['id'])->get();
        return response()->json([
            'success' => true,
            'data' =>  $Supplier->toArray()
        ], 200);
    }

    public function showbysubcategoryid($id)
    {
        $Supplier=Supplier::where('subcategory_id','=', $id)->get();
    
  if (count($Supplier)<1) {
            return response()->json([
                'success' => false,
                'message' => ' Supplier with id ' . $id . ' not found'
            ]);
        }
        else{
            foreach($Supplier as $ser){

        $ser['Images'] = Supplier_img::where('supplier_id','=', $ser['id'])->get();
            }
        return response()->json([
            'success' => true,
            'data' =>  $Supplier
        ], 200);
    }
    }

    public function showdetails($category,$subcategory,$id)
    {
    $Supplier=Supplier::where('subcategory_id', '=', $subcategory)->where('category_id', '=', $category)->where('id', '=', $id)->get();
       
        if(count($Supplier)<1){
            return response()->json("record not found",404);
        }
        else{
            foreach($Supplier as $ser){
     
                $ser['Images'] = Supplier_img::where('supplier_id','=', $ser['id'])->get();
               
                }
             
        return response()->json([
            'success' => true,
            'data' => $Supplier
        ], 200);
    }
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
        $Supplier= auth()->user()->supplier()->find($id);
        if (!$Supplier) {
            return response()->json([
                'success' => false,
                'message' => 'Supplier with id ' . $id . ' not found'
            ], 400);
        }

        $Supplier->update($request->all());  
       $Supplier->save();
 
        if ($Supplier)
            return response()->json([
                'success' => true,
                'data'=>$Supplier
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Supplier could not be updated'
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
        $Supplier = auth()->user()->supplier()->find($id);
 
        if (!$Supplier) {
            return response()->json([
                'success' => false,
                'message' => 'Supplier with id ' . $id . ' not found'
            ], 400);
        }
        DB::table('supplier_imgs')->where('supplier_id', $id)->delete();
        if ($Supplier->delete()) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'request could not be deleted'
            ], 500);
        }
 
    }
    public function insertBrochure(Request $request)
    {
       
    
        $imageName = "";
       
        $Image = new supplier_brochure();
      
        $Image->upload_file= $imageName;
      
     
        if ($request->hasFile('upload_file')) {
            $supplier =$request->input('supplier_id');
            $imageName = time().'.'.$request->upload_file->getClientOriginalExtension();
            $image = $request->file('upload_file');
            $t = Storage::disk('s3')->put($imageName, file_get_contents($image), 'public');
            $imageName = Storage::disk('s3')->url($imageName);
            $upload_file= supplier_brochure::create(['supplier_id' => $supplier,
                                     'upload_file' => $imageName]);
         }
        

            return response()->json([
                'success' => true,
                'data' => $upload_file
            ]);
            }  
    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteBrochure($id)
    {
        $supplier_brochure= supplier_brochure::where('supplier_id','=', $id);
        $supplier_brochure->delete();
        return response()->json( $supplier_brochure);
    }
}
