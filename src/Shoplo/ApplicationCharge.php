<?php

namespace Shoplo;

class ApplicationCharge extends Resource
{
	/**
	 * @param int   $id
	 * @param array $params
	 * @param bool  $cache
	 *
	 * @return mixed
	 * @throws AuthException
	 * @throws ShoploException
	 */
	public function retrieve($id = 0, $params = array(), $cache = false)
	{
		if ($id == 0) {
			if (!$cache || !isset($this->bucket['application_charge'])) {
				$result                  = $this->send($this->prefix . "application_charges");
				$this->bucket['application_charge'] = $this->prepare_result($result);
			}
			return $this->bucket['application_charge'];
		} else {
			if (!$cache || !isset($this->bucket['product'][$id])) {
				$result                       = $this->send($this->prefix . "/application_charges/" . $id);
				$this->bucket['application_charge'][$id] = $this->prepare_result($result);
			}
			return $this->bucket['application_charge'][$id];
		}
	}

	/**
	 * @param array $params
	 *
	 * @return mixed
	 * @throws AuthException
	 * @throws ShoploException
	 */
	public function count($params = array())
	{
		$params = $this->prepare_params($params);
		return $this->send($this->prefix . "application_charges/count?" . $params);
	}

	/**
	 * @param $fields
	 *
	 * @return mixed
	 * @throws AuthException
	 * @throws ShoploException
	 */
    public function create($fields)
    {
        $fields = array('application_charge' => $fields);
        return $this->send("application_charges", 'POST', $fields);
    }

	/**
	 * @param $id
	 *
	 * @return mixed
	 * @throws AuthException
	 * @throws ShoploException
	 */
	public function delete($id)
	{
		return $this->send("application_charges/" . $id, 'DELETE');
	}

	/**
	 * @param $id
	 *
	 * @return mixed
	 * @throws AuthException
	 * @throws ShoploException
	 */
	public function activate($id)
	{
		return $this->send("application_charges/{$id}/activate", 'POST');
	}

}