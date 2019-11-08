<?php

namespace App\Http\Resources;

use App\Models\Authent;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return UserResource::collection($this->collection);
    }

    /**
     * Get additional data that should be returned with the resource array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function with($request)
    {
        return [
            'additional' => [
                'combos' => [
                    'authents' => $request->agency ? 
                        Authent::fetchCombo()->whereNotIn('name', ['administrator', 'bengkel'])->get() :
                        Authent::fetchCombo()->whereIn('name', ['bengkel'])->get(),
                ],

                'info' => $request->agency ? optional($request->agency)->name : optional($request->garage)->name,
            ],
        ];
    }
}
