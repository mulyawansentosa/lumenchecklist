<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;

class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth',['except' => ['register','login']]);
    }

    public function login(Request $request){
        $email      = $request->input('email');
        $password   = $request->input('password');

        $user   = User::where('email',$email)->first();
        if(Hash::check($password, $user->password)){
            $api_token = base64_encode(str_random(40));
            $user->update(
                [
                    'api_token' => $api_token
                ]
            );

            return response()->json(
                [
                    'success'   => true,
                    'message'   => 'Berhasil',
                    'data'      => [
                        'user' => $user,
                        'api_token' => $api_token
                    ]
                    ],201
            );
        }else{
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Gagal',
                    'data'      => null
                ],400
            );
        }
    }

    public function logout(){

    }
    
    public function register(Request $request){
        $name       = $request->input('name');
        $email      = $request->input('email');
        $password   = Hash::make($request->input('password'));

        $register = User::create(
            [
                'name'      => $name,
                'email'     => $email,
                'password'  => $password
            ]
        );

        if($register){
            return response()->json(
                [
                    'success'   => true,
                    'message'   => 'Registration Success',
                    'data'      => $register
                ]
            );
        }else{
            return response()->json(
                [
                    'success'   => false,
                    'message'   => 'Registration Error',
                    'data'      => null
                ],400
            );
        }
    }

    public function show($id = null){
        if(is_null($id)){
            $user = User::all();
            return response()->json(
                [
                    'success'   => false,
                    'message'   => 'Data Tidak Ditemukan',
                    'data'      => $user
                ]
            );
        }else{
            $user   = User::find($id);
            return response()->json(
                [
                    'success'   => true,
                    'message'   => 'Data Ditemukan',
                    'data'      => $user
                ]
            );
        }
    }
}
