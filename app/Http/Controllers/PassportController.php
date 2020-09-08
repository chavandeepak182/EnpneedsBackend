<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\User;
use Illuminate\Http\Request;

class PassportController extends Controller
{
/**
* Handles Registration Request
*
* @param Request $request
* @return \Illuminate\Http\JsonResponse
*/
public function register(Request $request)
{
$this->validate($request, [
'first_name' => 'required|min:3',
'last_name' => 'required|min:3',
'email' => 'required|email|unique:users',
'password' => 'required|min:6',
'c_password' => 'required|min:6',
'gender' => 'required',
'dob' => 'required'
]);
$fname=$request->input('first_name');
$lname=$request->input('last_name');
$pass=$request->input('password');
$cpass=$request->input('c_password');
$dateOfBirth = $request->input('dob');
//$age = Carbon::parse($dateOfBirth)->age;
$user_age = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%y years, %m months and %d days');
if($fname != $lname)
{
if($user_age>=13)
{
if($pass==$cpass)
{
$user = User::create([
'first_name' => $request->first_name,
'last_name' => $request->last_name,
'email' => $request->email,
'gender' => $request->gender,
'dob' => $request->dob,
'password' => bcrypt($request->password)
]);

$token = $user->createToken('enpneeds')->accessToken;

return response()->json(['token' => $token], 200);
}
else{
return response()->json('please enter correct password');
}
}
else{
return response()->json('You can not register,your age is less than 13 years');
}
}
else{
return response()->json('You firstname and lastname is same. plz change it');
}

}

/**
* Handles Login Request
*
* @param Request $request
* @return \Illuminate\Http\JsonResponse
*/
public function login(Request $request)
{
$credentials = [
'email' => $request->email,
'password' => $request->password
];

if (auth()->attempt($credentials)) {
$token = auth()->user()->createToken('enpneeds')->accessToken;
return response()->json(['token' => $token], 200);
} else {
return response()->json(['error' => 'UnAuthorised'], 401);
}
}

/**
* Returns Authenticated User Details
*
* @return \Illuminate\Http\JsonResponse
*/
public function details()
{
return response()->json(['user' => auth()->user()], 200);
}
}