<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Service;
use App\service_brochure;
use App\Service_image;
use App\Category;
use App\Subcategory;
use App\user;
class Servicescontroller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   /*public function index()
    {
        $Service = auth()->user()->service;
        $tmp_data = [];
        foreach($Service as $ser){
            $ser['Images'] = service_image::where('service_id','=',$ser->id)->get();
            $tmp_data[] = $ser;
        }
        return response()->json([
            'success' => true,
            'data' => $Service
        ]);
    }*/
    public function index()
    {
    //$Service =Service::all();
    $Service =Service::paginate(12);
    foreach($Service as $ser){
     
       $ser['Images'] = service_image::where('service_id','=', $ser['id'])->get();
      
       
       $ser['upload_file'] = service_brochure::where('service_id','=', $ser['id'])->get();
    }
       return response()->json([
           'success' => true,
           'data' => $Service
       ]);
   }
   public function servicedesc()
    {
        $Service = Service::orderBy('name', 'desc')->get();
        foreach($Service as $eq){
            $eq['Images'] =Service_image::where('service_id','=',$eq->id)->get();
            $tmp_data[] = $eq;
        }
       return response()->json([
           'success' => true,
           'data' => $Service
       ]);
   }
   public function serviceasc()
   {
       $Service = Service::orderBy('name', 'asc')->get();
       foreach($Service as $eq){
        $eq['Images'] =Service_image::where('service_id','=',$eq->id)->get();
        $tmp_data[] = $eq;
    }
      return response()->json([
          'success' => true,
          'data' => $Service
      ]);
  }
  public function companyasc()
  {
      $Service = Service::orderBy('company', 'asc')->get();
      foreach($Service as $eq){
       $eq['Images'] =Service_image::where('service_id','=',$eq->id)->get();
       $tmp_data[] = $eq;
   }
     return response()->json([
         'success' => true,
         'data' => $Service
     ]);
 }
 public function companydesc()
 {
     $Service =Service::orderBy('company', 'desc')->get();
     foreach($Service as $eq){
      $eq['Images'] = Service_image::where('service_id','=',$eq->id)->get();
      $tmp_data[] = $eq;
  }
    return response()->json([
        'success' => true,
        'data' => $Service
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
            'category_id'=>'required',
            'subcategory_id'=>'required',
            'name' => 'required',
            'contact_person' => 'required',
            'mobile'=>'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|numeric',
            'description' => 'required',
            'company'=>'required',
            'address' => 'required',
            'latitude'=>'required|string',
            'longitude' => 'required',
            'email' => 'required',
            'country_code'=>'required',
            'image' => 'required',
            'image.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'admin'=>'required',
            'upload_file'=>'required|mimes:pdf,xlx,csv|max:2048'
           
        ]);
        $imageName = "";
        $Service = new Service();
        $Service->category_id=$request->category_id;
        $Service->subcategory_id=$request->subcategory_id;
        $Service->name = $request->input('name');
        $Service->contact_person = $request->input('contact_person');
        $Service->mobile = $request->input('mobile');
        $Service->description = $request->input('description');
        $Service->company = $request->input('company');
        $Service->country_code= $request->input('country_code');
         $Service->email = $request->input('email');
         $Service->alt_email= $request->input('alt_email');
        $Service->address= $request->input('address');
        $Service->latitude= $request->input('latitude');
        $Service->longitude= $request->input('longitude');
        $Service->admin= $request->input('admin');
        $Service->image= $imageName;
        $Service->upload_file= $imageName;
        $file_count = count($request->image);
        if($file_count > 5){
            return response()->json([
                'success' => false,
                'message' => ' upload max 5 images'
            ], 500);
           }
        if (auth()->user()->service()->save($Service)){
            $res = $Service->toArray();
          
               if ($request->hasFile('image')) {
                 $image = $request->image;
                 $count = 1;
                     foreach($image as $img){
                        $count++;
                        $imageName = time().$count.'.'.$img->getClientOriginalExtension();
                        $t = Storage::disk('s3')->put($imageName, file_get_contents( $img), 'public');
                        $imageName= Storage::disk('s3')->url($imageName);
                        Service_image::create(['service_id' => $res['id'],
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
                    service_brochure::create(['service_id' => $res['id'],
                                             'upload_file' => $imageName]);
                 }
                

        return response()->json([
               'success' => true,
               'data' => $Service->toArray()
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
        $Service = Service::find($id);
    
            if (!$Service) {
                return response()->json([
                    'success' => false,
                    'message' => 'service with id ' . $id . ' not found'
                ]);
             }
        
             $Service['Images'] = Service_image::where('service_id','=', $Service->id)->get();
             $service['upload_file'] = service_brochure::where('service_id','=', $service['id'])->get();   
             
        return response()->json([
            'success' => true,
            'data' => $Service
        ], 200);

    }
    public function showbysubcategoryid($id)
    {
       
        $Service=Service::where('subcategory_id','=', $id)->get();
       
        if(count($Service)<1){
            return response()->json([
                'success' => false,
                'message' => ' Service with id ' . $id . ' not found'
            ]);
        }
        else{
            foreach($Service as $ser){
     
                $ser['Images'] = service_image::where('service_id','=', $ser['id'])->get();
               
                }
             
        return response()->json([
            'success' => true,
            'data' => $Service
        ], 200);
    }
    }
    public function showdetails($category,$subcategory,$id)
    {
       // $Service=DB::select('select * FROM services where category_id=1 and subcategory_id=1 and id=1');
        
   $Service=Service::where('subcategory_id', '=', $subcategory)->where('category_id', '=', $category)->where('id', '=', $id)->get();
      
        if(count($Service)<1){
            return response()->json("record not found");
        }
        else{
            foreach($Service as $ser){
     
                $ser['Images'] = service_image::where('service_id','=', $ser['id'])->get();
               
                }
             
        return response()->json([
            'success' => true,
            'data' => $Service
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
        Log::info('Update request: '.$id);
        Log::info('Service: '.$request);
        $Service = auth()->user()->service()->find($id);
         
  
         if (!$Service) {
             return response()->json([
                 'success' => false,
                 'message' => 'request with id ' . $id . ' not found'
             ], 400);
         }
 
         $updated = $Service->fill($request->all())->save();
  
         if ($Service)
             return response()->json([
                 'success' => true,
                 'data'=>$Service
             ]);
         else
             return response()->json([
                 'success' => false,
                 'message' => 'service could not be updated'
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
        $Service = auth()->user()->service()->find($id);
 
        if (!$Service) {
            return response()->json([
                'success' => false,
                'message' => 'request with id ' . $id . ' not found'
            ], 400);
        }
        DB::table('service_images')->where('service_id', $id)->delete();
        if ($Service->delete()) {
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
       
        $Image = new service_brochure();
      
        $Image->upload_file= $imageName;
      
     
        if ($request->hasFile('upload_file')) {
            $service =$request->input('service_id');
            $imageName = time().'.'.$request->upload_file->getClientOriginalExtension();
            $image = $request->file('upload_file');
            $t = Storage::disk('s3')->put($imageName, file_get_contents($image), 'public');
            $imageName = Storage::disk('s3')->url($imageName);
           $upload_file= service_brochure::create(['service_id' => $service,
                                     'upload_file' => $imageName]);
         }
        

            return response()->json([
                'success' => true,
                'data' =>  $upload_file
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
        $service_brochure= service_brochure::where('service_id','=', $id);
        $service_brochure->delete();
        return response()->json( $service_brochure);
    }
}
