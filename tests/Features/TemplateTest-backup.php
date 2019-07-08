<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Controllers\AuthController;
use App\User;
use Illuminate\Http\Request;
use App\Template;
use App\Checklist;
use App\Item;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\Checklist\GetChecklistResource;
use App\Http\Resources\Checklist\GetListofChecklistCollection;
use App\Http\Resources\Template\ListAllChecklistTemplateCollection;

class TemplateTest extends TestCase
{

    public function cekEmail($email){
        $faker              = Faker\Factory::create();
        if(count(App\User::where('email',$email)->first())>0){
            $this->cekEmail($email);            
        }{
            $email          = $faker->email;
            return $email;
        }
    }

    public function testGet_list_of_checklist()
    {
        try{
            //Generate User for Login
            $users              = factory(App\User::class, 10)->create();
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

            //Getting item data that has been created
            $data               = new ListAllChecklistTemplateCollection(Template::with('checklist')->paginate());
            $testCase           = json_encode($data);
            // //Parameter sent to server
            // $testParam          = [
            //                         'item_id'      => $items[0]['attributes']['item_id']
            //                     ];
            //Processing data to server
            $status             = (array) $this->call(
                                'GET',
                                '/checklists/templates',
                                [],
                                [],
                                [],
                                $headers = [
                                    'HTTP_Authorization' => 'bearer '.$token,
                                    'CONTENT_TYPE' => 'application/json',
                                    'HTTP_ACCEPT' => 'application/json'
                                ]
                                // $json = json_encode(['data' => $testParam])
                                );
                                //call($method, $uri, $parameters = [], $cookies = [], $files = [], $server = [], $content = null)
            
            //Converting data from json to Array
            $result             = json_decode($this->response->getContent(),true);
            unset($result['links'],$result['meta']);
            $result             = json_encode($result, true);
            $helper             = new Helper;
            if($helper->isJson($result)){
                // Testing
                $this->assertJsonStringEqualsJsonString(
                    $testCase, $result
                );
            }else{
                throw new Exception('Responses: '.$status);
            }

            // Deleting Testing DAta
            App\User::destroy($user->id);   
        }catch(\Exception $e){
            $this->expectException($e->getMessage());
        }
    }

    public function testGet_checklist_template()
    {
        try{
            //Generate User for Login
            $users              = factory(App\User::class, 10)->create();
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
            $templateId         = Template::first()->id;
            //Getting item data that has been created
            $data               = new ListAllChecklistTemplateCollection(Template::where('id',$templateId)->with('checklist')->paginate());
            $testCase           = json_encode($data);
            // //Parameter sent to server
            // $testParam          = [
            //                         'item_id'      => $items[0]['attributes']['item_id']
            //                     ];
            //Processing data to server
            $status             = (array) $this->call(
                                'GET',
                                '/checklists/templates/'.$templateId,
                                [],
                                [],
                                [],
                                $headers = [
                                    'HTTP_Authorization' => 'bearer '.$token,
                                    'CONTENT_TYPE' => 'application/json',
                                    'HTTP_ACCEPT' => 'application/json'
                                ]
                                // $json = json_encode(['data' => $testParam])
                                );
                                //call($method, $uri, $parameters = [], $cookies = [], $files = [], $server = [], $content = null)
            
            //Converting data from json to Array
            $result             = json_decode($this->response->getContent(),true);
            unset($result['links'],$result['meta']);
            $result             = json_encode($result, true);
            $helper             = new Helper;
            if($helper->isJson($result)){
                // Testing
                $this->assertJsonStringEqualsJsonString(
                    $testCase, $result
                );
            }else{
                throw new Exception('Responses: '.$status);
            }

            // Deleting Testing DAta
            App\User::destroy($user->id);   
        }catch(\Exception $e){
            $this->expectException($e->getMessage());
        }
    }    
}