<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Requestdata;
class Requestcontroller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Requestdata = auth()->user()->requests;
 
        return response()->json([
            'success' => true,
            'data' => $Requestdata
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
     * @param  \Illuminate\Http\Request $Requestdata
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request )
    {
            $this->validate($request, [
            'name' => 'required|regex:/^[a-zA-Z]+$/u|min:2',
            'url'=>'required',
            'contact' => 'required',
            'alternative_name'=>'require|regex:/^[a-zA-Z]+$/u|min:2',
            'alternative_contact' => 'required|integer',
            'company'=>'required|regex:/^[a-zA-Z]+$/u|min:2',
            'country' => 'required|regex:/^[a-zA-Z]+$/u|min:2',
            'title'=>'required',
            'discription' => 'required',  
            'type'=>'required',
            'location' => 'required',
            'email'=>'required|email'
        ]);
 
      $Requestdata = new Requestdata();
      $Requestdata->name = $request->name;
      $Requestdata->url = $request->url;
      $Requestdata->contact = $request->contact;
      $Requestdata->alternative_name = $request->alternative_name;
      $Requestdata->alternative_contact = $request->alternative_contact;
      $Requestdata->company = $request->company;
      $Requestdata->country = $request->country;
      $Requestdata->title = $request->title;
      $Requestdata->discription = $request->discription;
      $Requestdata->type = $request->type;
      $Requestdata->location = $request->location;
      $Requestdata->email = $request->email;
 
 
        if (auth()->user()->requests()->save($Requestdata))
            return response()->json([
                'success' => true,
                'data' =>$Requestdata->toArray()
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'request could not be added'
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
        $Requestdata = auth()->user()->requests()->find($id);
 
        if (!$Requestdata) {
            return response()->json([
                'success' => false,
                'message' => 'Request with id ' . $id . ' not found'
            ], 400);
        }
 
        return response()->json([
            'success' => true,
            'data' => $Requestdata->toArray()
        ], 400);
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
     * @param  \Illuminate\Http\Request $Requestdata
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      
        $Requestdata = auth()->user()->requests()->find($id);
 
        if (!$Requestdata) {
            return response()->json([
                'success' => false,
                'message' => 'request with id ' . $id . ' not found'
            ], 400);
        }
 
        $updated = $Requestdata->fill($request->all())->save();
 
        if ($updated)
            return response()->json([
                'success' => true
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'request could not be updated'
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
        $Requestdata = auth()->user()->requests()->find($id);
 
        if (!$Requestdata) {
            return response()->json([
                'success' => false,
                'message' => 'request with id ' . $id . ' not found'
            ], 400);
        }
 
        if ($Requestdata->delete()) {
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
}
