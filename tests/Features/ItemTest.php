<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Controllers\AuthController;
use App\User;
use Illuminate\Http\Request;
use App\Models\Item\Item;
use App\Models\Checklist\Checklist;
use App\Models\Checklist\Eloquent\ChecklistModel;

class ItemTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */

    public function testShow_complete_items()
    {
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
            $type               = $faker->jobTitle;
            $checklist_id       = App\Models\Checklist\Eloquent\ChecklistModel::create(
                                    'che'
                                );
            $checklist          = App\Models\Item\Item::create(
                [
                    'checklist_id'  => App\Models\Checklist\Eloquent\ChecklistModel::first()->id,
                    'type'          => $faker->jobTitle
                ]
            );
            $item               = App\Models\Item\Item::create(
                                    [
                                        'checklist_id'  => App\Models\Checklist\Eloquent\ChecklistModel::first()->id,
                                        'type'          => $faker->jobTitle
                                    ]
                                );
            $testCase           = array (
                                'data'      => array(

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


        // $data       = $this->items->showtemplate();
        // if($data){
        //     return response()->json(
        //         [
        //             'status'    => true,
        //             'message'   => 'List of Company',
        //             'data'      => $data
        //         ],200
        //     );
        // }else{
        //     return response()->json(
        //         [
        //             'stataus'   => false,
        //             'message'   => 'Error Getting List of Company',
        //             'data'      => null
        //         ],400
        //     );
        // }

        /*
        $items              = new App\Models\Item\Item();
        $data               = $items->showtemplate();

        $testCase           = array (
                            'success'   => true,
                            'message'   => 'Registration Success',
                            'data'      => array(
                                            'name'      => $name,
                                            'email'     => $email
                                        )
                            );
        $this->post('/register',$testItem);
        $data               = (array)json_decode($this->response->getContent(),true);
        if(sizeof($data)>0){
            $result             = array(
                'success'   => $data['success'],
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
            $this->assertTrue(false,'Hello');
        }
        */
    }
}
