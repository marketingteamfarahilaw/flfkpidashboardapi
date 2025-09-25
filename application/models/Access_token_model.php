<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Access_token_model extends MY_Model {

	var $table = 'access_token';
	var $id = 'id';

	function get_token ( $identifier ) {
		date_default_timezone_set( 'Asia/Manila' );

		$customer = $this->customers->get_by_attribute( 'customer_username', $identifier );

		$generated_token = md5( getToken( 25 ) );

		// if member already exists in access token, refresh token value
		$access_token = $this->find( array( 'customer_id' => $customer->customer_id ) );

		if ( $access_token ) {
			$data = array(
				'token'         => $generated_token,
				'ip'            => $this->input->ip_address(),
				'date_accessed' => date( 'Y-m-d H:i:s' )
			);
			$token = $this->update( array( 'customer_id' => $customer->customer_id ), $data );

		} else {
			$data = array(
				'token'         => $generated_token,
				'customer_id'   => $customer->customer_id,
				'ip'            => $this->input->ip_address(),
				'date_accessed' => date( 'Y-m-d H:i:s' ),
			);
			$token = $this->save( $data );

		}

		$tokenExp = $this->configs->get_by_attribute( 'config_name', 'token_expiry' );
		return array(
			'access_token' => $token->token,
			'expires_in'   => '18000'//(integer)$tokenExp->config_value
		);

	}

}
