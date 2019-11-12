<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
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
            'vehicle' => [
                'agency_id' => optional($this->agency)->id,
                'police_id' => optional($this->police)->id,
                'text' => optional($this->vehicle)->kind . ' | ' . optional($this->police)->id . ' - ' . optional($this->agency)->name . ' a/n ' . optional($this->police)->name,
                'value' => optional($this->vehicle)->id,
                'name' => optional($this->police)->name,
                'brand' => optional(optional($this->vehicle)->brand)->name,
                'type' => optional(optional($this->vehicle)->type)->name,
            ],
            $this->mergeWhen(optional($this->garage)->id, [
                'garage' => [
                    'value' => optional($this->garage)->id,
                    'text' => optional($this->garage)->name,
                    'address' => optional($this->garage)->address,
                    'phone' => optional($this->garage)->phone,
                ]
            ]),
            'police_id' => optional($this->vehicle)->kind . ' | ' . optional($this->police)->id . ' a/n ' . optional($this->police)->name,
            'periode' => $this->periode,
            'year' => $this->year,
            'notes' => $this->notes,
            'status' => $this->status,
            'details' => ServiceDetail::collection($this->items),

            'inv_id' => optional($this->invoice)->id,
            'inv_refsinv' => optional($this->invoice)->refsinv,
            'inv_dateinv' => optional($this->invoice)->dateinv,
            'inv_details' => optional($this->invoice)->items ? InvoiceDetail::collection($this->invoice->items) : [],
            'inv_subtotal' => optional($this->invoice)->subtotal ?: 0,
            'inv_discount' => optional($this->invoice)->discount ?: 0,
            'inv_tax' => optional($this->invoice)->tax ?: 0,
            'inv_total' => optional($this->invoice)->total ?: 0,
            
            'pinned' => false,
            'updated_at' => (string) $this->updated_at,
        ];
    }
}