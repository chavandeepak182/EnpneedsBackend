<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use App\Company_image;
use Illuminate\Http\Request;

class CompanyImgController extends Controller
{
    public function index()
    {
        $companies =Company_image::all();
        return response()->json([
            'success' => true,
            'data' => $companies
        ]);
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'image' => 'required',
           
            'image.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            
        ]);
        
    
        $imageName = "";
       
        $Image = new Company_image();
      
        $Image->image= $imageName;
      
     
        if ($request->hasFile('image')) {
            $company =$request->input('company_id');
            $imageName = time().'.'.$request->image->getClientOriginalExtension();
            $image = $request->file('image');
            $t = Storage::disk('s3')->put($imageName, file_get_contents($image), 'public');
            $imageName = Storage::disk('s3')->url($imageName);
            $upload_file=Company_image::create(['company_id' => $company,
                                     'image' => $imageName]);
         }
        

            return response()->json([
                'success' => true,
                'data' =>$upload_file
            ]);
       }
       public function destroy($id)
       {
           $c_brochure=Company_image::where('company_id','=', $id);
           $c_brochure->delete();
           return response()->json( $c_brochure);
       }
}
