<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $parameters = ParameterResource::collection($this->parameters);
        $properties = $this->parseProperties($parameters);
        $services = SimilarProductResource::collection($this->similarCollections);
        $reviews = ProductReviewResource::collection($this->similarBrands);
        $domain = $request->root();


        $data = [
            'id' => (int)$this->id,
            'categoryId' => (int)$this->category_id,
            'slug' => (string)$this->alias,
            'name' => (string)$this->translation->name,
            'description' => (string)$this->translation->description,
            'rating' => (string)$this->translation->atributes,
            'available' => (bool)$this->homewear,
            'lastReview' => $this->updated_at->diffForHumans(),
            'memberSince' => $this->created_at->format('M Y'),
            'price' => (string)$this->mainPrice->price,
            'image' => $this->mainImage ? $domain . '/images/products/og/' .$this->mainImage->src : null,
            'video' => $this->video ?? null,
        ];

        $data = array_merge($data, $properties);
        $data['services'] = $services;
        $data['reviews'] = $reviews;
        return $data;
    }

    public function parseProperties($parameters)
    {
        $result = [];
        if ($parameters) {
            $properties = collect($parameters)->toArray();
            $result = [];
            foreach ($properties as $propertyArray) {
                foreach ($propertyArray as $key => $prop) {
                    $result[$key] = $prop;
                }
            }
        }
        return $result;
    }

}
