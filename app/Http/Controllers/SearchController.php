<?php

namespace App\Http\Controllers;
use App\User;
use App\Service;
use App\Equipment;
use App\Equipment_image;
use App\Service_image;
use App\Supplier_img;
use App\Unit_rigImg;
use App\Supplier;
use App\Unit_rigs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\backendproject;
class SearchController extends Controller
{
    public function search(Request $request){
         $q = $request->input('search');
       // $q='ram';
           $users = User::where('name', 'like', '%'.$q.'%')->orwhere('email', 'like', '%'.$q.'%')->get();
        if(count($users) > 0){
            return response()->json([
                'success' => true,
                'data' => $users
            ]);
        }
         else
         {
             return response('No Details found. Try to search again !');
         }
       
     }
     public function Servicesearch(Request $request){
        $q = $request->input('search');
      // $q='ram';
          $service = Service::where('name', 'like', '%'.$q.'%')->get();
          foreach($service as $eq){
            $eq['Images'] = Service_image::where('service_id','=',$eq->id)->get();
            }
       if(count($service) > 0){
           return response()->json([
               'success' => true,
               'data' => $service
           ]);
       }
        else
        {
            return response()->json([
                'success' => false,
                'data' => false,
            ]);
        }
      
    }
    public function Equipmentsearch(Request $request){
        $q = $request->input('search');
        // $q='ram';
        $equipment = Equipment::where('name', 'like', '%'.$q.'%')->get();
        foreach($equipment as $eq){
        $eq['Images'] = Equipment_image::where('equipment_id','=',$eq->id)->get();
        }
        if(count($equipment) > 0){
        return response()->json([
        'success' => true,
        'data' => $equipment
        ]);
        }
        else
        {
        return response()->json([
        'success' => false,
        'data' => false
        ]);
        }
        
        }
    public function Suppliersearch(Request $request){
        $q = $request->input('search');
      // $q='ram';
          $supplier = Supplier::where('name', 'like', '%'.$q.'%')->get();
          foreach($supplier as $eq){
            $eq['Images'] = Supplier_img::where('supplier_id','=',$eq->id)->get();
            }
       if(count($supplier) > 0){
           return response()->json([
               'success' => true,
               'data' => $supplier
           ]);
       }
        else
        {
            return response()->json([
                'success' => false,
                'data' => false
            ]);
        }
      
    }
    public function Unit_rigsearch(Request $request){
        $q = $request->input('search');
      // $q='ram';
          $unit_rigs = Unit_rigs::where('name', 'like', '%'.$q.'%')->get();
          foreach($unit_rigs as $eq){
            $eq['Images'] = Unit_rigImg::where('unit_rigs_id','=',$eq->id)->get();
            }
       if(count($unit_rigs) > 0){
           return response()->json([
               'success' => true,
               'data' => $unit_rigs
           ]);
       }
        else
        {
            return response()->json([
                'success' => false,
                'data' =>false
            ]);
        }
      
    }
    public function blogsearch(Request $request){
        $q = $request->input('search');
      // $q='ram';
          $blogs = Blog::where('title', 'like', '%'.$q.'%')->get();
         
       if(count($blogs) > 0){
           return response()->json([
               'success' => true,
               'data' => $blogs
           ]);
       }
        else
        {
            return response()->json([
                'success' => false,
                'data' =>false
            ]);
        }
      
    }
}
