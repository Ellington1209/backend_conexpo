<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\User;
use Validator;

class ApiController extends Controller
{
    use RegistersUsers;

    public function index (Request $Request)
    {
        $rules = [
            'name'        =>['required', 'string' , 'max:255'],
            'email'       =>['required', 'string', 'email', 'max:255', 'unique:user'],
            'password'    =>['required', 'string', 'min:6'],
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){
            return response()->json(['error'=> $validator->message()], 200);
        }else{
            $user = User::create([
                'name'       =>$request->name,
                'email'      =>$request->email,
                'password'   => Hash::make($request->password),
            ]);

            if(!empty($user)){
                return response()->json([
                    'name'     => $user->name,
                    'email'    => $user->email,
                    'password' => $user->createToken('Token Name')->accessToken
                ], 200);
            }
        }
    }
}
