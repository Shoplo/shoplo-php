<?php

namespace Shoplo;

class ProductVariant extends Resource
{
    public function retrieve($productId, $variantId = 0, $params = array(), $cache = false)
    {
        if ($variantId == 0) {
            if (!$cache || !isset($this->bucket['variant'])) {
                $params                  = $this->prepare_params($params);
                $result                  = empty($params) ? $this->send($this->prefix . "/variants/" . $productId) : $this->send($this->prefix . "/variants/" . $productId ."?". $params);
                $this->bucket['variant'] = $this->prepare_result($result);
            }
            return $this->bucket['variant'];
        } else {
            if (!$cache || !isset($this->bucket['variant'][$variantId])) {
                $result                       = $this->send($this->prefix . "/products/" . $productId . "/variants/" . $variantId);
                $this->bucket['variant'][$variantId] = $this->prepare_result($result);
            }
            return $this->bucket['variant'][$variantId];
        }
    }

    public function modify($id, $fields)
    {
        $fields = array('variant' => $fields);
        return $this->send($this->prefix . "/variants/" . $id, 'POST', $fields);
    }
}