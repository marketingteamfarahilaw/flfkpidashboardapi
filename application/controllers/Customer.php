<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	require APPPATH . '/libraries/REST_Controller.php';

	class Customer extends REST_Controller {

		private $logged_user;

		function __construct ()
    	{
			header( 'Access-Control-Allow-Origin: *' );
			header( "Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE" );
			parent::__construct();
			date_default_timezone_set( 'Asia/Manila' );

			$user = $this->_getUser( ($this->input->get( 'token' )) ? $this->input->get( 'token' ) : $this->input->post( 'token' ) );
			$this->logged_user = $user;

		}

		/*
		 * UPDATE USER INFORMATION
		 *
		 * */
		function saveBase64ImagePng($base64Image)
		{
			// requires php5
			define('UPLOAD_DIR', 'uploads/user/');
			$img = $base64Image;
			$img = str_replace('data:image/png;base64,', '', $img);
			$img = str_replace(' ', '+', $img);
			$data = base64_decode($img);
			$filename = uniqid();
			$file = UPLOAD_DIR . $filename . '.png';
			$success = file_put_contents($file, $data);
			return $filename;
		}

		function index_post ()
    	{
			$user = $this->_getUser( ($this->input->get( 'token' )) );
			$userPassword = $user->customer_password;
			$filename = $this->saveBase64ImagePng( $this->input->post( 'customer_image_url' ) );
			$password = ($this->input->post( 'password' ) == '') ? $userPassword:  password_hash( $this->input->post( 'password' ), PASSWORD_BCRYPT );
			$data = array(
				"customer_email"  => $this->input->post( 'customer_email' ),
				"customer_username"  => $this->input->post( 'username' ),
				"customer_password"  => $password,
				"customer_name"  => $this->input->post( 'customer_name' ),
			);

			$customer = $this->logged_user;
			$updated_customer = $this->customers->update( array( 'customer_id' => $customer->customer_id ), $data );
			if ( $updated_customer ) {
				$this->response( array(
					'status'   => TRUE,
					'response' => $updated_customer,
				), REST_Controller::HTTP_OK );
			}

			$this->response( array(
				'status'  => FALSE,
				'message' => 'Error occurred'
			), REST_Controller::HTTP_BAD_REQUEST );

		}

		function unverify_post ()
  		{
    		$this->load->model('customers_model', 'customer');
  			$data = array(
				"customer_id"  => $this->input->post( 'customer_id' ),
				"customer_verified"  => 'Unverified',
			);

			$updated_customer = $this->customer->unverifyCustomer( $data );
			// pr($updated_customer);die();
			if ( $updated_customer ) {
				$this->response( array(
					'status'   => TRUE,
					'response' => $updated_customer,
				), REST_Controller::HTTP_OK );
			}

			$this->response( array(
				'status'  => FALSE,
				'message' => 'Error occurred'
			), REST_Controller::HTTP_BAD_REQUEST );
		}

		/*
		 * GET USER INFORMATION
		 *
		 * */
		function index_get ()
    	{	
			$username = $this->logged_user->customer_username;
			$member_found = $this->customers->get_by_attribute( 'customer_username', $username );
			if ( $member_found ) {
				unset( $member_found->customer_password );
				$this->response( array(
					'status'   => TRUE,
					'response' => $member_found,
				), REST_Controller::HTTP_OK );
			}
			$this->response( array(
				'status'  => FALSE,
				'message' => 'Customer Not Found'
			), REST_Controller::HTTP_BAD_REQUEST );
		}

	    /*
	     * GET LOOP OF CUSTOMERS
	     *
	     * */
	    function users_get ( )
	    {
			// $user = $this->_getUser( ($this->input->get( 'token' )) );
			$this->load->model( 'Customers_model', 'customer' );
			$data = $this->customer->user_list();
	      	return $this->response( array(
          		'status'   => TRUE,
	         	'response' => $data,
	      	), REST_Controller::HTTP_OK );
	    }

		function user_info_get ($id = null)
		{
			$user = $this->_getUser( ($this->input->get( 'token' )) );
			$customer = ($id) ? $this->customer->get_by_id( $id ) : $this->customer->find_all();
      		$this->response( array(
          		'status'   => TRUE,
          		'response' => ($customer) ? $customer : array(),
      		), REST_Controller::HTTP_OK );
		}
	}