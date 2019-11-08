<?php

namespace App\Http\Resources;

use App\Models\Control;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceDetail extends JsonResource
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
            $this->mergeWhen($this->pivot, [
                'value' => $this->id,
                'text' => $this->pivot->name,
                'unit' => $this->pivot->unit,
                'used' => $this->pivot->used,
                'blnc' => $this->pivot->blnc,
                'aprv' => $this->pivot->aprv,
                'exmn' => $this->pivot->exmn,
            ])
        ];
    }
}
