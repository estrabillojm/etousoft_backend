<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{

   public function deleteUser($id){
      $user = User::destroy($id);
      return response($user, Response::HTTP_NO_CONTENT);

   }



   public function singleUser($id){
      $user = User::select('*')
             ->where('id','=', $id)
             ->first();

      return response($user, Response::HTTP_OK);
   }

   public function paginateUser(Request $request){
      $users = User::orderBy('created_at','desc')->paginate(10);
      return response($users, Response::HTTP_OK);
   }

   public function allUser(){
      $admin = User::orderBy('created_at','desc')
      ->select("*")
      ->where('role', 'admin')->get();

      $user = User::orderBy('created_at','desc')
      ->select("*")
      ->where('role', 'user')->get();

      $count = [
         'admin' => count($admin),
         'user' => count($user)
      ];



      return response($count, Response::HTTP_OK);
   }
   
   public function login(Request $request){
      if(Auth::attempt($request->only('username', 'password'))){
         $user = Auth::user();
         $user= User::where('username', $request->username)->first();
         $token = $user->createToken('my-app-token')->plainTextToken;
         $response = [
             'token' => $token,
         ];
         $cookie = \cookie('sanctum', $token, 3600);
          return \response($response, 201)->withCookie($cookie);
      }
      return response ([
            'error' => 'Invalid Credentials',
      ], Response::HTTP_UNAUTHORIZED);
   }


   public function updateUser(Request $request, $id){
      $user = User::find($id);
      $user->update([
         'first_name' => $request->input('first_name'),
         'last_name' => $request->input('last_name'),
         'middle_name' => $request->input('middle_name'),
         'gender' => $request->input('gender'),
         'dob' => $request->input('dob'),
         'age' => $request->input('age'),
         'suffix' => $request->input('suffix'),
         'contact' => $request->input('contact'),
         'role' => $request->input('role'),
         'address' => $request->input('address'),
         'brgy' => $request->input('brgy'),
         'city_id' => $request->input('city_id'),
         'city' => $request->input('city'),
         'province_id' => $request->input('province_id'),
         'province' => $request->input('province'),
         'region_id' => $request->input('region_id'),
         'region' => $request->input('region'),
         'username' => $request->input('username')
      ]);

      return response($user, Response::HTTP_ACCEPTED);

      
   }

   public function register(Request $request){
      $user = User::create($request->only('first_name','last_name', 'middle_name','gender', 'dob','age', 'suffix' , 'contact', 'role', 'address','brgy','city_id','city','province_id' ,'province','region_id' ,'region' ,'username') + [
         'password' => Hash::make($request->input('password'))
      ]);
      return response($user, Response::HTTP_CREATED);
   }
   

   public function logout(){
      $cookie = \Illuminate\Support\Facades\Cookie::forget('sanctum');
      return \response([
            'message' => 'success'
      ])->withCookie($cookie);
   }
}
