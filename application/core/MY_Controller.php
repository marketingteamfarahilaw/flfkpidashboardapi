<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class MY_Controller extends CI_Controller {


		public function _isUserLoggedIn () {

			if ( $this->_isLoggedIn() ) {
				// do nothing
			} else {
				redirect( 'login' );
			}
		}

		public function _isLoggedIn () {

			$is_logged_in = $this->session->userdata( 'is_logged_in' );
			return !isset( $is_logged_in ) || $is_logged_in != TRUE ? FALSE : TRUE;

		}

		public function _success ( $data = NULL ) {
			if ( $data == NULL ) {
				$data = array( 'success' => 'true' );
			}
			$this->output->set_content_type( 'application/json' )->set_output( json_encode( $data ) )->set_status_header( 200 );
		}

		public function _validation_error ( $error = NULL ) {
			if ( $error == NULL ) {
				$error = "Something went wrong. Please try again.";
			}
			$this->output->set_content_type( 'application/json' )->set_output( json_encode( array(
				'error'             => 'validation_error',
				'error_description' => $error
			) ) )->set_status_header( 400 );
		}

		function _send_email ( $member, $details = array(), $action = 'send_verification' ) {

			$this->load->library( 'Mailer' );
			$data = array();
			$data['from'] = $this->configs->get( 'mail_sender' );
			$data['fromName'] = $this->configs->get( 'mail_sender_name' );
			$data['to'] = $member->email;
			if ( $action == 'send_coupon' ) {
				$data['subject'] = 'First Login Bonus';
				$data['couponDetails'] = $details;
				$data['message'] = $this->load->view( 'email_coupon_bonus', $data, TRUE );
			} else if ( $action == 'item_redemption' ) {
				$data['subject'] = 'Item Redemption';
				$data['itemDetails'] = $details;
				$data['message'] = $this->load->view( 'email_item_redemption', $data, TRUE );
			} else if ( $action == 'airtime_redemption' ) {
				$data['subject'] = 'Airtime Redemption';
				$data['airtimeDetails'] = $details;
				$data['message'] = $this->load->view( 'email_airtime_redemption', $data, TRUE );
			} else if ( $action == 'raffle_winner' ) {
                $data['subject'] = 'Raffle Winner';
                $data['product_name'] = $details['name'];
                $data['image'] = $details['image'];
                $data['raffle_name'] = $details['raffle_name'];
                $data['account'] = $details['account'];
                $data['full_name'] = $details['full_name'];
                $data['message'] = $this->load->view( 'email_raffle_winner', $data, TRUE );
                pr($data);
            } else {
				$data['subject'] = 'Email Verification';
				$verificationEndpoint = $this->configs->get( 'verificationEndpoint' );
				$verificationToken = $this->_generate_verification_token( $member->account_id );
				$data['verificationLink'] = $verificationEndpoint . $verificationToken;
				$data['message'] = $this->load->view( 'email_activation_link', $data, TRUE );
				pr($data);
			}  

			$this->mailer->send_mail( $data );
		}

		function _generate_verification_token ( $account_id = '' ) {

			$this->load->library( 'encryption' );
			$this->encryption->initialize( array(
				'driver' => 'openssl',
				'cipher' => 'aes-256',
				'mode'   => 'ctr',
				'key'    => $this->configs->get( 'encryption_key' )
			) );

			$delimiter = $this->configs->get( 'encryption_delimiter' );
			$plain_text = $account_id . $delimiter . 'azusabren';
			$ciphertext = urlencode($this->encryption->encrypt( $plain_text ));
			return $ciphertext;
		}

		function _check_date_is_within_range ( $start_date, $end_date, $current_date = "now" ) {
			date_default_timezone_set( 'Asia/Manila' );
			$start_timestamp = is_int( $start_date ) ? $start_date : strtotime( $start_date );
			$end_timestamp = is_int( $end_date ) ? $end_date : strtotime( $end_date );
			$current_timestamp = is_int( $current_date ) ? $current_date : strtotime( "now" );
			return $current_timestamp >= $start_timestamp && $current_timestamp <= $end_timestamp;
		}
	}
