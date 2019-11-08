<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AgencyResource extends JsonResource
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
            'root' => $this->root,
            'vehicles_count' => $this->vehicles_count,
            'services_count' => $this->services_count,
            'users_count' => $this->users_count,
            'pinned' => false,
            'updated_at' => (string) $this->updated_at,
        ];
    }
}