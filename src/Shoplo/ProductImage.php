<?php

namespace Shoplo;

class ProductImage extends Resource
{
    public function remove($productId, $imageId)
    {
        return $this->send("products/" . $productId . "/images/" . $imageId, 'DELETE');
    }
}