<?php
namespace App\Http\Resources\Item;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\Item\ItemCompleteResource;

class ItemCompleteCollection extends ResourceCollection
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
        // return parent::toArray($request);
        return [
            'data'  => $this->collection->transform(function ($item){
                // return $item->only(['field','name']);
                return new ItemCompleteResource($item);
            })
        ];
    }
}