<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
class Intakes extends REST_Controller {
    function __construct() {
        parent::__construct();
        $this->load->helper(array('html', 'url', 'date', 'form'));
        date_default_timezone_set('Asia/Manila');
        
        $this->load->model( 'Intakes_Model', 'intake' );
    }

    function index_get(){
        $intake = $this->intake->show();

        if ( $intake ) {
            $this->response( array(
                'status'   => TRUE,
                'response' => $intake,
            ), REST_Controller::HTTP_OK );
        }
    }
}