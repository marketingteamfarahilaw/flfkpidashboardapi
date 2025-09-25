<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	require APPPATH . '/libraries/REST_Controller.php';

	class Commons extends REST_Controller {

		public function __construct () {

			header( 'Access-Control-Allow-Origin: *' );
			header( "Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE" );
			parent::__construct();
			date_default_timezone_set( 'Asia/Manila' );
		}

		function index_get () {
			// do not remove this
			show_404();
		}

		public function customers_get ( $id = FALSE ) {
			$customers = ($id) ? $this->customers->get_by_id( $id ) : $this->customers->find_all();

			if ( $customers ) unset( $customers->customer_password );
			$this->response( array(
				'status'   => TRUE,
				'response' => ($customers) ? $customers : array(),
			), REST_Controller::HTTP_OK );

		}

		private function _validate ( $action = "" ) {

			$this->form_validation->set_rules( 'username', 'username', 'strip_tags|trim|required' );

			$this->form_validation->set_error_delimiters( '', '' );
			if ( $this->form_validation->run( $this ) == FALSE ) {
				$this->response( array(
					'status'  => FALSE,
					'message' => $this->form_validation->error_array()
				), REST_Controller::HTTP_BAD_REQUEST );
			} else {
				return TRUE;
			}
		}

	}
