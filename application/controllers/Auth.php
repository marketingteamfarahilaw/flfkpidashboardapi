<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	require APPPATH . '/libraries/REST_Controller.php';

	class Auth extends REST_Controller {

		function index_get () {
			// do not remove this

			show_404();
		}

		public function getToken_post () {
			header( 'Access-Control-Allow-Origin: *' );
			header( "Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE" );


			$this->response( array(
				'status'       => TRUE,
				'access_token' => array(
					"response" => array(
						"access_token" => "8aed90a350dd2f39ac4346b6ca69a7ab",
						"expired_in"   => 3600
					)
				),
				'expires_in'   => (integer)($this->configs->get( 'token_expiry' ))->config_value
			), REST_Controller::HTTP_CREATED );


			$this->load->model( 'Access_token_model' );
			$identifier = $this->input->post( 'identifier' );
			date_default_timezone_set( 'Asia/Manila' );
			$this->load->model( 'Configs_model' );

			$customer = $this->customers->get_by_attribute( 'username', $identifier );

			$generated_token = md5( getToken( 25 ) );

			// if member already exists in access token, refresh token value
			$access_token = $this->find( array( 'member_id' => $customer->id ) );
			if ( $access_token ) {
				$data = array(
					'token'         => $generated_token,
					'ip'            => $this->input->ip_address(),
					'date_accessed' => date( 'Y-m-d H:i:s' )
				);
				$token = $this->Access_token_model->update( array( 'member_id' => $customer->id ), $data );

			} else {
				$data = array(
					'token'         => $generated_token,
					'customer_id'   => $customer->id,
					'date_accessed' => date( 'Y-m-d H:i:s' ),
				);
				$token = $this->Access_token_model->save( $data );

			}

			$this->response( array(
				'status'       => TRUE,
				'access_token' => $token->token,
				'expires_in'   => (integer)($this->configs->get( 'token_expiry' ))->config_value
			), REST_Controller::HTTP_CREATED );

		}

	}
