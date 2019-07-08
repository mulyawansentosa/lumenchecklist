<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use App\Item;
use App\Checklist;
use App\Template;
use DB;
use App\Http\Resources\Item\ItemCompleteCollection;
use App\Http\Resources\Checklist\GetChecklistItemResource;
use App\Http\Resources\Item\CreateChecklistItemResource;
use Carbon\Carbon;

class ItemController extends Controller{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request, $id)
    {
        try{
            $req                    = json_decode($request->getContent(),true);
            $data                   = $req['data']['attribute'];
            $auth                   = $request->header();
            $token                  = $auth['authorization'];
            $user                   = User::where('api_token',str_replace('bearer ','',$token[0]))->first();
            $data['user_id']        = $user->id;
            $data['checklist_id']   = $id;
            $result                 = Item::create($data);
            if($result){
                $datachecklist      = Checklist::find($id);
                return new CreateChecklistItemResource($datachecklist);
            }
        }catch(\Exception $e){
            return response()->json(
                [
                    // 'success'   => false,
                    // 'code'      => 500,
                    // 'message'   => $e->getMessage()
                    'data'      => [$e->getMessage()]
                ], 500
            );
        }
    }

    public function getchecklistitem($checklistId, $itemId)
    {
        try{
            $datachecklist      = Item::where('checklist_id',$checklistId)
                                ->where('id',$itemId)
                                ->first();

            if(count($datachecklist) > 0){
                return new GetChecklistItemResource($datachecklist);
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

    public function completeitems(Request $request)
    {
        try{
            $req                = json_decode($request->getContent(),true);
            $item_data          = array_values($req['data']);
            $update_data        = Item::whereIn('id', $item_data)->update(['is_completed' => true]);
            $result             = Item::whereIn('id',$item_data)->get();
            if(count($result) > 0){
                return new ItemCompleteCollection($result);
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
                    'data'      => []
                ], 500
            );
        }
    }

    public function incompleteitems(Request $request)
    {
        try{
            $req                = json_decode($request->getContent(),true);
            $item_data          = array_values($req['data']);
            $update_data        = Item::whereIn('id', $item_data)->update(['is_completed' => false]);
            $result             = Item::whereIn('id',$item_data)->get();
            if(count($result) > 0){
                return new ItemCompleteCollection($result);
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
                    'data'      => []
                ], 500
            );
        }
    }

    public function update(Request $request, $checklistId,$itemId)
    {
        try{
            $req                    = json_decode($request->getContent(),true);
            $data                   = $req['data']['attribute'];
            $item                   = Item::where('checklist_id',$checklistId)->where('id',$itemId)->first();
            $result                 = $item->update($data);
            // var_dump($result);
            if($result){
                $datachecklist      = Checklist::find($checklistId);
                return new CreateChecklistItemResource($datachecklist);
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
                    // 'success'   => false,
                    // 'code'      => 500,
                    // 'message'   => $e->getMessage()
                    'data'      => [$e->getMessage()]
                ], 500
            );
        }
    }

    public function bulkupdate(Request $request, $checklistId)
    {
        try{
            $req                    = json_decode($request->getContent(),true);
            $data                   = $req['data'];
            foreach($data as $items){
                $dataitem   = array();
                $execute    = Item::where('checklist_id',$checklistId)->where('id',$items['id'])->first();
                if($execute){
                    $result    = $execute->update(
                        [
                            'description'   => $items['attributes']['description'],
                            'due'           => $items['attributes']['due'],
                            'urgency'       => $items['attributes']['urgency']
                        ]
                    );
                    if($result){
                        $dataitem['status']  = 200;
                    }else{
                        $dataitem['status']  = 403;
                    }    
                }else{
                    $dataitem['status']  = 404;
                }
                $dataitem['id']      = $items['id'];
                $dataitem['action']  = 'update';
                $check[] = $dataitem;
            }
            if(sizeof($check)>0){
                return response()->json(
                    [
                        'data'   => $check
                    ],200
                );
            }else{
                return response()->json(
                    [
                        // 'success'   => false,
                        // 'code'      => 500,
                        // 'message'   => $e->getMessage(),
                        'data'      => []
                    ], 500
                );
            }
        }catch(\Exception $e){
            return response()->json(
                [
                    // 'success'   => false,
                    // 'code'      => 500,
                    // 'message'   => $e->getMessage(),
                    'data'      => []
                ], 500
            );
        }
    }

    public function destroy($checklistId,$itemId)
    {
        try{
            $item                   = Item::where('checklist_id',$checklistId)->where('id',$itemId)->first();
            if($item){
                $result             = $item->delete();
                $datachecklist      = Checklist::find($checklistId);
                return new CreateChecklistItemResource($datachecklist);
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
                    // 'success'   => false,
                    // 'code'      => 500,
                    // 'message'   => $e->getMessage(),
                    'data'      => []
                ], 500
            );
        }
    }

    public function summaries(Request $request)
    {
        try{
            $date       = Carbon::now();
            $today      = count(Item::whereDate('due',Carbon::now()->format('Y-m-d H:i:s'))->get());
            $past_due   = count(Item::whereDate('due','<',Carbon::now()->format('Y-m-d H:i:s'))->get());
            $this_week  = count(Item::whereBetween('due',[Carbon::now()->startOfWeek()->format('Y-m-d H:i:s'),Carbon::now()->endOfWeek()->format('Y-m-d H:i:s')])->get());
            $past_week  = count(Item::whereBetween('due',[Carbon::now()->subWeek()->subDay(7)->format('Y-m-d H:i:s'),Carbon::now()->subWeek()->format('Y-m-d H:i:s')])->get());
            $this_month = count(Item::whereBetween('due',[Carbon::now()->startOfMonth()->format('Y-m-d H:i:s'),Carbon::now()->endOfMonth()->format('Y-m-d H:i:s')])->get());
            $past_month = count(Item::whereBetween('due',[Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d H:i:s'),Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d H:i:s')])->get());
            $total      = count(Item::all());
            return response()->json(
                [
                    'today'         => $today,
                    'past_due'      => $past_due,
                    'this_week'     => $this_week,
                    'past_week'     => $past_week,
                    'this_month'    => $this_month,
                    'past_month'    => $past_month,
                    'total'         => $total
                ],200
            );
        }catch(\Exception $e){
            return response()->json(
                [
                    // 'success'   => false,
                    // 'code'      => 500,
                    // 'message'   => $e->getMessage(),
                    'data'      => []
                ], 500
            );
        }
    }
}
