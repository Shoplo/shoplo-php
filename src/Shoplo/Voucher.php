<?php

namespace Shoplo;

class Voucher extends Resource
{
    public function retrieve($id = 0, $params = array(), $cache = false)
    {
        if ($id == 0) {
            if (!$cache || !isset($this->bucket['voucher'])) {
                $params                = $this->prepare_params($params);
                $result                = empty($params) ? $this->send($this->prefix . "vouchers") : $this->send($this->prefix . "vouchers?" . $params);
                $this->bucket['voucher'] = $this->prepare_result($result);
            }
            return $this->bucket['voucher'];
        } else {
            if (!$cache || !isset($this->bucket['voucher'][$id])) {
                $result                     = $this->send($this->prefix . "/vouchers/" . $id);
                $this->bucket['voucher'][$id] = $this->prepare_result($result);
            }
            return $this->bucket['voucher'][$id];
        }
    }

    public function count($params = array())
    {
        $params = $this->prepare_params($params);
        return $this->send($this->prefix . "vouchers/count" . (!empty($params) ? '?' . $params : ''));
    }

    public function create($fields)
    {
        $fields = array('voucher' => $fields);
        return $this->send($this->prefix . "vouchers", 'POST', $fields);
    }

    public function modify($id, $fields)
    {
        $fields = array('voucher' => $fields);
        return $this->send($this->prefix . "vouchers/" . $id, 'PUT', $fields);
    }

    public function remove($id)
    {
        return $this->send($this->prefix . "vouchers/" . $id, 'DELETE');
    }
}