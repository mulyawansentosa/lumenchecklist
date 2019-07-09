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
use App\Http\Resources\Checklist\ListofItemInGivenChecklistResource;
use App\Http\Resources\Checklist\GetChecklistItemResource;
use App\Http\Resources\Item\CreateChecklistItemResource;
use Carbon\Carbon;
use App\Helper\Helper;

class ItemTest extends TestCase
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

    public function testShow_complete_items()
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
            $items              = Item::where('id',$item_id)->get()->toArray();
            //Test case as right answer of test
            $testCase           = [
                                'data'      => [
                                            'id'            => $items[0]['id'],
                                            'item_id'       => $items[0]['id'],
                                            'is_completed'  => true,
                                            'checklist_id'  => $items[0]['checklist_id']
                                            ]
                                ];
            //Parameter sent to server
            $testParam          = [
                                    'item_id'      => $items[0]['id']
                                ];
            
            //Processing data to server
            $status             = (array) $this->call(
                                'POST',
                                '/checklists/complete',
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
            
            //Converting data from json to Array
            $data               = (array)json_decode($this->response->getContent(),true);
            if(sizeof($data)>0){
                $result             = array(
                                    'data'      => array(
                                                'id'            => $data['data'][0]['id'],
                                                'item_id'       => $data['data'][0]['item_id'],
                                                'is_completed'  => $data['data'][0]['is_completed'] == 1 ? true : false,
                                                'checklist_id'  => $data['data'][0]['checklist_id']
                                                )
                                    );
                //Testing
                $this->assertArraySubset(
                    $testCase, $result
                );

            }else{
                throw new Exception('Responses: '.$status);
            }                
            //Deleting Testing DAta                
            App\User::destroy($user->id);   
            Template::destroy($template_id);
        }catch(\Exception $e){
            $this->expectException($e->getMessage());
        }
    }

    public function testShow_incomplete_items()
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
            $items              = Item::where('id',$item_id)->get()->toArray();
            //Test case as right answer of test
            $testCase           = [
                                'data'      => [
                                            'id'            => $items[0]['id'],
                                            'item_id'       => $items[0]['id'],
                                            'is_completed'  => false,
                                            'checklist_id'  => $items[0]['checklist_id']
                                            ]
                                ];

            //Parameter sent to server
            $testParam          = [
                                    'item_id'      => $items[0]['id']
                                ];
            
            //Processing data to server
            $status             = (array) $this->call(
                                'POST',
                                '/checklists/incomplete',
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
            
            //Converting data from json to Array
            $data               = (array)json_decode($this->response->getContent(),true);
            if(sizeof($data)>0){
                $result             = array(
                                    'data'      => array(
                                                'id'            => $data['data'][0]['id'],
                                                'item_id'       => $data['data'][0]['item_id'],
                                                'is_completed'  => $data['data'][0]['is_completed'] == 1 ? true : false,
                                                'checklist_id'  => $data['data'][0]['checklist_id']
                                                )
                                    );
                //Testing
                $this->assertArraySubset(
                    $testCase, $result
                );

            }else{
                throw new Exception('Responses: '.$status);
            }                
            //Deleting Testing DAta                
            App\User::destroy($user->id);   
            Template::destroy($template_id);
        }catch(\Exception $e){
            $this->expectException($e->getMessage());
        }
    }

    public function testShow_all_list_items_in_given_checklist()
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
            $data               = new ListofItemInGivenChecklistResource(Checklist::with('items')->where('id',$checklist_id)->first());
            $testCase           = json_encode($data);

            // //Parameter sent to server
            // $testParam          = [
            //                         'item_id'      => $items[0]['attributes']['item_id']
            //                     ];

            //Parameter sent to server
            $status             = (array) $this->call(
                                'GET',
                                '/checklists/'.$checklist_id.'/items',
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

    public function testGet_checklist_item()
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
            $data               = new GetChecklistItemResource(Item::where('checklist_id',$checklist_id)->where('id',$item_id)->first());
            $testCase           = json_encode($data);

            // //Parameter sent to server
            // $testParam          = [
            //                         'item_id'      => $items[0]['attributes']['item_id']
            //                     ];
            //Processing data to server
            $status             = (array) $this->call(
                                'GET',
                                '/checklists/'.$checklist_id.'/items/'.$item_id,
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

    public function testCreate_checklist_item()
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

            // //Creating item data
            // factory(Item::class)->create(
            //     [
            //         'id'            => $item_id,
            //         'checklist_id'  => $checklist_id
            //     ]
            // );

            //Test case as right answer of test
            $data               = new CreateChecklistItemResource(Checklist::where('id',$checklist_id)->first());
            $testCase           = json_encode($data);
            //Parameter sent to server
            $testParam          = [
                                        'attribute'     => [
                                            "description"   => $faker->sentence($nbWords = 6, $variableNbWords = true),
                                            "due"           => $faker->date('Y-m-d H:i:s'),
                                            "urgency"       => $faker->randomDigit,
                                            "assignee_id"   => App\User::all()->random()->id
                                        ],
                                ];
            //Processing data to server
            $status             = (array) $this->call(
                                'POST',
                                '/checklists/'.$checklist_id.'/items',
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
            //Deleting Testing DAta                
            App\User::destroy($user->id);   
            Template::destroy($template_id);
        }catch(\Exception $e){
            $this->expectException($e->getMessage());
        }
    }    

    public function testUpdate_checklist_item()
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

            //Test case as right answer of test
            $data               = new CreateChecklistItemResource(Checklist::where('id',$checklist_id)->first());
            $testCase           = json_encode($data);
            //Parameter sent to server
            $testParam          = [
                                        'attribute'     => [
                                            "description"   => $faker->sentence($nbWords = 6, $variableNbWords = true),
                                            "due"           => $faker->date('Y-m-d H:i:s'),
                                            "urgency"       => $faker->randomDigit,
                                            "assignee_id"   => App\User::all()->random()->id
                                        ],
                                ];
            //Processing data to server
            $status             = (array) $this->call(
                                'PATCH',
                                '/checklists/'.$checklist_id.'/items/'.$item_id,
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
            //Deleting Testing DAta                
            App\User::destroy($user->id);   
            Template::destroy($template_id);
        }catch(\Exception $e){
            $this->expectException($e->getMessage());
        }
    }

    public function testDelete_checklist_item()
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

            //Test case as right answer of test
            $data               = new CreateChecklistItemResource(Checklist::where('id',$checklist_id)->first());
            $testCase           = json_encode($data);
            //Parameter sent to server
            $testParam          = [
                                        'attribute'     => [
                                            "description"   => $faker->sentence($nbWords = 6, $variableNbWords = true),
                                            "due"           => $faker->date('Y-m-d H:i:s'),
                                            "urgency"       => $faker->randomDigit,
                                            "assignee_id"   => App\User::all()->random()->id
                                        ],
                                ];
            //Processing data to server
            $status             = (array) $this->call(
                                'DELETE',
                                '/checklists/'.$checklist_id.'/items/'.$item_id,
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
            //Deleting Testing DAta                
            App\User::destroy($user->id);   
            Template::destroy($template_id);
        }catch(\Exception $e){
            $this->expectException($e->getMessage());
        }
    }    
    public function testUpdate_bulk_checklist()
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

            //Test case as right answer of test
            $data               = [
                                    "data" => [
                                        [
                                            "status"    =>  200,
                                            "id"        =>  $item_id,
                                            "action"    =>  "update"
                                        ]
                                    ]
                                ];
            $testCase           = json_encode($data);
            //Parameter sent to server
            $testParam          = [ 
                                    [
                                        'id'                => $item_id,
                                        'action'            => 'update',
                                        'attributes'        => [
                                            "description"   => $faker->sentence($nbWords = 6, $variableNbWords = true),
                                            "due"           => $faker->date('Y-m-d H:i:s'),
                                            "urgency"       => $faker->randomDigit
                                        ]    
                                    ]
                                ];
            //Processing data to server
            $status             = (array) $this->call(
                                'POST',
                                '/checklists/'.$checklist_id.'/items/_bulk',
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
            //Deleting Testing DAta                
            App\User::destroy($user->id);   
            Template::destroy($template_id);
        }catch(\Exception $e){
            $this->expectException($e->getMessage());
        }
    }

    public function testSummary_item()
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

            $date       = Carbon::now();
            $today      = count(Item::whereDate('due',Carbon::now()->format('Y-m-d H:i:s'))->get());
            $past_due   = count(Item::whereDate('due','<',Carbon::now()->format('Y-m-d H:i:s'))->get());
            $this_week  = count(Item::whereBetween('due',[Carbon::now()->startOfWeek()->format('Y-m-d H:i:s'),Carbon::now()->endOfWeek()->format('Y-m-d H:i:s')])->get());
            $past_week  = count(Item::whereBetween('due',[Carbon::now()->subWeek()->subDay(7)->format('Y-m-d H:i:s'),Carbon::now()->subWeek()->format('Y-m-d H:i:s')])->get());
            $this_month = count(Item::whereBetween('due',[Carbon::now()->startOfMonth()->format('Y-m-d H:i:s'),Carbon::now()->endOfMonth()->format('Y-m-d H:i:s')])->get());
            $past_month = count(Item::whereBetween('due',[Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d H:i:s'),Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d H:i:s')])->get());
            $total      = count(Item::all());

            //Test case as right answer of test
            $data           = [
                                'today'         => $today,
                                'past_due'      => $past_due,
                                'this_week'     => $this_week,
                                'past_week'     => $past_week,
                                'this_month'    => $this_month,
                                'past_month'    => $past_month,
                                'total'         => $total
                            ];
            $testCase           = json_encode($data);
            //Parameter sent to server

            //Processing data to server
            $status             = (array) $this->call(
                                'GET',
                                '/checklists/items/summaries',
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
            //Deleting Testing DAta                
            App\User::destroy($user->id);   
        }catch(\Exception $e){
            $this->expectException($e->getMessage());
        }
    }
}
