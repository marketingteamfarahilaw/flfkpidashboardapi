<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	require APPPATH . '/libraries/REST_Controller.php';

	class Site extends REST_Controller {

		function __construct () {

			header( 'Access-Control-Allow-Origin: *' );
			header( "Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE" );
			parent::__construct();
			date_default_timezone_set( 'Asia/Manila' );

			$this->load->library('mailer');
		}

		function index_get () {
			// do not remove this
			show_404();
		}

		function login_post ()
		{
			if ( $this->_validate( 'login' ) ) {
				$username = $this->input->post( 'username' );
				$password = $this->input->post( 'password' );

				$response = $this->customers->login( $username, $password );

				if ( $response ){
					$member_found = $this->customers->get_by_attribute( 'customer_username', $username );

					if ( $member_found ) {
						unset( $member_found->customer_password );
						$this->response( array(
							'status'   => TRUE,
							'response' => $member_found,
							'token'    => $this->auth->get_token( $username )
						), REST_Controller::HTTP_OK );
					}
				}
			}
			$this->response( array(
				'status'  => FALSE,
				'message' => 'Incorrect Credentials'
			), REST_Controller::HTTP_BAD_REQUEST );
		}

		function login_get ()
		{
			$user = $this->_getUser( ($this->input->get( 'token' )) );
			if ( $user ) {
				$this->response( array(
					'status'   => TRUE,
					'response' => $user
				), REST_Controller::HTTP_OK );
			}
		}

		function logout_post ()
		{
			if ( $this->_validate( 'logout' ) ) {
				$this->auth->delete_where( array( 'token' => $this->input->post( 'token' ) ) );
				$this->response( array(
					'status'   => TRUE,
					'response' => 'User successfully logs out.'
				), REST_Controller::HTTP_OK );
			}
			$this->response( array(
				'status'  => FALSE,
				'message' => 'Error occurred'
			), REST_Controller::HTTP_INTERNAL_SERVER_ERROR );
		}

		function register_post ()
		{
			if ( $this->_validate( 'register' ) ) {
				$data = array(
					"customer_username"        	=> $this->input->post( 'customer_username' ),
					"customer_password"        	=> password_hash( $this->input->post( 'password' ), PASSWORD_BCRYPT ),
					"customer_first_name"      	=> $this->input->post( 'customer_first_name' ),
					"customer_last_name"      	=> $this->input->post( 'customer_last_name' ),
					"customer_email"      		=> $this->input->post( 'customer_email' ),
					"customer_designation"      => $this->input->post( 'customer_designation' ),
					"customer_department"      	=> $this->input->post( 'customer_department' ),
					"customers_level"       	=> $this->input->post( 'customers_level' ),
					"customer_date_registered" 	=> date( 'Y-m-d H:i:s' ),
					"customer_verified" 		=> 'Verified',
				);
				$new_customer = $this->customers->save( $data );
				$this->response( array(
					'status'   => TRUE,
					'response' => $new_customer,
				), REST_Controller::HTTP_OK );
			}
			
			$this->response( array(
				'status'  => FALSE,
				'message' => 'Error occurred'
			), REST_Controller::HTTP_BAD_REQUEST );
		}

		function _validate ( $action = "login" )
		{
			if ( $action == "login" ) {
				$this->form_validation->set_rules( 'username', 'username', 'strip_tags|trim|required' );
				$this->form_validation->set_rules( 'password', 'password', 'strip_tags|trim|required' );
			} else if ( $action == "logout" ) {
				$this->form_validation->set_rules( 'token', 'token', 'required' );
			} else if ( $action == "register" ) {
				$this->form_validation->set_rules( 'customer_first_name', 'firstname', 'required' );
				$this->form_validation->set_rules( 'customer_last_name', 'lastname', 'required' );
				$this->form_validation->set_rules( 'customer_username', 'username', 'strip_tags|trim|required|is_unique[customers.customer_username]' );
				$this->form_validation->set_rules( 'customer_email', 'email', 'strip_tags|trim|required|is_unique[customers.customer_email]' );
				$this->form_validation->set_rules( 'customer_password', 'password', 'strip_tags|trim|required' );
			} 
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
