<?php

namespace Shoplo;

class ProductVariant extends Resource
{
    public function retrieve($productId = 0, $variantId = 0, $params = array(), $cache = false)
    {
        if ($variantId == 0) {
            if (!$cache || !isset($this->bucket['variant'])) {
                $params                  = $this->prepare_params($params);
                $result                  = empty($params) ? $this->send($this->prefix . "/variants/" . $productId) : $this->send($this->prefix . "/variants/" . $productId ."?". $params);
                $this->bucket['variant'] = $this->prepare_result($result);
            }
            return $this->bucket['variant'];
        } elseif ($variantId && $productId == 0) {
            if (!$cache || !isset($this->bucket['variant'][$variantId])) {
                $result                       = $this->send($this->prefix . "/variant/" . $variantId);
                $this->bucket['variant'][$variantId] = $this->prepare_result($result);
            }
            return $this->bucket['variant'][$variantId];
        } else {
            if (!$cache || !isset($this->bucket['variant'][$variantId])) {
                $result                       = $this->send($this->prefix . "/products/" . $productId . "/variants/" . $variantId);
                $this->bucket['variant'][$variantId] = $this->prepare_result($result);
            }
            return $this->bucket['variant'][$variantId];
        }
    }

    public function count($productId, $params = array())
    {
        $params = $this->prepare_params($params);
        return $this->send($this->prefix . "products/{$productId}/variants/count?" . $params);
    }

    public function create($productId, $fields)
    {
        $fields = array('variant' => $fields);
        return $this->send($this->prefix . "/products/{$productId}/variants", 'POST', $fields);
    }

    public function modify($productId, $variantId, $fields)
    {
        $fields = array('variant' => $fields);
        return $this->send($this->prefix . "/products/" . $productId . "/variants/" . $variantId, 'PUT', $fields);
    }

    public function remove($productId, $variantId)
    {
        return $this->send($this->prefix . "/products/{$productId}/variants/{$variantId}/", 'DELETE');
    }
}