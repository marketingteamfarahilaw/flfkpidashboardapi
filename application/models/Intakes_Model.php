<?php

class Intakes_Model extends CI_Model
{
    
    function show() {
		$this->db->join('intake_type b', 'a.type = b.id')
		->from("intakes a");

		$query = $this->db->select('a.date, JSON_ARRAYAGG(JSON_OBJECT("type", b.name, "data", a.data)) AS records')
						->group_by('a.date')
						->order_by('a.date')
						->get();


		$result = $query->result_array();

		// Decode JSON records into PHP array
		foreach ($result as &$row) {
			$row['records'] = json_decode($row['records'], true);
		}

		return $result;
	}
}
