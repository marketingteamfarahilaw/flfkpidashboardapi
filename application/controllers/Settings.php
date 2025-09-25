<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	require APPPATH . '/libraries/REST_Controller.php';

	class Settings extends REST_Controller {

		public function __construct () {

			header( 'Access-Control-Allow-Origin: *' );
			header( "Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE" );
			parent::__construct();
			date_default_timezone_set( 'Asia/Manila' );
			$this->load->model( 'Settings_model', 'settings' );
		}

		/*
		 * ADD & UPDATE setting INFORMATION
		 *
		 * */
		public function index_post ( $action = "add" ) {
			if ( $this->_validate( $action ) ) {
				$data = array(
					"setting_name"          => $this->post( 'name' ),
					"setting_description"   => $this->post( 'description' ),
					"setting_default_value" => $this->post( 'default_value' ),
				);

				if ( $action == "update" ) {
					$setting = $this->settings->update( array( 'setting_id' => $this->post( 'id' ) ), $data );
				} else {
					$setting = $this->settings->save( $data );
				}
				if ( $setting ) {
					$this->response( array(
						'status'   => TRUE,
						'response' => $setting,
					), REST_Controller::HTTP_OK );
				}
			}

			$this->response( array(
				'status'  => FALSE,
				'message' => 'Error occurred'
			), REST_Controller::HTTP_BAD_REQUEST );

		}

		/*
		 * GET SETTING/S INFORMATION
		 *
		 * */
		public function index_get ( $id = FALSE ) {
			$settings = ($id) ? $this->settings->get_by_id( $id ) : $this->settings->find_all();
			$this->response( array(
				'status'   => TRUE,
				'response' => ($settings) ? $settings : array(),
			), REST_Controller::HTTP_OK );
		}

		/*
		 * DELETE SETTING
		 *
		 * */
		public function delete_post ( $id ) {

			if ( $this->_validate( 'delete' ) ) {
				$setting_found = $this->settings->get_by_id( $id );
				if ( $setting_found ) {
					$setting_career = $this->settings->delete_by_id( $id );
					$this->response( array(
						'status'   => TRUE,
						'response' => $setting_career,
					), REST_Controller::HTTP_OK );
				}

			}
			$this->response( array(
				'status'  => FALSE,
				'message' => 'Settings Not Found'
			), REST_Controller::HTTP_BAD_REQUEST );
		}


		/*
		 * Private Functions
		 *
		 * */
		private function _validate ( $action ) {

			if ( $action !== 'delete' ) {
				$this->form_validation->set_rules( 'name', 'name', 'required' );
				$this->form_validation->set_rules( 'description', 'description', 'required' );
				$this->form_validation->set_rules( 'default_value', 'default_value', 'required' );
			}

			if ( $action !== "add" ) {
				$this->form_validation->set_rules( 'id', 'id', 'required' );
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
