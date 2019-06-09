<?php

namespace App\Models\Checklist;
use App\Models\Checklist\Eloquent\ChecklistModel;
use App\Models\Checklist\Eloquent\ChecklistattributeModel;
use App\Models\Checklist\Eloquent\ChecklistlinkModel;
use App\Models\Template\Eloquent\TemplateModel;
use App\Models\Item\Item;
use Carbon\Carbon;
use DB;

class Checklist{
    protected $checklists;
    protected $templates;
    protected $checklistattributes;
    protected $checklistlinks;

    public function __construct(){
        $this->checklists           = new ChecklistModel;
        $this->templates            = new TemplateModel;
        $this->items                = new Item;
        $this->checklistattributes  = new ChecklistattributeModel;
        $this->checklistlinks       = new ChecklistlinkModel;
    }

    public function show($id){
        $data               = $this
                                ->checklists
                                ->select(
                                    'type',
                                    'id'
                                )->find($id);
        if($data){
            $data['attributes'] = $data->attributes;
            $data['links']      = $data->links;    
        }
        return $data;
    }

    public function listofitemingivenchecklist($checklistId){
        $data               = $this
                                ->checklists
                                ->select(
                                    'type',
                                    'id'
                                )->find($checklistId);
        if($data){
            $data['attributes'] = $data->attributes;
            $data['items']      = $this->items->listofitemingivenchecklistitems($data->id);
            $data['links']      = $data->links;    
        }
        return $data;        
    }

    public function getchecklist($checklistId){
        $data               = $this
                                ->checklists
                                ->select(
                                    'type',
                                    'id'
                                )->find($checklistId);
        if($data){
            $data['attributes'] = $data->attributes;
            $data['links']      = $data->links;    
        }
        return $data;        
    }

    public function getchecklists(){
        $datas              = array();
        $data               = $this
                                ->checklists
                                ->select(
                                    'type',
                                    'id'
                                )->get();
        foreach($data as $val => $key){
            $key['attributes']      = $this->checklistattributes->where('checklist_id','=',$key['id'])->first();
            $key['links']           = $this->checklistlinks->where('checklist_id','=',$key['id'])->first();
            $datas[]                = $key;
        }
        return $datas;
    }

    public function update($req,$checklistId){
        $execute    = $this->checklists->find($checklistId);
        if($execute){
            $result     = $execute->attributes()->update(
                [
                    'object_domain' => $req['object_domain'],
                    'object_id'     => $req['object_id'],
                    'description'   => $req['description'],
                    'is_completed'  => $req['is_completed'],
                    'completed_at'  => $req['completed_at']
    //                'created_at'    => Carbon::createFromFormat('Y-m-d H:i:s', $req['created_at'])
                ]
            );    
            return $result;
        }else{
            $ret['status']      = '404';
            $ret['error']       = 'Not found';
            return $ret;
        }
    }

    public function destroy($checklistId){
        $check             = $this->checklists->find($checklistId);
        if($check){
            $result        = $this->checklists->find($checklistId)->delete();
            if($result){
                $data               = $this->items->showitemsonchecklist($checklistId);
                if($data){
                    $result             = $this->items->where(
                        'checklist_id',
                        '=',
                        $checklistId
                    )->delete();            
                }
                $dataitem['status']  = 201;
                $dataitem['action']  = 'success';
                }else{
            }
        }else{
            $dataitem['status']  = 404;
            $dataitem['action']  = 'Not Found';
        }
        return $dataitem;
    }

    public function store($req){
        $execute    = $this->checklists->create(
            [
                'template_id'   => $this->templates->select('id')->first()['id'],
                'type'          => 'Checklist',
            ]
        );
        $result    = $execute->attributes()->create(
            [
                'object_domain' => $req['object_domain'],
                'object_id'     => $req['object_id'],
                'is_completed'  => false,
//                'due'           => $req['due'],
                'urgency'       => $req['urgency'],
                'description'   => $req['description'],
                'task_id'       => $req['task_id'],
            ]
        );
        return $execute;
    }

}