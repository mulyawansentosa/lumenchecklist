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

    public function testUser_can_register_to_application()
    {
        try{
            $faker              = Faker\Factory::create();
            $name               = $faker->name;
            $email              = $faker->email;
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
            $status             = $this->call('POST','/register',$testItem);
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
            $email              = $faker->email;
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
            $status             = $this->call('POST','/login',$testItem);
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
            $email              = $faker->email;
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
            $status             = $this->call('POST','/login',$testItem);
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
            $email              = $faker->email;
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
            $status             = (array)$this->get('/logout', ['HTTP_Authorization' => 'bearer '.$token]);
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
            $email              = $faker->email;
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
            $status             = (array)$this->get('/user/'.$user->id, ['HTTP_Authorization' => 'bearer '.$token]);
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
                throw new Exception('Responses: Error on Sending Header Data');
            }                
        }catch(\Exception $e){
            $this->expectException($e->getMessage());
        }
    }    
}
