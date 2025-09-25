<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PhpSpreadsheetLoader {
    public function __construct() {
        require_once(APPPATH . 'third_party/PhpSpreadsheet/autoload.php');
    }
}
