<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::post('login', 'PassportController@login');
Route::post('register', 'PassportController@register');

Route::middleware('auth:api')->group(function () {
    Route::get('user', 'PassportController@details');

    Route::put('usersupdate','UserController@update');
    Route::get('userphotos','UserController@getUserphoto');
   
//Friends,follow,connection
    Route::post('userfollow','UserfollowController@userfollow');
    Route::post('send_request','FriendController@send_request');
    Route::post('accept_request','FriendController@accept_request');
    Route::get('friend_list','FriendController@friend_list');
    Route::post('send_conection','FriendController@send_conection');

    Route::post('reject_request','FriendController@reject_request');
    Route::get('suggestion_list','FriendController@suggestion_list');
    Route::get('showalluser/{id}','UserController@show');
   

    Route::resource('products', 'ProductController');


    Route::get('postshowbyuserid','PostController@postshowbyuserid');
    Route::resource('posts', 'PostController');
    Route::resource('comments', 'CommentController');
    Route::resource('replies', 'ReplyController');
    Route::post('likepost', 'PostController@like');
    Route::post('dislikepost', 'PostController@dislike');
    Route::get('postbyid','PostCommentReplyController@show');
   

    Route::post('likecomment', 'CommentController@likes');
    Route::post('dislikecomment', 'CommentController@dislikes');

    Route::post('likereply', 'ReplyController@Likes');
    Route::post('dislikereply', 'ReplyController@DisLikes');


   
    Route::resource('profiles', 'ProfileController');
    Route::get('profileimgs', 'profileimgController@profileimage');
    Route::resource('profileimg', 'profileimgController');
    Route::resource('coverphoto', 'CoverphotoController');
    Route::get('profileshowbyidauth','ProfileController@profileshowbyidauth');

    Route::resource('cdetails', 'CdetailsController');
    Route::resource('education', 'EducationController');
    Route::resource('addresses', 'AddressController');
    Route::resource('experiences', 'ExperienceController');
    Route::resource('abouts', 'AboutController');

    Route::post('follow','FollowController@Follow');
    Route::resource('company','CompanyController');
    Route::resource('whitepaper','WhitepaperController');

    Route::resource('blogs', 'BlogController');
    Route::delete('unit_rigsImgById/{id}', 'Unit_rigsImgController@destroyById');
    Route::delete('supplierImgById/{id}', 'Supplier_imgController@destroyById');
    Route::resource('request', 'RequestController');
    Route::resource('service', 'Servicescontroller');
    Route::resource('serviceImg', 'Service_imgController');
    Route::resource('equipment', 'EquipmentController');
    Route::resource('equipmentImg', 'Equipment_imageController');
    Route::resource('supplier', 'SupplierController');
    Route::resource('supplierImg', 'Supplier_imgController');
    Route::resource('unit_rig', 'Unit_rigsController');
    Route::resource('unit_rigsImg', 'Unit_rigsImgController');
    Route::delete('serviceImgById/{id}', 'Service_imgController@destroyById');
    Route::delete('equipmentImgById/{id}', 'Equipment_imageController@destroyById');
    Route::resource('ads', 'AdsController');
    Route::get('adsshowbyuserid','AdsController@adsshowbyuserid');
    Route::post('SinsertBrochure', 'Servicescontroller@insertBrochure');
    Route::delete('SdeleteBrochure/{id}', 'Servicescontroller@deleteBrochure');
    Route::post('EinsertBrochure', 'EquipmentController@insertBrochure');
    Route::delete('EdeleteBrochure/{id}', 'EquipmentController@deleteBrochure');
    Route::post('SuinsertBrochure', 'SupplierController@insertBrochure');
    Route::delete('SudeleteBrochure/{id}', 'SupplierController@deleteBrochure');
    Route::post('UinsertBrochure', 'Unit_rigsController@insertBrochure');
    Route::delete('UdeleteBrochure/{id}', 'Unit_rigsController@deleteBrochure');
    Route::get('companyfollow','CompanyController@companyfollow');
   
    Route::get('showalluser','UserController@show');
});

Route::get('commentbypostid/{id}','CommentController@showbypostid');
Route::get('posts','PostController@index');


Route::get('users/{id}','UserController@getUser');
Route::get('comments', 'CommentController@index');
Route::get('replies', 'ReplyController@index');
Route::get('categorybyid/{id}','Categorycontroller@showbyID');
Route::delete('categorydeletebyid/{id}','Categorycontroller@deleteByID');
Route::get('categories','Categorycontroller@index');
Route::post('addcategory','Categorycontroller@enpSave');
Route::get('subcategories','Subcategorycontroller@index');
Route::post('addsubcategory','Subcategorycontroller@enpSave');
Route::get('subcategorydeletebyid','Subcategorycontroller@deleteByID');
Route::get('subcategories/{id}','Subcategorycontroller@show');
Route::post('search','SearchController@search');

