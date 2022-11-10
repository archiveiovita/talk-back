<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SimilarProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $domain = $request->root();

        return [
            'name' => $this->translation->name,
            'image' => $this->banner ? $domain.'/images/collections/og/'. $this->banner : null,
        ];
    }
}
