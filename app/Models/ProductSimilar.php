<?php

namespace App\Models;

use App\Base as Model;

class ProductSimilar extends Model
{
    protected $table = 'similar_products';

    protected $fillable = ['product_id', 'category_id'];

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
}
