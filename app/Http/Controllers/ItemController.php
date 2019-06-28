<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Item\Item;
use App\Models\Checklist\Checklist;

class ItemController extends Controller{
    protected $items;
    protected $checklists;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->items        = new Item;
        $this->checklists    = new Checklist;
    }

    public function index()
    {
        try{
            $data       = $this->items->showtemplate();
            if($data){
                return response()->json(
                    [
                        // 'status'    => true,
                        // 'message'   => 'List of Company',
                        'data'      => $data
                    ],200
                );
            }else{
                return response()->json(
                    [
                        // 'stataus'   => false,
                        // 'message'   => 'Error Getting List of Company',
                        'data'      => null
                    ],400
                );
            }
        }catch(\Exception $e){
            return response()->json(
                [
                    'success'   => false,
                    'code'      => 500,
                    'message'   => $e->getMessage(),
                    'data'      => []
                ], 500
            );
        }
    }

    public function store(Request $request, $id)
    {
        $req                    = json_decode($request->getContent(),true);
        $data                   = $req['data']['attribute'];
        $data['checklist_id']   = $id;

        $result = $this->items->store($data);
        if($result){
            $datachecklist      = $this->checklists->show($id);
            return response()->json(
                [
                    'data'   => $datachecklist
                ],200
            );
        }
    }

    public function getchecklistitem($checklistId, $itemId)
    {

        $result = $this->items->getchecklistitem($checklistId, $itemId);
        if($result){
            return response()->json(
                [
                    'data'   => $result
                ],200
            );
        }
    }

    public function completeitems(Request $request)
    {
        try{
            $req                    = json_decode($request->getContent(),true);
            $datas                  = $req['data'];
            if(is_array($datas)){
                $result             = $this->items->completeitems($datas);
                if($result){
                    return response()->json(
                        [
                            'data'   => $result
                        ], 200
                    );
                }
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
        $req                    = json_decode($request->getContent(),true);
        $datas                  = $req['data'];
        if(is_array($datas)){
            $result             = $this->items->incompleteitems($datas);
            if($result){
                return response()->json(
                    [
                        'data'   => $result
                    ],200
                );
            }
    
        }
    }

    public function update(Request $request, $checklistId,$itemId)
    {
        $req                    = json_decode($request->getContent(),true);
        $data                   = $req['data']['attribute'];
        $data['checklist_id']   = $checklistId;

        $result = $this->items->update($data,$checklistId,$itemId);
        if($result){
            $datachecklist      = $this->checklists->show($checklistId);
            return response()->json(
                [
                    'data'   => $datachecklist
                ],200
            );
        }
    }

    public function bulkupdate(Request $request, $checklistId)
    {
        $req                    = json_decode($request->getContent(),true);
        $data                   = $req['data'];

        $result = $this->items->updatebulk($data,$checklistId);
        if($result){
            return response()->json(
                [
                    'data'   => $result
                ],200
            );
        }
    }

    public function destroy($checklistId,$itemId)
    {
        $result = $this->items->destroy($checklistId,$itemId);
        if($result){
            $datachecklist      = $this->checklists->show($checklistId);
            return response()->json(
                [
                    'data'   => $datachecklist
                ],200
            );
        }
    }

    public function summaries(Request $request)
    {
        $today      = 0;
        $past_due   = 0;
        $this_week  = 0;
        $past_week  = 0;
        $this_month = 0;
        $past_month = 0;
        $total      = 0;
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
    }
}
