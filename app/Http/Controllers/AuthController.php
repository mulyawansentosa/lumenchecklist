<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use Validator;

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
        try{

            $validator = Validator::make($request->all(), [
                'email'             => 'required|email|max:100',
                'password'          => 'required|max:255'
            ]);
    
            if ($validator->fails()) {
                return response()->json(
                    [
                        'success'   => false,
                        'code'      => 200,
                        'message'   => $validator->messages()->toJson(),
                        'data'      => []
                    ], 200
                );
            }else{
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
                            'code'      =>  200,
                            'message'   => 'Login Success',
                            'data'      => [
                                'user' => $user
                            ]
                            ],200
                    );
                }else{
                    return response()->json(
                        [
                            'success'   => false,
                            'code'      => 200,
                            'message'   => 'Login Failed',
                            'data'      => []
                        ], 200
                    );
                }        
            }                
        }catch(\Exception $e){
            return response()->json(
                [
                    'success'   => false,
                    'code'      => 500,
                    'message'   => $e->getMessage(),
                    'data'      => []
                ], 500
            );
        }
    }

    public function logout(Request $request){
        try{
            $data                   = $request->header();
            $auth                   = $data['authorization'];
            $user                   = User::where('api_token',str_replace('bearer ','',$auth[0]));
            if($user){
                $user->update(
                    [
                        'api_token' => ''
                    ]
                );
    
                return response()->json(
                    [
                        'success'   => true,
                        'code'      => 200,
                        'message'   => 'Logout Success',
                        'data'      => []
                    ], 200
                );
            }else{
                return response()->json(
                    [
                        'success'   => false,
                        'code'      => 200,
                        'message'   => 'You are already logout',
                        'data'      => []
                    ], 200
                );
            }    
        }catch(\Exception $e){
            return response()->json(
                [
                    'success'   => false,
                    'code'      => 500,
                    'message'   => $e->getMessage(),
                    'data'      => []
                ], 500
            );
        }
    }
    
    public function register(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'name'      => 'required|max:255',
                'email'     => 'required|email|max:100|unique:users',
                'password'  => 'required'
            ]);
    
            if ($validator->fails()) {
                return response()->json(
                    [
                        'success'   => false,
                        'code'      => 200,
                        'message'   => $validator->messages()->toJson(),
                        'data'      => []
                    ], 200
                );
            }else{
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
                            'code'      => 200,
                            'message'   => 'Registration Success',
                            'data'      => $register
                        ], 200
                    );
                }else{
                    return response()->json(
                        [
                            'success'   => false,
                            'code'      => 200,
                            'message'   => 'Registration Error',
                            'data'      => null
                        ], 200
                    );
                }            
            }    
        }catch(\Exception $e){
            return response()->json(
                [
                    'success'   => false,
                    'code'      => 500,
                    'message'   => $e->getMessage(),
                    'data'      => []
                ], 500
            );
        }
    }

    public function show(Request $request, $id){
        try{
            $data                   = $request->header();
            $auth                   = $data['authorization'];
            $user                   = User::where('api_token',str_replace('bearer ','',$auth[0]))->where('id',$id);
            if($user){
                $user               = User::find($id);
                return response()->json(
                    [
                        'success'   => true,
                        'code'      => 200,
                        'message'   => 'Data found',
                        'data'      => $user
                    ], 200
                );    
            }else{
                return response()->json(
                    [
                        'success'   => false,
                        'code'      => 200,
                        'message'   => 'Data not found',
                        'data'      => []
                    ], 200
                );    
            }
        }catch(\Exception $e){
            return response()->json(
                [
                    'success'   => false,
                    'code'      => 500,
                    'message'   => $e->getMessage(),
                    'data'      => []
                ], 500
            );
        }
    }

    public function destroy(Request $request, $id)
    {
        try{
            if(is_null($id)){
                return response()->json(
                    [
                        'success'   => false,
                        'code'      => 200,
                        'message'   => 'Parameter is nout found'
                    ]
                );
            }else{
                $check      = User::find($id);
                if($check){
                    $user = User::destroy($id);
                    if($user){
                        return response()->json(
                            [
                                'success'   => true,
                                'code'      => 200,
                                'message'   => 'Data has been deleted',
                                'data'      => []
                            ], 200
                        );    
                    }else{
                        return response()->json(
                            [
                                'success'   => false,
                                'code'      => 200,
                                'message'   => 'Error deleting data',
                                'data'      => []
                            ], 200
                        );    
                    }
                }else{
                    return response()->json(
                        [
                            'success'   => false,
                            'code'      => 200,
                            'message'   => 'Data is not found',
                            'data'      => []
                        ], 200
                    );
                }
            }
        }catch(\Exception $e){
            return response()->json(
                [
                    'success'   => false,
                    'code'      => 500,
                    'message'   => $e->getMessage(),
                    'data'      => []
                ], 500
            );
        }
    }    
}
