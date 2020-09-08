<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use App\CompanyBroucher;
use Illuminate\Http\Request;

class CompanyBroucherController extends Controller
{
    public function index()
    {
        $companies = CompanyBroucher::all()->toArray();
        return $companies;
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'upload_file' => 'required',
           
            'upload_file.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            
        ]);
       
    
        $imageName = "";
       
        $Image = new CompanyBroucher();
      
        $Image->upload_file= $imageName;
      
     
        if ($request->hasFile('upload_file')) {
            $company =$request->input('companies_id');
            $imageName = time().'.'.$request->upload_file->getClientOriginalExtension();
            $image = $request->file('upload_file');
            $t = Storage::disk('s3')->put($imageName, file_get_contents($image), 'public');
            $imageName = Storage::disk('s3')->url($imageName);
            $upload_file=CompanyBroucher::create(['companies_id' => $company,
                                     'upload_file' => $imageName]);
         }
        

            return response()->json([
                'success' => true,
                'data' =>$upload_file
            ]);
        
       
    }
    public function destroy($id)
    {
        $c_brochure=CompanyBroucher::where('companies_id','=', $id);
        $c_brochure->delete();
        return response()->json( $c_brochure);
    }
}
