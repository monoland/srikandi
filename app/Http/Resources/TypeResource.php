<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TypeResource extends JsonResource
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
            'budgets_count' => $this->budgets_count,
            'vehicles_count' => $this->vehicles_count,
            'pinned' => false,
            'updated_at' => (string) $this->updated_at,
        ];
    }
}