<?php

namespace App\Models\Item;
use Illuminate\Http\Request;
use App\Models\Item\Eloquent\ItemModel;
use App\Models\Item\Eloquent\ItemlinkModel;
use App\Models\Item\Eloquent\ItemattributeModel;
use App\Models\Checklist\Checklist;

use DB;

class Item{
    protected $items;
    protected $itemlinks;
    protected $itemattributes;
    protected $checklists;


    public function __construct(){
        $this->items            = new ItemModel;
        $this->itemlinks        = new ItemlinkModel;
        $this->itemattributes   = new ItemattributeModel;
    }

    public function showtemplate(){
        $temp               = $this->items->find(1);
        $temp['attributes'] = $temp->attributes;
        $temp['links']      = $temp->links;       
        return $temp;
    }

    public function store($req){
        $execute    = $this->items->create(
            [
                'checklist_id'  => $req['checklist_id'],
                'type'          => 'Item',
            ]
        );
        $result    = $execute->attributes()->create(
            [
                'description'   => $req['description'],
                'is_completed'  => false,
                'due'           => $req['due'],
                'urgency'       => $req['urgency'],
                'assignee_id'   => $req['assignee_id'],
                'checklist_id'  => $req['checklist_id']
            ]
        );
        return $result;
    }

    public function storechecklist($req,$checklistId){
        foreach($req as $key => $val){
            $execute    = $this->items->create(
                [
                    'checklist_id'  => $checklistId,
                    'type'          => 'Item',
                ]
            );
            $result    = $execute->attributes()->create(
                [
                    'description'   => $val,
                    'is_completed'  => false
                ]
            );
        }
        return $result;
    }

    public function getchecklistitem($checklistId, $itemId){
        $where              = ['checklist_id' => $checklistId, 'id' => $itemId];
        $data               = $this
                                ->items
                                ->select(
                                    'type',
                                    'id'
                                )->where(
                                    'checklist_id','=',$checklistId
                                )->where(
                                    'id','=',$itemId
                                )
                                ->first();
        if($data){
            $data['attributes'] = $data->attributes;
            $data['links']      = $data->links;    
        }
        return $data;
    }

    public function completeitems($datas){

        DB::beginTransaction();
        try {
            $check                  = array();
            foreach($datas as $data){
                foreach($data as $list => $item){
                    $data           = $this->itemattributes->where('item_id','=',$item)->first();
                    if($data){
                        $result     = $this->itemattributes->where('item_id','=',$item)->update(array('is_completed' => true));
                        if($result){
                            $check[]  = true;
                        }else{
                            $check[]  = false;
                        }
                    }
                }
            }
            if(!in_array(false,$check)){
                $isi = $this->showitems($datas);
            }
            return $isi;
        } catch(ValidationException $e)
        {
            DB::rollback();
            return $e;
        }
        DB::commit();    
    }

    public function incompleteitems($datas){

        DB::beginTransaction();
        try {
            $check                  = array();
            foreach($datas as $data){
                foreach($data as $list => $item){
                    $data           = $this->itemattributes->where('item_id','=',$item)->first();
                    if($data){
                        $result     = $this->itemattributes->where('item_id','=',$item)->update(array('is_completed' => false));
                        if($result){
                            $check[]  = true;
                        }else{
                            $check[]  = false;
                        }
                    }
                }
            }
            if(!in_array(false,$check)){
                $isi = $this->showitems($datas);
            }
            return $isi;
        } catch(ValidationException $e)
        {
            DB::rollback();
            return $e;
        }
        DB::commit();    
    }

    public function showitems($datas){
        $isi                = array();
        foreach($datas as $data){
            foreach($data as $list => $item){
                $result      = $this->items->select(
                                'items.id',
                                'itemattributes.item_id',
                                'itemattributes.is_completed',
                                'checklist_id'
                            )->join(
                                'itemattributes',
                                'items.id',
                                '=',
                                'itemattributes.item_id'
                            )->where(
                                'items.id',
                                '=',
                                $item
                            )->first();
                $isi[]      = $result;
            }
        }
        return $isi;
    }

    public function showitemsonchecklist($checklistId){
        $isi                = array();
        $result      = $this->items->select(
            'items.id',
            'itemattributes.item_id',
            'itemattributes.is_completed',
            'checklist_id'
        )->join(
            'itemattributes',
            'items.id',
            '=',
            'itemattributes.item_id'
        )->where(
            'items.checklist_id',
            '=',
            $checklistId
        )->get();
        return $isi;
    }

    public function showitem($itemId){
        $result      = $this->items->select(
            'items.id',
            'itemattributes.item_id',
            'itemattributes.is_completed',
            'checklist_id'
        )->join(
            'itemattributes',
            'items.id',
            '=',
            'itemattributes.item_id'
        )->where(
            'items.id',
            '=',
            $itemId
        )->first();
        return $result;
    }

    public function listofitemingivenchecklistitems($id){
        $isi        = array();
        $result      = $this->items->select(
            'items.id',
            'itemattributes.is_completed',
            'itemattributes.due',
            'itemattributes.urgency',
            'items.checklist_id',
            'itemattributes.assignee_id',
            'itemattributes.task_id',
            'itemattributes.completed_at',
            'itemattributes.updated_by',
            'itemattributes.updated_at',
            'itemattributes.created_at'
        )->join(
            'itemattributes',
            'items.id',
            '=',
            'itemattributes.item_id'
        )->where(
            'items.checklist_id',
            '=',
            $id
        )->get();
        return $result;
    }

    public function update($req,$checklistId,$itemId){
        $execute    = $this->items->where(
            'checklist_id',
            '=',
            $checklistId
        )->where(
            'id',
            '=',
            $itemId
        )->first();
        $result    = $execute->attributes()->update(
            [
                'description'   => $req['description'],
                'is_completed'  => false,
                'due'           => $req['due'],
                'urgency'       => $req['urgency'],
                'assignee_id'   => $req['assignee_id']
            ]
        );
        return $result;
    }

    public function updatebulk($req,$checklistId){
        $check        = array();
        foreach($req as $items){
            $dataitem   = array();
            $execute    = $this->items->where(
                'checklist_id',
                '=',
                $checklistId
            )->where(
                'id',
                '=',
                $items['id']
            )->first();
            if($execute){
                $result    = $execute->attributes()->update(
                    [
                        'description'   => $req['attributes']['description'],
                        'due'           => $req['attributes']['due'],
                        'urgency'       => $req['attributes']['urgency']
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
        return $check;  
    }

    public function destroy($checklistId,$itemId){
        DB::beginTransaction();
        try {

            $check              = array();
            $result             = $this->items->where(
                'checklist_id',
                '=',
                $checklistId
            )->where(
                'id',
                '=',
                $itemId
            )->delete();
            if($result){
                $check[]        = true;
            }

            $result1            = $this->itemattributes->where(
                'item_id',
                '=',
                $itemId
            )->delete();
            if($result1){
                $check[]        = true;
            }
            if(!in_array(false,$check)){
                $this->checklists = new Checklist;
                $check  = $this->checklists->show($checklistId);
                return $check;
            }
        } catch(ValidationException $e)
        {
            DB::rollback();
            return $e;
        }
        DB::commit();    
    }

    public function summaries($itemId){
        $result      = $this->items->select(
            'items.id',
            'itemattributes.item_id',
            'itemattributes.is_completed',
            'checklist_id'
        )->join(
            'itemattributes',
            'items.id',
            '=',
            'itemattributes.item_id'
        )->where(
            'items.id',
            '=',
            $itemId
        )->first();
        return $result;
    }
}