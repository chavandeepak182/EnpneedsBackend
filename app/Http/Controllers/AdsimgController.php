<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdsimgController extends Controller
{
    public function index()
    {
        $adsimg = Adsimg::all()->toArray();
        return $adsimg;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'ads_img' => 'required',
           
            'ads_img.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            
        ]);
        $file_count = count($request->ads_img);
        if($file_count > 5){
            return response()->json([
                'success' => false,
                'message' => ' upload max 5 images'
            ], 500);
        }
    
        $imageName = "";
        $adsimg = new Ads();
       
        $post->ads_img = $imageName;
         
 
        if (auth()->user()->adsimgs()->save($adsimg)){
            $res = $adsimg->toArray();
            if ($request->hasFile('ads_img')) {
        
                $image = $request->ads_img;
                $count = 1;
                foreach($image as $img){
                    $count++;
                    $imageName = time().$count.'.'.$img->getClientOriginalExtension();
    
                    $t = Storage::disk('s3')->put($imageName, file_get_contents($img), 'public');
                    $imageName = Storage::disk('s3')->url($imageName);
                    $insert_image['ads_id'] = $res['id'];
                    $insert_image['ads_img'] = $imageName;
                    Adsimg::create($insert_image);
                }
                
            }
                return response()->json([
                'success' => true,
                'data' => $adsimg->toArray()
            ]);
        }
        else{
            return response()->json([
                'success' => false,
                'message' => 'Ads could not be added'
            ], 500);
        }
    }

       
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
 
