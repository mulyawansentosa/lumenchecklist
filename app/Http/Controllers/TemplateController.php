<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Template;
use App\Checklist;
use App\Item;
use App\User;
use App\Http\Resources\Template\ListAllChecklistTemplateCollection;
use App\Http\Resources\Template\CreateChecklistTemplateResource;
use Faker\Factory as Faker;

class TemplateController extends Controller{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function listallchecklisttemplate()
    {
        try{
            $data      = Template::with('checklist')->paginate();
            if(count($data) > 0){
                return new ListAllChecklistTemplateCollection($data);
            }else{
                return response()->json(
                    [
                        'data'   => []
                    ],200
                );    
            }
        }catch(\Exception $e){
            return response()->json(
                [
                    'data'      => [$e->message()]
                ], 500
            );
        }
    }

    public function getchecklisttemplate($templateId)
    {
        try{
            $data      = Template::where('id',$templateId)->with('checklist')->paginate();
            if(count($data) > 0){
                return new ListAllChecklistTemplateCollection($data);
            }else{
                return response()->json(
                    [
                        'data'   => []
                    ],200
                );    
            }
        }catch(\Exception $e){
            return response()->json(
                [
                    'data'      => [$e->message()]
                ], 500
            );
        }
    }

    public function store(Request $request)
    {
        try{
            $faker                  = Faker::create();
            $req                    = json_decode($request->getContent(),true);
            $data                   = $req['data']['attributes'];
            $auth                   = $request->header();
            $token                  = $auth['authorization'];
            $user                   = User::where('api_token',str_replace('bearer ','',$token[0]))->first();
            $dataitems              = $data['items'];
            $exetemplate            = Template::create(
                                    [
                                        'name'   => $data['name']

                                    ]
            );

            $exechecklist           = $exetemplate->checklist()->create(
                                    [
                                        'description'   => $data['checklist']['description'],
                                        'due_interval'  => $data['checklist']['due_interval'],
                                        'due_unit'      => $data['checklist']['due_unit']
                                    ]
            );
            foreach($dataitems as $val){
                $exechecklist->items()->create(
                    [
                        'user_id'       => $user->id,
                        'description'   => $val['description'],
                        'urgency'       => $val['urgency'],
                        'due_interval'  => $val['due_interval'],
                        'due_unit'      => $val['due_unit']
                    ]
                );
            }
            $datatemplate           = Template::find($exetemplate->id);
            return new CreateChecklistTemplateResource($datatemplate);
        }catch(\Exception $e){
            $dataitem['status']     = 500;
            $dataitem['error']      = $e->getMessage();
            return response()->json(
                $dataitem, 500
            );
        }
    }    
}
