<?php
namespace App\Http\Resources\Item;
use Illuminate\Http\Resources\Json\Resource;

class ItemResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'name'          => $this->description,
            'user_id'       => $this->user_id,
            'is_completed'  => $this->is_completed,
            'due'           => $this->due,
            'urgency'       => $this->urgency,
            'checklist_id'  => $this->checklist_id,
            'assignee_id'   => $this->assignee_id,
            'task_id'       => $this->task_id,
            'completed_at'  => $this->completed_at,
            'last_update_by'=> $this->updated_by,
            'update_at'     => $this->updated_at,
            'created_at'    => $this->created_at
        ];
    }
}