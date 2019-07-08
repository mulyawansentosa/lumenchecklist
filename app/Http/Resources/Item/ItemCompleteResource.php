<?php
namespace App\Http\Resources\Item;
use Illuminate\Http\Resources\Json\Resource;

class ItemCompleteResource extends Resource
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
            'item_id'       => $this->id,
            'is_completed'  => $this->is_completed,
            'checklist_id'  => $this->checklist_id
        ];
    }
}