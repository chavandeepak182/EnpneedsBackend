<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Subcategory;
use App\Category;
class Subcategorycontroller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subcategories = Subcategory::all()->toArray();
        return $subcategories;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function subcategorydesc($id)
    {
        $subcategories = Subcategory::orderBy('subcategory_name', 'desc')->where('category_id',$id)->get();
    
       return response()->json([
           'success' => true,
           'data' => $subcategories
       ]);
   }


   public function subcategoryasc($id)
 {
    $subcategories = Subcategory::orderBy('subcategory_name', 'asc')->where('category_id',$id)->get();
    
       return response()->json([
           'success' => true,
           'data' => $subcategories
       ]);
}


public function search(Request $request ,$id){
    $q = $request->input('search');
  // $q='ram';
  $subcategories = Subcategory::where('subcategory_name', 'like', '%'.$q.'%')->where('category_id',  $id)->get();
   if(count($subcategories) > 0){
       return response()->json([
           'success' => true,
           'data' => $subcategories
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
    public function enpSave(Request $request)
    {
        $subcategories = Subcategory::create($request->all());
        return $subcategories;
    }
    public function store(Request $request)
    {
      
        $this->validate($request, [
            'cateogry_id'=> 'required',
            'subcategory_name'    =>  'required'
        ]);
        $subcategories = new Subcategory([
            'category_id'    =>  $request->get('category_id'),
            'subcategory_name'    =>  $request->get('subcategory_name')
        ]);
        $subcategories->save();
        return redirect()->route('Subcategory.create')->with('success', 'Data Added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $Subcategory=Subcategory::where('category_id','=', $id)->get();
        if(is_null($Subcategory)){
            return response()->json("record not found",404);
        }
        return response()->json( $Subcategory,200); 
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
       
        $Subcategory= Subcategory::find($id);
 
        if (!$Subcategory) {
            return response()->json([
                'success' => false,
                'message' => 'subcategory with id ' . $id . ' not found'
            ], 400);
        }
 
        $updated = $Subcategory->fill($request->all())->save();
 
        if ($updated)
            return response()->json([
                'success' => true
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'subcategory could not be updated'
            ], 500);
 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteByID(Request $request,$id)
    {
      $subcategories=Subcategory::find($id);
      $subcategories->delete();
      return response()->json($subcategories);
    }
}
