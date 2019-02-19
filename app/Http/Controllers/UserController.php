<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
   /**
    * Create a new user
    *
    * @param Request $request
    * @return \Illuminate\Http\JsonResponse
    */
   public function create(Request $request)
   {
       $user = User::create([
           "name" => $request->get("name"),
           "email" => $request->get("email")
       ]);

       return response()->json(["message" => "The user with id {$user->id} has been successfully created." ], 201);
   }
}
 