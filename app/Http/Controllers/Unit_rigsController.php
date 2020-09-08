<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Unit_rigs;
use App\Unit_rigsImg;
use App\Category;
use App\Subcategory;
use App\user;
use App\unit_rigs_brochure;

class Unit_rigsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$Unit_rigs=Unit_rigs::all();
        $Unit_rigs=Unit_rigs::paginate(12);
       
        foreach($Unit_rigs as $ser){
            $ser['Images'] = Unit_rigsImg::where('unit_rigs_id','=',$ser->id)->get();
            $ser['upload_file'] = unit_rigs_brochure::where('unit_rigs_id','=', $ser['id'])->get();
        }
        return response()->json([
            'success' => true,
            'data' => $Unit_rigs
        ]);
    }

    public function Unit_rigsdesc()
    {
        $Unit_rigs = Unit_rigs::orderBy('name', 'desc')->get();
        foreach($Unit_rigs as $eq){
            $eq['Images'] =Unit_rigsImg::where('unit_rigs_id','=',$eq->id)->get();
            $tmp_data[] = $eq;
        }
       return response()->json([
           'success' => true,
           'data' => $Unit_rigs
       ]);
   }
   public function Unit_rigsasc()
   {
       $Unit_rigs = Unit_rigs::orderBy('name', 'asc')->get();
       foreach($Unit_rigs as $eq){
        $eq['Images'] =Unit_rigsImg::where('unit_rigs_id','=',$eq->id)->get();
        $tmp_data[] = $eq;
    }
      return response()->json([
          'success' => true,
          'data' => $Unit_rigs
      ]);
  }
  public function companyasc()
  {
      $Unit_rigs = Unit_rigs::orderBy('company', 'asc')->get();
      foreach($Unit_rigs as $eq){
       $eq['Images'] =Unit_rigsImg::where('unit_rigs_id','=',$eq->id)->get();
       $tmp_data[] = $eq;
   }
     return response()->json([
         'success' => true,
         'data' => $Unit_rigs
     ]);
 }
 public function companydesc()
 {
     $Unit_rigs =Unit_rigs::orderBy('company', 'desc')->get();
     foreach($Unit_rigs as $eq){
      $eq['Images'] = Unit_rigsImg::where('unit_rigs_id','=',$eq->id)->get();
      $tmp_data[] = $eq;
  }
    return response()->json([
        'success' => true,
        'data' => $Unit_rigs
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
         $Unit_rigs = new Unit_rigs();
         $Unit_rigs->name = $request->input('name');
         $Unit_rigs->contact_person = $request->input('contact_person');
         $Unit_rigs->mobile = $request->input('mobile');
         $Unit_rigs->description = $request->input('description');
         $Unit_rigs->company = $request->input('company');
         $Unit_rigs->country_code= $request->input('country_code');
         $Unit_rigs->email = $request->input('email');
         $Unit_rigs->alt_email= $request->input('alt_email');
         $Unit_rigs->address= $request->input('address');
         $Unit_rigs->latitude= $request->input('latitude');
         $Unit_rigs->longitude= $request->input('longitude');
         $Unit_rigs->admin= $request->input('admin');
         $Unit_rigs->category_id=$request->category_id;
         $Unit_rigs->subcategory_id=$request->subcategory_id;
         $Unit_rigs->image= $imageName;
         $Unit_rigs->upload_file= $imageName;
       
       
         $file_count = count($request->image);
         if($file_count > 5){
             return response()->json([
                 'success' => false,
                 'message' => ' upload max 5 images'
             ], 500);
            }
     
    
         if (auth()->user()->unit_rigs()->save($Unit_rigs)){
             $res = $Unit_rigs->toArray();
           echo($res['id']);
                if ($request->hasFile('image')) {
                  $image = $request->image;
                  $count = 1;
                      foreach($image as $img){
                         $count++;
                         $imageName = time().$count.'.'.$img->getClientOriginalExtension();
                         $t = Storage::disk('s3')->put($imageName, file_get_contents( $img), 'public');
                         $imageName= Storage::disk('s3')->url($imageName);
                         Unit_rigsImg::create(['unit_rigs_id' => $res['id'],
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
                   unit_rigs_brochure::create(['unit_rigs_id' => $res['id'],
                                             'upload_file' => $imageName]);
                 }
                

         return response()->json([
                'success' => true,
                'data' => $Unit_rigs->toArray()
            ]);
         }  
        else{
            return response()->json([
                'success' => false,
                'message' => 'Unit_rigs could not be added'
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
        $Unit_rigs = Unit_rigs::find($id);

 
        if (!$Unit_rigs) {
            return response()->json([
                'success' => false,
                'message' => ' Unit_rigs with id ' . $id . ' not found'
            ]);
        }
        $Unit_rigs['Images'] = Unit_rigsImg::where('unit_rigs_id','=', $Unit_rigs->id)->get();
        $Unit_rigs['upload_file'] = unit_rigs_brochure::where('unit_rigs_id','=', $Unit_rigs['id'])->get();
        return response()->json([
            'success' => true,
            'data' =>  $Unit_rigs->toArray()
        ], 200);
    }

    public function showbysubcategoryid($id)
    {
        $Unit_rigs = Unit_rigs::where('subcategory_id','=', $id)->get();

 
        if (count($Unit_rigs)<1) {
            return response()->json([
                'success' => false,
                'message' => ' Unit_rigs with id ' . $id . ' not found'
            ]);
        }
        else{
            foreach($Unit_rigs as $ser){
        $ser['Images'] = Unit_rigsImg::where('unit_rigs_id','=', $ser['id'])->get();
            }
        return response()->json([
            'success' => true,
            'data' =>  $Unit_rigs->toArray()
        ], 200);
    }
    }

    public function showdetails($category,$subcategory,$id)
    {
    $Unit_rigs=Unit_rigs::where('subcategory_id', '=', $subcategory)->where('category_id', '=', $category)->where('id', '=', $id)->get();       
        if(count($Unit_rigs)<1){
            return response()->json("record not found");
        }
        else{
            foreach($Unit_rigs as $ser){
     
                $ser['Images'] = Unit_rigsImg::where('unit_rigs_id','=', $ser['id'])->get();
               
                }
             
        return response()->json([
            'success' => true,
            'data' => $Unit_rigs
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
        $Unit_rigs= auth()->user()->unit_rigs()->find($id);
        if (!$Unit_rigs) {
            return response()->json([
                'success' => false,
                'message' => 'Unit_rigs with id ' . $id . ' not found'
            ], 400);
        }

        $Unit_rigs->update($request->all());  
       $Unit_rigs->save();
 
        if ($Unit_rigs)
            return response()->json([
                'success' => true,
                'data'=>$Unit_rigs
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Unit_rigs could not be updated'
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
        $Unit_rigs = auth()->user()->unit_rigs()->find($id);
 
        if (!$Unit_rigs) {
            return response()->json([
                'success' => false,
                'message' => 'Unit_rigs with id ' . $id . ' not found'
            ], 400);
        }
        DB::table('unit_rigs_imgs')->where('unit_rigs_id', $id)->delete();
        if ($Unit_rigs->delete()) {
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
       
        $Image = new unit_rigs_brochure();
      
        $Image->upload_file= $imageName;
      
     
        if ($request->hasFile('upload_file')) {
            $unit_rigs =$request->input('unit_rigs_id');
            $imageName = time().'.'.$request->upload_file->getClientOriginalExtension();
            $image = $request->file('upload_file');
            $t = Storage::disk('s3')->put($imageName, file_get_contents($image), 'public');
            $imageName = Storage::disk('s3')->url($imageName);
            $upload_file= unit_rigs_brochure::create(['unit_rigs_id' => $unit_rigs,
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
        $unit_rigs_brochure= unit_rigs_brochure::where('unit_rigs_id','=', $id);
        $unit_rigs_brochure->delete();
        return response()->json( $unit_rigs_brochure);
    }
}
