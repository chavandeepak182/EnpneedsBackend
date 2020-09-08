<?php
 
namespace App\Http\Controllers;
 
use App\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = auth()->user()->addresses;
 
        return response()->json([
            'success' => true,
            'data' => $addresses
        ]);
    }
 
    public function show($id)
    {
        $address = auth()->user()->addresses()->find($id);
 
        if (!$address) {
            return response()->json([
                'success' => false,
                'message' => 'Address with id ' . $id . ' not found'
            ], 400);
        }
 
        return response()->json([
            'success' => true,
            'data' => $address->toArray()
        ], 400);
    }
 
    public function store(Request $request)
    {
        $this->validate($request, [
            'housenumber' => 'required',
            'street' => 'required|integer',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'zipcode' => 'required',
            'profile_id'=>'required'
        ]);
 
        $address = new Address();
        
        $address->housenumber = $request->housenumber;
        $address->street = $request->street;
        $address->city = $request->city;
        $address->state = $request->state;
        $address->country = $request->country;
        $address->zipcode = $request->zipcode;
        $address->profile_id = $request->profile_id;
 
        if (auth()->user()->addresses()->save($address))
            return response()->json([
                'success' => true,
                'data' => $address->toArray()
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Address could not be added'
            ], 500);
    }
 
    public function update(Request $request, $id)
    {
        Log::info('Update address: '.$id);
        Log::info('Request: '.$request);
        $address = auth()->user()->addresses()->find($id);
 
        if (!$address) {
            return response()->json([
                'success' => false,
                'message' => 'Address with id ' . $id . ' not found'
            ], 400);
        }
 
        $updated = $address->fill($request->all())->save();
 
        if ($updated)
            return response()->json([
                'success' => true
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Address could not be updated'
            ], 500);
    }
 
    public function destroy($id)
    {
        $address = auth()->user()->addresses()->find($id);
 
        if (!$address) {
            return response()->json([
                'success' => false,
                'message' => 'Address with id ' . $id . ' not found'
            ], 400);
        }
 
        if ($address->delete()) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Address could not be deleted'
            ], 500);
        }
    }
}
