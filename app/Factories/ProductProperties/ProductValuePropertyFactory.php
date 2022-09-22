<?php

namespace App\Factories\ProductProperties;

use App\Models\ParameterValueProduct;

class ProductValuePropertyFactory
{
    public function getPropertyValue($productId, $propertyId)
    {
        $value = ParameterValueProduct::select('id', 'parameter_id', 'product_id', 'parameter_value_id')
            ->where('product_id', $productId)
            ->where('parameter_id', $propertyId)->first();

        if ($value) {
            if ($value->value) {
                if ($value->value->translation) {
                    return $value->value->translation->name;
                } else {
                    return '---';
                }
            } else {
                if ($value->translation) {
                    return $value->translation->value;
                }else {
                    return '---';
                }
            }
        }

        return null;
    }
}