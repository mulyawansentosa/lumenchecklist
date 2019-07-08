<?php
namespace App\Http\Resources\Template;
use Illuminate\Http\Resources\Json\Resource;

class ChecklistResource extends Resource
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
            'description'   => $this->description,
            'due_interval'  => $this->due_interval,
            'due_unit'      => $this->due_unit
        ];
    }
}