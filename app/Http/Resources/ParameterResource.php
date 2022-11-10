<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ParameterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $value = null;

        if ($this->value) {
            $value = $this->value->translation->name;
        } elseif ($this->translation) {
            $value = $this->translation->value;
        }

        return [
             $this->parameter->translation->name => $value
        ];

    }
}
