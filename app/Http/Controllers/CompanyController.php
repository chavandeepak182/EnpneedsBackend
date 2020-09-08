<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\About;
use App\Follow;
use Illuminate\Support\Facades\DB;
use App\Company;
use App\CompanyBroucher;
use App\Company_image;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::paginate(10);
        foreach($companies as $com){
        $com['image'] = Company_image::where('company_id','=', $com['id'])->get();
        $com['upload_file'] = CompanyBroucher::where('companies_id','=', $com['id'])->get();
        }
        return response()->json([
            'success' => true,
            'data' => $companies
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function companyfollow(Request $request)
    {
        $user=auth()->user();
        $follows= Follow::where('user_id','=',$user->id)->get();
        if(!empty($follow)){
        foreach ($follows as $follow)
        {
        $arr[]=$follow->company_id;
        }
        $companyfollows=Company::whereNotIn('id',$arr)->get();
        foreach($companyfollows as $com){
        $com['image'] = Company_image::where('company_id','=', $com['id'])->get();
        
        }
        
        
        return response()->json([
        'data'=> $companyfollows
        ], 200);
        }
        else{
        $companyfollows= Company::all();
        foreach($companyfollows as $com){
        $com['image'] = Company_image::where('company_id','=', $com['id'])->get();
        
        }
        return response()->json([
        'data'=> $companyfollows
        ], 200);
        
        
        }
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
            'website_url'=>'required',
            'address' => 'required',
            'email'=>'required',
            'alt_email' => 'required',
            'c_size'=>'required',
            'c_type' => 'required',
            'founded_date'=>'required',
            'company_details' => 'required',
            'latitute' => 'required',
            'longitute' => 'required',
            'image' => 'required',
            'image.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'upload_file'=>'required',
            'upload_file.*'=>'required|mimes:pdf,xlx,csv|max:2048'
           
        ]);
        $imageName = "";
        $companies = new Company();
        $companies->name = $request->input('name');
        $companies->website_url = $request->input('website_url');
        $companies->address = $request->input('address');
        $companies->email = $request->input('email');
        $companies->alt_email = $request->input('alt_email');
        $companies->c_size = $request->input('c_size');
        $companies->c_type= $request->input('c_type');
        $companies->founded_date = $request->input('founded_date');
        $companies->company_details= $request->input('company_details');
        $companies->latitute=$request->input('latitute');
        $companies->longitute=$request->input('longitute');
        $companies->image= $imageName;
        $companies->upload_file= $imageName;
        
  if (auth()->user()->companies()->save($companies)){
            $res = $companies->toArray();
      if ($request->hasFile('image')) {
        //$this->validate($request, [
           // 'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        $imageName = time().'.'.$request->image->getClientOriginalExtension();
        $image = $request->file('image');
        $t = Storage::disk('s3')->put($imageName, file_get_contents($image), 'public');
        $imageName= Storage::disk('s3')->url($imageName);
        Company_image::create(['company_id' => $res['id'],
                                'image' => $imageName]);
     }
     if ($request->hasFile('upload_file')) {
        //$this->validate($request, [
           // 'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        $imageName = time().'.'.$request->upload_file->getClientOriginalExtension();
        $image = $request->file('upload_file');
        $t = Storage::disk('s3')->put($imageName, file_get_contents($image), 'public');
        $imageName = Storage::disk('s3')->url($imageName);
        CompanyBroucher::create(['companies_id' => $res['id'],
                                'upload_file' => $imageName]);
     }
    
     return response()->json([
         'success' => true,
         'data' => $companies->toArray()
     ]);
     }
            return response()->json([
                'success' => false,
                'message' => 'companies could not be added'
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

        $com=Company::find($id);
        
            $com['image'] = Company_image::where('company_id','=', $com['id'])->get();
            $com['upload_file'] = CompanyBroucher::where('companies_id','=', $com['id'])->get();
           
        if (!$com) {
            return response()->json([
                'success' => false,
                'message' => 'companies with id ' . $id . ' not found'
            ], 400);
        }
 
        return response()->json([
            'success' => true,
            'data' => $com->toArray()
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
      
       $companies = auth()->user()->companies()->find($id);
       
 
        if (!$companies) {
            return response()->json([
                'success' => false,
                'message' => 'companies with id ' . $id . ' not found'
            ], 400);
        }

        $companies->update($request->all());  
       $companies->save();
 
        if ($companies)
            return response()->json([
                'success' => true,
                'data'=>$companies
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'companies could not be updated'
            ], 500);
    
    }

    //download pdf
    public function download($path)
{
    $fs = Storage::getDriver();
    $stream = $fs->readStream($path);
    return \Response::stream(function() use($stream) {
        fpassthru($stream);
    }, 200, [
        "Content-Type" => $fs->getMimetype($path),
        "Content-Length" => $fs->getSize($path),
        "Content-disposition" => "attachment; filename=\"" .basename($path) . "\"",
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
        $companies = auth()->user()->companies()->find($id);
 
        if (!$companies) {
            return response()->json([
                'success' => false,
                'message' => 'companies with id ' . $id . ' not found'
            ], 400);
        }
 
        if ($companies->delete()) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'companies could not be deleted'
            ], 500);
        }
    }

}