Route::get('services', 'Servicescontroller@index');
Route::get('servicedesc', 'Servicescontroller@servicedesc');
Route::get('serviceasc', 'Servicescontroller@serviceasc');
Route::get('Scompanydesc', 'ServicesController@companydesc');
Route::get('Scompanyasc', 'ServicesController@companyasc');
Route::get('equipmentdesc', 'EquipmentController@equipmentdesc');
Route::get('equipmentasc', 'EquipmentController@equipmentasc');
Route::get('Ecompanydesc', 'EquipmentController@companydesc');
Route::get('Ecompanyasc', 'EquipmentController@companyasc');
Route::get('supplierdesc', 'SupplierController@Supplierdesc');
Route::get('supplierasc', 'SupplierController@Supplierasc');
Route::get('Sucompanydesc', 'SupplierController@companydesc');
Route::get('Sucompanyasc', 'SupplierController@companyasc');
Route::get('Unit_rigsdesc', 'Unit_rigsController@Unit_rigsdesc');
Route::get('Unit_rigsasc', 'Unit_rigsController@Unit_rigsasc');
Route::get('Ucompanydesc', 'Unit_rigsController@companydesc');
Route::get('Ucompanyasc', 'Unit_rigsController@companyasc');

Route::get('subcategorydesc/{id}', 'Subcategorycontroller@subcategorydesc');
Route::get('subcategoryasc/{id}', 'Subcategorycontroller@subcategoryasc');
Route::post('subcategorysearch/{id}', 'Subcategorycontroller@search');
Route::get('ads','AdsController@index');
Route::get('suppliers', 'SupplierController@index');
Route::get('unit_rigs', 'Unit_rigsController@index');
Route::get('equipments', 'EquipmentController@index');
Route::get('companyindex','CompanyController@index');
Route::get('companybyid/{id}','CompanyController@show');
Route::get('whitepaper','WhitepaperController@index');
Route::get('serviceshowbyid/{id}', 'Servicescontroller@showbyid');
Route::get('serviceshowbysubcategoryid/{id}', 'Servicescontroller@showbysubcategoryid');
Route::get('suppliershowbyid/{id}', 'SupplierController@showbyid');
Route::get('suppliershowbysubcategoryid/{id}', 'SupplierController@showbysubcategoryid');
Route::get('equipmentshowbyid/{id}', 'EquipmentController@showbyid');
Route::get('equipmentshowbysubcategoryid/{id}', 'EquipmentController@showbysubcategoryid');
Route::get('unit_rigshowbyid/{id}', 'Unit_rigsController@showbyid');
Route::get('unit_rigshowbysubcategoryid/{id}', 'Unit_rigsController@showbysubcategoryid');
Route::get('servicedetails/{category}/{subcategory}/{id}', 'Servicescontroller@showdetails');
Route::get('unit_rigsdetails/{category}/{subcategory}/{id}', 'Unit_rigsController@showdetails');
Route::get('equipmentdetails/{category}/{subcategory}/{id}', 'Equipmentcontroller@showdetails');
Route::get('supplierdetails/{category}/{subcategory}/{id}', 'SupplierController@showdetails');
Route::post('servicesearch','SearchController@Servicesearch');
Route::post('equipmentsearch','SearchController@Equipmentsearch');
Route::post('suppliersearch','SearchController@Suppliersearch');
Route::post('unit_rigsearch','SearchController@Unit_rigsearch');
Route::post('blogsearch','SearchController@blogsearch');
Route::get('showbycompanyid/{id}','FollowController@showbycompanyid');
Route::delete('deletebycompanyid/{id}','FollowController@deletebycompanyid');
Route::get('profilepic', 'profileimgController@showprofile');
Route::get('downloadpdf/{path}','CompanyController@download');
Route::post('cdownloadpdf','CompanyController@download');
Route::get('countryflag', 'CountryFlag@countrycode');
Route::resource('cBrochure', 'CompanyBroucherController');
Route::resource('companyImg', 'CompanyImgController');
Route::resource('PostComment', 'PostCommentReplyController');
Route::get('showbyuserid/{id}', 'PostCommentReplyController@showby');

Route::get('edushowbyid/{id}','EducationController@edushowbyid');
Route::get('aboutshowbyid/{id}','AboutController@aboutshowbyid');
Route::get('experienceshowbyid/{id}','ExperienceController@experienceshowbyid');
Route::get('profileshowbyid/{id}','ProfileController@profileshowbyid');
Route::get('covershowbyid/{id}','CoverphotoController@covershowbyid');