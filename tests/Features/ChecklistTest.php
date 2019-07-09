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
use App\Helper\Helper;

class ChecklistTest extends TestCase
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

    public function testGet_checklist()
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

            //Checking template id wheater exist or not
            $count_item             = Template::count();
            if($count_item>0){
                $template_id        = Template::get()->last()->id+1;
            }else{
                $template_id        = 1;
            }

            //Checking checklist id wheater exist or not
            $count_item             = Checklist::count();
            if($count_item>0){
                $checklist_id       = Checklist::get()->last()->id+1;
            }else{
                $checklist_id       = 1;
            }

            //Checking item id wheater exist or not
            $count_item             = Item::count();
            if($count_item>0){
                $item_id            = Item::get()->last()->id+1;
            }else{
                $item_id            = 1;
            }
            $is_completed           = $faker->boolean;

            //Creating template data
            factory(Template::class)->create(
                [
                    'id'    => $template_id
                ]
            );

            //Creating checklist data
            factory(Checklist::class)->create(
                [
                    'id'            => $checklist_id,
                    'template_id'   => $template_id
                ]
            );

            //Creating item data
            factory(Item::class)->create(
                [
                    'id'            => $item_id,
                    'checklist_id'  => $checklist_id
                ]
            );

            //Getting item data that has been created
            $data               = new GetChecklistResource(Checklist::where('id',$checklist_id)->first());
            $testCase           = json_encode($data);

            // //Parameter sent to server
            // $testParam          = [
            //                         'item_id'      => $items[0]['attributes']['item_id']
            //                     ];
            //Processing data to server
            $status             = (array) $this->call(
                                'GET',
                                '/checklists/'.$checklist_id,
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
            $result             = $this->response->getContent();
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
            Template::destroy($template_id);
        }catch(\Exception $e){
            $this->expectException($e->getMessage());
        }
    }

    public function testUpdate_checklist()
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

            //Checking template id wheater exist or not
            $count_item             = Template::count();
            if($count_item>0){
                $template_id        = Template::get()->last()->id+1;
            }else{
                $template_id        = 1;
            }

            //Checking checklist id wheater exist or not
            $count_item             = Checklist::count();
            if($count_item>0){
                $checklist_id       = Checklist::get()->last()->id+1;
            }else{
                $checklist_id       = 1;
            }

            //Checking item id wheater exist or not
            $count_item             = Item::count();
            if($count_item>0){
                $item_id            = Item::get()->last()->id+1;
            }else{
                $item_id            = 1;
            }
            $is_completed           = $faker->boolean;

            //Creating template data
            factory(Template::class)->create(
                [
                    'id'    => $template_id
                ]
            );

            //Creating checklist data
            factory(Checklist::class)->create(
                [
                    'id'            => $checklist_id,
                    'template_id'   => $template_id
                ]
            );

            //Creating item data
            factory(Item::class)->create(
                [
                    'id'            => $item_id,
                    'checklist_id'  => $checklist_id
                ]
            );

            //Getting item data that has been created
            $data               = new GetChecklistResource(Checklist::where('id',$checklist_id)->first());
            $testCase           = json_encode($data);

            //Parameter sent to server
            $testParam          = [
                                    "type"      => "checklists",
                                    "id"        => $checklist_id,
                                    "attributes"    => [
                                        "object_domain"     => $faker->jobTitle,
                                        "object_id"         => $faker->randomDigit,
                                        "description"       => $faker->sentence($nbWords = 6, $variableNbWords = true),
                                        "is_completed"      => $faker->boolean,
                                        "completed_at"      => $faker->date('Y-m-d H:i:s'),
                                        "created_at"        => $faker->date('Y-m-d H:i:s')
                                    ],
                                    "links"     => [
                                        'self' => route('updatechecklist',['checklistId' => $checklist_id])
                                        ]
                                ];

            //Processing data to server
            $status             = (array) $this->call(
                                'GET',
                                '/checklists/'.$checklist_id,
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
            $result             = $this->response->getContent();
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
            Template::destroy($template_id);
        }catch(\Exception $e){
            $this->expectException($e->getMessage());
        }
    }

    public function testDelete_checklist()
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

            //Checking template id wheater exist or not
            $count_item             = Template::count();
            if($count_item>0){
                $template_id        = Template::get()->last()->id+1;
            }else{
                $template_id        = 1;
            }

            //Checking checklist id wheater exist or not
            $count_item             = Checklist::count();
            if($count_item>0){
                $checklist_id       = Checklist::get()->last()->id+1;
            }else{
                $checklist_id       = 1;
            }

            //Checking item id wheater exist or not
            $count_item             = Item::count();
            if($count_item>0){
                $item_id            = Item::get()->last()->id+1;
            }else{
                $item_id            = 1;
            }
            $is_completed           = $faker->boolean;

            //Creating template data
            factory(Template::class)->create(
                [
                    'id'    => $template_id
                ]
            );

            //Creating checklist data
            factory(Checklist::class)->create(
                [
                    'id'            => $checklist_id,
                    'template_id'   => $template_id
                ]
            );

            //Creating item data
            factory(Item::class)->create(
                [
                    'id'            => $item_id,
                    'checklist_id'  => $checklist_id
                ]
            );

            //Getting item data that has been created
            // $data               = new GetChecklistResource(Checklist::where('id',$checklist_id)->first());
            $data               = [
                                    "status"    => 201,
                                    "action"    => "success"
                                ];
            $testCase           = json_encode($data);

            //Processing data to server
            $status             = (array) $this->call(
                                'DELETE',
                                '/checklists/'.$checklist_id,
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
            $result             = $this->response->getContent();
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
            Template::destroy($template_id);
        }catch(\Exception $e){
            $this->expectException($e->getMessage());
        }
    }    

    public function testCreate_checklist()
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

            //Checking template id wheater exist or not
            $count_item             = Template::count();
            if($count_item>0){
                $template_id        = Template::get()->last()->id+1;
            }else{
                $template_id        = 1;
            }

            //Checking checklist id wheater exist or not
            $count_item             = Checklist::count();
            if($count_item>0){
                $checklist_id       = Checklist::get()->last()->id+1;
            }else{
                $checklist_id       = 1;
            }

            //Creating template data
            factory(Template::class)->create(
                [
                    'id'    => $template_id
                ]
            );

            //Parameter sent to server
            $testParam          = [
                                    "attributes"    => [
                                        "object_domain" => $faker->jobTitle,
                                        "object_id"     => $faker->randomDigit,
                                        "due"           => $faker->date('Y-m-d H:i:s'),
                                        "urgency"       => $faker->randomDigit,
                                        "description"   => $faker->sentence($nbWords = 6, $variableNbWords = true),
                                        "items"         => [
                                            $faker->sentence($nbWords = 6, $variableNbWords = true),
                                            $faker->sentence($nbWords = 6, $variableNbWords = true),
                                            $faker->sentence($nbWords = 6, $variableNbWords = true)
                                        ],
                                        "task_id"       => $faker->randomDigit
                                    ]
                                ];

            //Processing data to server
            $status             = (array) $this->call(
                                'POST',
                                '/checklists',
                                [],
                                [],
                                [],
                                $headers = [
                                    'HTTP_Authorization' => 'bearer '.$token,
                                    'CONTENT_TYPE' => 'application/json',
                                    'HTTP_ACCEPT' => 'application/json'
                                ],
                                $json = json_encode(['data' => $testParam])
                                );
                                //call($method, $uri, $parameters = [], $cookies = [], $files = [], $server = [], $content = null)
            //Getting item data that has been created
            $data               = new GetChecklistResource(Checklist::all()->last());
            $testCase           = json_encode($data);
            //Converting data from json to Array
            $result             = $this->response->getContent();
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
            Template::destroy($template_id);
        }catch(\Exception $e){
            $this->expectException($e->getMessage());
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
            $data               = new GetListofChecklistCollection(Checklist::paginate());
            $testCase           = json_encode($data);
            // //Parameter sent to server
            // $testParam          = [
            //                         'item_id'      => $items[0]['attributes']['item_id']
            //                     ];
            //Processing data to server
            $status             = (array) $this->call(
                                'GET',
                                '/checklists',
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