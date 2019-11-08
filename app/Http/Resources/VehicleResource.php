<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VehicleResource extends JsonResource
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
            'police_id' => optional($this->police)->id,
            'name' => optional($this->police)->name,
            'color' => optional($this->police)->color,
            'taxdue' => optional($this->police)->taxdue,
            'taxsum' => optional($this->police)->taxsum,
            'year' => $this->year,
            'frame_numb' => $this->frame_numb,
            'machine_numb' => $this->machine_numb,
            'refs_numb' => $this->refs_numb,
            'brand_id' => $this->brand_id,
            'type_id' => $this->type_id,
            'agency_id' => $this->agency_id,
            'condition' => $this->condition,
            'pinned' => false,
            'updated_at' => (string) $this->updated_at,
        ];
    }
}