<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all()->toArray();
        return $categories;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function enpSave(Request $request)
    {
        $categories = Category::create($request->all());
        return $categories;
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
            
            'category_name'    =>  'required'
        ]);
        $categories = new Category([
            
            'category_name'    =>  $request->get('category_name')
        ]);
        $categories->save();
        return redirect()->route('Category.create')->with('success', 'Data Added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showbyID($id)
    {
        $categories=Category::find($id);
       return response()->json($categories);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function categorybyid(Request $request,$id)
    {
      
        $categories=Category::find($id);
        $categories->category_name=$request->input('category_name');
        
        $categories->save();
        return response()->json($categories);
        
    }

  
    public function deleteByID(Request $request,$id)
    {
      $categories=Category::find($id);
      $categories->delete();
      return response()->json($categories);
    }
}
