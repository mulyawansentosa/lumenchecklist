<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use App\Template;
use App\Checklist;
use App\Item;
use DB;
use App\Http\Resources\Checklist\ListofItemInGivenChecklistResource;
use App\Http\Resources\Checklist\GetChecklistResource;
use App\Http\Resources\Checklist\GetListofChecklistCollection;
use Carbon\Carbon;

class ChecklistController extends Controller{
    protected $checklists;
    protected $items;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->checklists   = new Checklist;
        $this->items        = new Item;
    }

    public function listofitemingivenchecklist($checklistId)
    {
        try{
            $datachecklist      = Checklist::with('items')->where('id',$checklistId)->first();
            // var_dump($datachecklist->items);
            if(count($datachecklist) > 0){
                return new ListofItemInGivenChecklistResource($datachecklist);
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

    public function getchecklist($checklistId)
    {
        try{
            $datachecklist      = Checklist::where('id',$checklistId)
                                ->first();

            if(count($datachecklist) > 0){
                return new GetChecklistResource($datachecklist);
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

    public function getlistofchecklist()
    {
        try{
            $datachecklist      = Checklist::paginate();

            if(count($datachecklist) > 0){
                return new GetListofChecklistCollection($datachecklist);
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

    public function update(Request $request, $checklistId)
    {

        $req                    = json_decode($request->getContent(),true);
        $data                   = $req['data']['attributes'];

        $execute                = Checklist::find($checklistId);
        if($execute){
            $result     = $execute->update(
                [
                    'object_domain' => $data['object_domain'],
                    'object_id'     => $data['object_id'],
                    'description'   => $data['description'],
                    'is_completed'  => $data['is_completed'],
                    'completed_at'  => $data['completed_at'],
                    'created_at'    => $data['created_at']
                ]
            );    
            if($result){
                $datachecklist      = Checklist::where('id',$checklistId)
                                    ->first();

                if(count($datachecklist) > 0){
                    return new GetChecklistResource($datachecklist);
                }else{
                    return response()->json(
                        [
                            'data'   => []
                        ],200
                    );    
                }
            }else{
                return response()->json(
                    [
                        'data'   => []
                    ],200
                );    
            }
        }else{
            return response()->json(
                [
                    'data'   => []
                ],404
            );
        }
        

        if($result){
            $datachecklist      = $this->checklists->show($checklistId);
            return response()->json(
                [
                    'data'   => $datachecklist
                ],200
            );
        }
    }

    public function destroy($checklistId)
    {
        try{
            $check             = Checklist::find($checklistId);
            if($check){
                $result        = Checklist::find($checklistId)->delete();
                if($result){
                    $dataitem['status']  = 201;
                    $dataitem['action']  = 'success';
                }
            }else{
                $dataitem['status']  = 404;
                $dataitem['error']  = 'Not Found';
            }
            return response()->json(
                $dataitem, 500
            );
        }catch(\Exception $e){
            $dataitem['status']     = 500;
            $dataitem['error']      = $e->getMessage();
            return response()->json(
                $dataitem, 500
            );
        }
    }

    public function store(Request $request)
    {
        try{
            $req                    = json_decode($request->getContent(),true);
            $data                   = $req['data']['attributes'];
            $auth                   = $request->header();
            $token                  = $auth['authorization'];
            $user                   = User::where('api_token',str_replace('bearer ','',$token[0]))->first();
            $dataitems              = $data['items'];
            $execute                = Checklist::create(
                                    [
                                        'template_id'   => Template::all()->random()->id,
                                        'object_domain' => $data['object_domain'],
                                        'object_id'     => $data['object_id'],
                                        'due'           => $data['due'],
                                        'urgency'       => $data['urgency'],
                                        'description'   => $data['description'],
                                        'task_id'       => $data['task_id']
                                    ]
            );
            foreach($dataitems as $val){
                $execute->items()->create(
                    [
                        'description'   => $val,
                        'user_id'       => $user->id
                    ]
                );
            }
            $datachecklist      = Checklist::where('id',$execute->id)->first();
            return new GetChecklistResource($datachecklist);
        }catch(\Exception $e){
            $dataitem['status']     = 500;
            $dataitem['error']      = $e->getMessage();
            return response()->json(
                $dataitem, 500
            );
        }
    }
}
