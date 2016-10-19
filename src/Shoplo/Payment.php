<?php
/**
 * Created by PhpStorm.
 * User: adrianadamiec
 * Date: 19.10.2016
 * Time: 12:11
 */

namespace Shoplo;


class Payment extends Resource
{
    public function retrieve($id = 0, $params = array(), $cache = false)
    {
        if ($id == 0) {
            if (!$cache || !isset($this->bucket['payment'])) {
                $params                       = $this->prepare_params($params);
                $result                       = empty($params) ? $this->send($this->prefix . "payment") : $this->send($this->prefix . "payment?" . $params);
                $this->bucket['payment'] = $this->prepare_result($result);
            }
            return $this->bucket['payment'];
        } else {
            if (!$cache || !isset($this->bucket['payment'][$id])) {
                $result                            = $this->send($this->prefix . "/payment/" . $id);
                $this->bucket['payment'][$id] = $this->prepare_result($result);
            }
            return $this->bucket['payment'][$id];
        }
    }

    public function count($params = array())
    {
        $params = $this->prepare_params($params);
        return $this->send($this->prefix . "payment/count" . (!empty($params) ? '?' . $params : ''));
    }
}