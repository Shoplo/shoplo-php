<?php

namespace Shoplo;

class RecurringApplicationCharge extends Resource
{
	public function retrieve($id = 0, $params = array(), $cache = false)
	{
		if ($id == 0) {
			if (!$cache || !isset($this->bucket['recurring_application_charge'])) {
				$result                  = $this->send($this->prefix . "recurring_application_charges");
				$this->bucket['recurring_application_charge'] = $this->prepare_result($result);
			}
			return $this->bucket['recurring_application_charge'];
		} else {
			if (!$cache || !isset($this->bucket['product'][$id])) {
				$result                       = $this->send($this->prefix . "/recurring_application_charges/" . $id);
				$this->bucket['recurring_application_charge'][$id] = $this->prepare_result($result);
			}
			return $this->bucket['recurring_application_charge'][$id];
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
		return $this->send($this->prefix . "recurring_application_charges/count?" . $params);
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
		$fields = array('recurring_application_charge' => $fields);
		return $this->send("recurring_application_charges", 'POST', $fields);
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
		return $this->send("recurring_application_charges/" . $id, 'DELETE');
	}

	public function activate($id)
	{
		return $this->send("recurring_application_charges/{$id}/activate", 'POST');
	}

}