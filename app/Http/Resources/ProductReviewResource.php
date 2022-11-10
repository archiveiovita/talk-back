<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductReviewResource extends JsonResource
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
            'author' => $this->translation->name,
            'profession' => $this->translation->seo_title,
            'image' => $this->logo ? $domain.'/images/brands/'.$this->logo : null,
            'review' => strip_tags($this->translation->description),
        ];
    }
}
