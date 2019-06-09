<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Checklist\Checklist;
use App\Models\Item\Item;

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

    public function index()
    {
        $template   = new Checklist();
        $data       = $template->showtemplate();
        if($data){
            return response()->json(
                [
                    'status'    => true,
                    'message'   => 'List of Company',
                    'data'      => $data
                ],200
            );
        }else{
            return response()->json(
                [
                    'stataus'   => false,
                    'message'   => 'Error Getting List of Company',
                    'data'      => null
                ],400
            );
        }
    }

    public function listofitemingivenchecklist($checklistId)
    {
        $datachecklist      = $this->checklists->listofitemingivenchecklist($checklistId);
        return response()->json(
            [
                'data'   => $datachecklist
            ],200
        );
    }

    public function getchecklist($checklistId)
    {
        $data       = $this->checklists->getchecklist($checklistId);
        if($data){
            return response()->json(
                [
                    'data'      => $data
                ],200
            );
        }else{
            return response()->json(
                [
                    'stataus'   => "404",
                    'error'     => "Not found"
                ],404
            );
        }
    }

    public function update(Request $request, $checklistId)
    {
        $req                    = json_decode($request->getContent(),true);
        $data                   = $req['data']['attributes'];

        $result = $this->checklists->update($data,$checklistId);
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
        $execute        = $this->checklists->destroy($checklistId);
        if($execute){
            $dataitem['status']  = 200;
            $dataitem['action']  = 'success';
        }else{
            $dataitem['status']  = 404;
            $dataitem['action']  = 'Not Found';
        }
        // return response()->json($dataitem);
        return $execute;
    }

    public function store(Request $request)
    {
        $req                    = json_decode($request->getContent(),true);
        $data                   = $req['data']['attributes'];

        $result = $this->checklists->store($data);
        if($result){
            $datachecklist      = $this->items->storechecklist($data['items'],$result['id']);
            $res                = $this->checklists->getchecklist($result['id']);
            return response()->json(
                [
                    'data'   => $res
                ],200
            );
        }
    }

    public function getchecklists()
    {
        $data       = $this->checklists->getchecklists();
        if($data){
            return response()->json(
                [
                    $data
                ],200
            );
        }else{
            return response()->json(
                [
                    'stataus'   => "500",
                    'error'     => "Server error"
                ],404
            );
        }
    }

}
