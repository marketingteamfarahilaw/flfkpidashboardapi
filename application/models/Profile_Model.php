<?php
class Profile_Model extends CI_Model
{

    function update_profile($data, $customer_id) {
        $this->db->where('customer_id',$customer_id);
		$query = $this->db->update('customers', $data);
		return $customer_id;
    }
}