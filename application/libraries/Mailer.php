<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class Mailer {

		function __construct () {
			$CI =& get_instance();

			$CI->load->library( 'email' );

			$config['protocol'] = 'smtp';
	        $config['smtp_host'] = 'ssl://smtp.gmail.com';
	        // to be changed 
	        $config['smtp_user'] = 'junrel.devocion@ictv.ph'; 
	        $config['smtp_pass'] = 'junrelko';
	        $config['smtp_port'] = 465;
	        $config['charset'] = "utf-8";
	        $config['mailtype'] = "html";
	        $config['newline'] = "\r\n";

			$CI->email->initialize( $config );
		}

		function send_mail ( $data ) {
			$CI =& get_instance();

			$CI->email->from( $data['from'], $data['fromName'] );
			$CI->email->to( $data['to'] );

			$CI->email->subject( $data['subject'] );
			$CI->email->message( $data['message'] );

			$CI->email->send();
		}
	}
