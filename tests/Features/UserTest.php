<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Controllers\AuthController;
use App\User;
use Illuminate\Support\Facades\Hash;

class UserTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function cekEmail($email){
        $faker              = Faker\Factory::create();
        if(count(App\User::where('email',$email)->first())>0){
            $this->cekEmail($email);            
        }{
            $email          = $faker->email;
            return $email;
        }
    }

    public function testUser_can_register_to_application()
    {
        try{
            $faker              = Faker\Factory::create();
            $name               = $faker->name;
            $email              = $this->cekEmail($faker->email);
            $password           = $faker->password;
            $testItem           = array(
                                    'name'      => $name,
                                    'email'     => $email,
                                    'password'  => $password
                                );
            $testCase           = array (
                                'success'   => true,
                                'code'      => 200,
                                'message'   => 'Registration Success',
                                'data'      => array(
                                                'name'      => $name,
                                                'email'     => $email
                                            )
                                );
            $status             = $this->call(
                                'POST',
                                '/register',
                                $testItem,
                                [],
                                [],
                                [],
                                []);
            // call($method, $uri, $parameters = [], $cookies = [], $files = [], $server = [], $content = null);
            $data               = (array)json_decode($this->response->getContent(),true);
            if(sizeof($data)>0 AND $data['success'] == true){
                $result             = array(
                    'success'   => $data['success'],
                    'code'      => $data['code'],
                    'message'   => $data['message'],
                    'data'      => array(
                                    'name'      => $data['data']['name'],
                                    'email'     => $data['data']['email']
                                )
                    );
                App\User::destroy($data['data']['id']);
                $this->assertArraySubset(
                    $testCase, $result
                );
            }else{
                throw new Exception('Responses: '.$status);
            }                
        }catch(\Exception $e){
            $this->expectException($e->getMessage());
        }
    }
    public function testUser_can_login_with_credential()
    {
        try{
            $faker              = Faker\Factory::create();
            $name               = $faker->name;
            $email              = $this->cekEmail($faker->email);
            $password           = $faker->password;
            $user               = App\User::create(
                                    [
                                        'name'      => $name,
                                        'email'     => $email,
                                        'password'  => Hash::make($password)
                                    ]
                                );                    
            $testItem           = array(
                                    'email'     => $email,
                                    'password'  => $password
                                );
            $testCase           = array (
                                'success'   => true,
                                'code'      => 200,
                                'message'   => 'Login Success'
                                );
            $status             = $this->call(
                                'POST',
                                '/login',
                                $testItem,
                                [],
                                [],
                                [],
                                []);
            // call($method, $uri, $parameters = [], $cookies = [], $files = [], $server = [], $content = null);
            $data               = (array)json_decode($this->response->getContent(),true);
            if(sizeof($data)>0 AND $data['success'] == true){
                $result             = array(
                                    'success'   => $data['success'],
                                    'code'      => $data['code'],
                                    'message'   => $data['message']
                                    );
                App\User::destroy($user->id);                    
                $this->assertArraySubset(
                    $testCase, $result
                );    
            }else{
                throw new Exception('Responses: '.$status);
            }                
        }catch(\Exception $e){
            $this->expectException($e->getMessage());
        }
    }

    public function testUser_cannot_login_without_credential()
    {
        try{
            $faker              = Faker\Factory::create();
            $name               = $faker->name;
            $email              = $this->cekEmail($faker->email);
            $password           = $faker->password;
            $user               = App\User::create(
                                    [
                                        'name'      => $name,
                                        'email'     => $email,
                                        'password'  => Hash::make($password)
                                    ]
                                );
            $testItem           = array(
                                    'email'     => $email,
                                    'password'  => str_random(10)
                                );
            $testCase           = array (
                                'success'   => false,
                                'code'      => 200,
                                'message'   => 'Login Failed'
                                );
            $status             = $this->call(
                                'POST',
                                '/login',
                                $testItem,
                                [],
                                [],
                                [],
                                []);
            // call($method, $uri, $parameters = [], $cookies = [], $files = [], $server = [], $content = null);
            $data               = (array)json_decode($this->response->getContent(),true);
            if(sizeof($data)>0 AND $data['success'] == false){
                $result             = array(
                                    'success'   => $data['success'],
                                    'code'      => $data['code'],
                                    'message'   => $data['message']
                                    );
                App\User::destroy($user->id);                    
                $this->assertArraySubset(
                    $testCase, $result
                );
            }else{
                throw new Exception('Responses: '.$status);
            }                
        }catch(\Exception $e){
            $this->expectException($e->getMessage());
        }
    }

    public function testUser_can_logout_with_credential(){
        try{
            $faker              = Faker\Factory::create();
            $name               = $faker->name;
            $email              = $this->cekEmail($faker->email);
            $password           = $faker->password;
            $token              = base64_encode(str_random(40));
            $user               = App\User::create(
                                    [
                                        'name'      => $name,
                                        'email'     => $email,
                                        'password'  => Hash::make($password),
                                        'api_token' => $token
                                    ]
                                );
            $testCase           = array (
                                'success'   => true,
                                'code'      => 200,
                                'message'   => 'Logout Success'
                                );
            $status             = $this->call(
                                'GET',
                                '/logout',
                                [],
                                [],
                                [],
                                $headers = [
                                    'HTTP_Authorization' => 'bearer '.$token,
                                    'CONTENT_TYPE' => 'application/json',
                                    'HTTP_ACCEPT' => 'application/json'
                                ]
                                );
            // call($method, $uri, $parameters = [], $cookies = [], $files = [], $server = [], $content = null);
            $data               = (array)json_decode($this->response->getContent(),true);
            if(sizeof($data)>0 AND $data['success'] == true){
                $result             = array(
                                    'success'   => $data['success'],
                                    'code'      => $data['code'],
                                    'message'   => $data['message']
                                    );
                App\User::destroy($user->id);                    
                $this->assertArraySubset(
                    $testCase, $result
                );                            
            }else{
                throw new Exception('Responses: '.$status);
            }
        }catch(\Exception $e){
            $this->expectException($e->getMessage());
        }
    }

    public function testUser_can_show_detail(){
        try{
            $faker              = Faker\Factory::create();
            $name               = $faker->name;
            $email              = $this->cekEmail($faker->email);
            $password           = $faker->password;
            $token              = base64_encode(str_random(40));
            $user               = App\User::create(
                                    [
                                        'name'      => $name,
                                        'email'     => $email,
                                        'password'  => Hash::make($password),
                                        'api_token' => $token
                                    ]
                                );
            $testCase           = array (
                                'success'   => true,
                                'code'      => 200,
                                'message'   => 'Data found',
                                'data'      => array(
                                                'name'      => $name,
                                                'email'     => $email
                                            )
                                );
            $status             = $this->call(
                                'GET',
                                '/user/'.$user->id,
                                [],
                                [],
                                [],
                                $headers = [
                                    'HTTP_Authorization' => 'bearer '.$token,
                                    'CONTENT_TYPE' => 'application/json',
                                    'HTTP_ACCEPT' => 'application/json'
                                ]
                                );
            // call($method, $uri, $parameters = [], $cookies = [], $files = [], $server = [], $content = null);
            $data               = (array)json_decode($this->response->getContent(),true);
            if(sizeof($data)>0 AND $data['success'] == true){
                $result             = array(
                                    'success'   => $data['success'],
                                    'code'      => $data['code'],
                                    'message'   => $data['message'],
                                    'data'      => array(
                                                'name'      => $data['data']['name'],
                                                'email'     => $data['data']['email']
                                                )
                                );
                App\User::destroy($user->id);                    
                $this->assertArraySubset(
                    $testCase, $result
                );    
            }else{
                throw new Exception('Responses: '.$status);
            }                
        }catch(\Exception $e){
            $this->expectException($e->getMessage());
        }
    }    
    public function testUser_can_delete_user(){
        try{
            $faker              = Faker\Factory::create();
            $name               = $faker->name;
            $email              = $this->cekEmail($faker->email);
            $password           = $faker->password;
            $token              = base64_encode(str_random(40));
            $user               = App\User::create(
                                    [
                                        'name'      => $name,
                                        'email'     => $email,
                                        'password'  => Hash::make($password),
                                        'api_token' => $token
                                    ]
                                );
            $testCase           = array(
                                    'success'   => true,
                                    'code'      => 200,
                                    'message'   => 'Data has been deleted',
                                    'data'      => array()
                                );
            $status             = $this->call(
                                'DELETE',
                                '/user/'.$user->id,
                                [],
                                [],
                                [],
                                $headers = [
                                    'HTTP_Authorization' => 'bearer '.$token,
                                    'CONTENT_TYPE' => 'application/json',
                                    'HTTP_ACCEPT' => 'application/json'
                                ]
                                );
            // call($method, $uri, $parameters = [], $cookies = [], $files = [], $server = [], $content = null);
            $data               = (array)json_decode($this->response->getContent(),true);
            if(sizeof($data)>0 AND $data['success'] == true){
                $result             = array(
                                    'success'   => $data['success'],
                                    'code'      => $data['code'],
                                    'message'   => $data['message'],
                                    'data'      => array()
                                );
                App\User::destroy($user->id);                    
                $this->assertArraySubset(
                    $testCase, $result
                );    
            }else{
                throw new Exception('Responses: '.$status);
            }
        }catch(\Exception $e){
            $this->expectException($e->getMessage());
        }
    }
    public function testCreate_user()
    {
        try{
            $faker              = Faker\Factory::create();
            $name               = $faker->name;
            $email              = $this->cekEmail($faker->email);
            $password           = $faker->password;
            $testItem           = array(
                                    'name'      => $name,
                                    'email'     => $email,
                                    'password'  => $password
                                );
            $testCase           = array(
                                'success'   => true,
                                'code'      => 200,
                                'message'   => 'Registration Success',
                                'data'      => array(
                                                'name'      => $name,
                                                'email'     => $email
                                            )
                                );
            $status             = $this->call(
                                'POST',
                                '/register',
                                $testItem,
                                [],
                                [],
                                [],
                                []);
            // call($method, $uri, $parameters = [], $cookies = [], $files = [], $server = [], $content = null);
            $data               = (array)json_decode($this->response->getContent(),true);
            if(sizeof($data)>0 AND $data['success'] == true){
                $result             = array(
                    'success'   => $data['success'],
                    'code'      => $data['code'],
                    'message'   => $data['message'],
                    'data'      => array(
                                    'name'      => $data['data']['name'],
                                    'email'     => $data['data']['email']
                                )
                    );
                App\User::destroy($data['data']['id']);
                $this->assertArraySubset(
                    $testCase, $result
                );
            }else{
                throw new Exception('Responses: '.$status);
            }                
        }catch(\Exception $e){
            $this->expectException($e->getMessage());
        }
    }    
}
