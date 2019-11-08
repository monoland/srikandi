<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SegmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'items_count' => $this->items_count,
            'pinned' => false,
            'updated_at' => (string) $this->updated_at,
        ];
    }
}