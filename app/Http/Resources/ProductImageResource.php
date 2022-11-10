<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductImageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return string
     */
    public function toArray($request)
    {
        $domain = $request->root();

        return $domain . '/images/products/og/' . $this->src;
    }
}
