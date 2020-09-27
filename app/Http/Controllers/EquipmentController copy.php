<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Equipment;
use App\Equipment_image;
use App\Category;
use App\Subcategory;
use App\user;
use App\equipment_brochure;

class EquipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       // $equipment = Equipment::all();
       $equipment = Equipment::paginate(12);
        foreach($equipment as $eq){
            $eq['Images'] = Equipment_image::where('equipment_id','=',$eq->id)->get();
            $eq['upload_file'] = equipment_brochure::where('equipment_id','=', $eq['id'])->get();
        }
        return response()->json([
            'success' => true,
            'data' => $equipment
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
            'image' => 'required|',
            'image.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'admin'=>'required',
            'upload_file'=>'required|mimes:pdf,xlx,csv|max:2048'
           
        ]);
        $imageName = "";
         $equipment = new Equipment();
         $equipment->name = $request->input('name');
         $equipment->contact_person = $request->input('contact_person');
         $equipment->mobile = $request->input('mobile');
         $equipment->description = $request->input('description');
         $equipment->company = $request->input('company');
         $equipment->country_code= $request->input('country_code');
         $equipment->email = $request->input('email');
         $equipment->alt_email= $request->input('alt_email');
         $equipment->address= $request->input('address');
         $equipment->latitude= $request->input('latitude');
         $equipment->longitude= $request->input('longitude');
         $equipment->admin= $request->input('admin');
         $equipment->category_id=$request->category_id;
        $equipment->subcategory_id=$request->subcategory_id;
         $equipment->image= $imageName;
         $equipment->upload_file= $imageName;
        
         $file_count = count($request->image);
         if($file_count > 5){
             return response()->json([
                 'success' => false,
                 'message' => ' upload max 5 images'
             ], 500);
            }
     
    
         if (auth()->user()->equipment()->save($equipment)){
             $res = $equipment->toArray();
           
                if ($request->hasFile('image')) {
                  $image = $request->image;
                  $count = 1;
                      foreach($image as $img){
                         $count++;
                         $imageName = time().$count.'.'.$img->getClientOriginalExtension();
                         $t = Storage::disk('s3')->put($imageName, file_get_contents( $img), 'public');
                         $imageName= Storage::disk('s3')->url($imageName);
                         Equipment_image::create(['equipment_id' => $res['id'],
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
                   equipment_brochure::create(['equipment_id' => $res['id'],
                                             'upload_file' => $imageName]);
                 }
                
         return response()->json([
                'success' => true,
                'data' => $equipment->toArray()
            ]);
         }  
        else{
            return response()->json([
                'success' => false,
                'message' => 'equipment could not be added'
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
        $equipment = Equipment::find($id);

 
        if (!$equipment) {
            return response()->json([
                'success' => false,
                'message' => ' equipment with id ' . $id . ' not found'
            ]);
        }
        
        $equipment['Images'] = Equipment_image::where('equipment_id','=', $equipment->id)->get();
        $equipment['upload_file'] = equipment_brochure::where('equipment_id','=', $equipment['id'])->get();
        return response()->json([
            'success' => true,
            'data' =>  $equipment->toArray()
        ], 200);

    }

    public function showbysubcategoryid($id)
    {
        $equipment = Equipment::where('subcategory_id','=', $id)->get();

 
        if (count($equipment)<1) {
            return response()->json([
                'success' => false,
                'message' => ' equipment with id ' . $id . ' not found'
            ]);
        }
        else{
            foreach($equipment as $ser){

        $ser['Images'] = Equipment_image::where('equipment_id','=', $ser['id'])->get();
            }
        return response()->json([
            'success' => true,
            'data' =>  $equipment->toArray()
        ], 200);
    }

    }
    public function showdetails($category,$subcategory,$id)
    {
    $Equipment=Equipment::where('subcategory_id', '=', $subcategory)->where('category_id', '=', $category)->where('id', '=', $id)->get();
       
        if(count($Equipment)<1){
            return response()->json("record not found");
        }
        else{
            foreach($Equipment as $ser){
     
                $ser['Images'] = Equipment_image::where('equipment_id','=', $ser['id'])->get();
               
                }
             
        return response()->json([
            'success' => true,
            'data' => $Equipment
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
        $equipment= auth()->user()->equipment()->find($id);
        if (!$equipment) {
            return response()->json([
                'success' => false,
                'message' => 'equipment with id ' . $id . ' not found'
            ], 400);
        }

        $equipment->update($request->all());  
       $equipment->save();
 
        if ($equipment)
            return response()->json([
                'success' => true,
                'data'=>$equipment
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'equipment could not be updated'
            ], 500);
            

    }
    public function equipmentdesc()
    {
        $equipment= Equipment::orderBy('name', 'desc')->get();
        foreach($equipment as $eq){
            $eq['Images'] = Equipment_image::where('equipment_id','=',$eq->id)->get();
            $tmp_data[] = $eq;
        }
       return response()->json([
           'success' => true,
           'data' => $equipment
       ]);
   }
   public function equipmentasc()
   {
       $equipment = Equipment::orderBy('name', 'asc')->get();
       foreach($equipment as $eq){
        $eq['Images'] = Equipment_image::where('equipment_id','=',$eq->id)->get();
        $tmp_data[] = $eq;
    }
      return response()->json([
          'success' => true,
          'data' => $equipment
      ]);
  }

  public function companyasc()
  {
      $equipment = Equipment::orderBy('company', 'asc')->get();
      foreach($equipment as $eq){
       $eq['Images'] = Equipment_image::where('equipment_id','=',$eq->id)->get();
       $tmp_data[] = $eq;
   }
     return response()->json([
         'success' => true,
         'data' => $equipment
     ]);
 }
 public function companydesc()
 {
     $equipment = Equipment::orderBy('company', 'desc')->get();
     foreach($equipment as $eq){
      $eq['Images'] = Equipment_image::where('equipment_id','=',$eq->id)->get();
      $tmp_data[] = $eq;
  }
    return response()->json([
        'success' => true,
        'data' => $equipment
    ]);
}
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $equipment = auth()->user()->equipment()->find($id);
 
        if (!$equipment) {
            return response()->json([
                'success' => false,
                'message' => 'equipment with id ' . $id . ' not found'
            ], 400);
        }
        DB::table('equipment_images')->where('equipment_id', $id)->delete();
        if ($equipment->delete()) {
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
       
        $Image = new equipment_brochure();
      
        $Image->upload_file= $imageName;
      
     
        if ($request->hasFile('upload_file')) {
            $equipment =$request->input('equipment_id');
            $imageName = time().'.'.$request->upload_file->getClientOriginalExtension();
            $image = $request->file('upload_file');
            $t = Storage::disk('s3')->put($imageName, file_get_contents($image), 'public');
            $imageName = Storage::disk('s3')->url($imageName);
            $upload_file=equipment_brochure::create(['equipment_id' => $equipment,
                                     'upload_file' => $imageName]);
         }
        

            return response()->json([
                'success' => true,
                'data' =>$upload_file
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
        $service_brochure= equipment_brochure::where('equipment_id','=', $id);
        $service_brochure->delete();
        return response()->json( $service_brochure);
    }
}
