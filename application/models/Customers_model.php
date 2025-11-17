<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	class Customers_model extends MY_Model {
		var $table = 'customers';
		var $id = 'customer_id';

		function login ( $username, $password )
		{
			$userFound = $this->get_by_attribute( 'customer_username', $username );
			if ( $userFound ) {
				$hashed_password = $userFound->customer_password;
				if ( password_verify( $password, $hashed_password ) ) {
					return TRUE;
				}
			}
			return FALSE;
		}
		function user_list()
		{
		  	$this->db->from("$this->table u");

		 	$result = $this->db->select('u.customer_id, u.customer_username, u.customer_first_name, u.customer_last_name, u.customer_email, u.customer_date_registered, u.customer_designation, u.customer_department')
						   ->where('customer_status', 'Active')
		                   ->get();

		  	$data = $result->result_array();
		  	// echo $this->db->last_query(); die();
		  	$result->free_result();

		  	return $data;
		}
		function verifyCustomer ( $data )
		{
			$this->db->set( 'customer_verified', $data['customer_verified'] )
					 ->where( 'customer_id', $data['customer_id'] )
					 ->update( $this->table );

			return TRUE;
		}
		function unverifyCustomer ( $data )
		{
			$this->db->set( 'customer_verified', $data['customer_verified'] )
					 ->where( 'customer_id', $data['customer_id'] )
					 ->update( $this->table );

			return TRUE;
		}
}
