<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $domain = $request->root();
        return [
            'id' => (int)$this->id,
            'name' => (string)$this->translation->name,
            'description' => (string)$this->translation->description,
            'slug' => (string)$this->alias,
            'icon' => $this->image_desktop ? $domain . '/categories/' . $this->image_desktop : null,
            'children' => CategoryResource::collection($this->children),
        ];
    }
}
