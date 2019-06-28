<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Controllers\AuthController;
use App\User;
use Illuminate\Http\Request;
use App\Models\Item\Item;
use App\Models\Checklist\Checklist;

class ItemTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */

    public function testShow_complete_items()
    {
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
