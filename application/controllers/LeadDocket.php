<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LeadDocket extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('LeadDocket_Model');
        $this->load->database();
    }

    public function upload() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->output->set_status_header(405)->set_output(json_encode(['error' => 'Only POST allowed']));
        }

        if (!isset($_FILES['file'])) {
            return $this->output->set_status_header(400)->set_output(json_encode(['error' => 'No file uploaded']));
        }

        require_once APPPATH . 'third_party/PHPExcel/Classes/PHPExcel.php';

        $filePath = $_FILES['file']['tmp_name'];
        $excel = PHPExcel_IOFactory::load($filePath);
        $sheet = $excel->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true);

        $headers = array_map('trim', $rows[1]);
        unset($rows[1]);

        $inserted = 0;
        $updated = 0;

        foreach ($rows as $row) {
            $data = array_combine($headers, $row);
            if (!empty($data['Full Name'])) {
                $result = $this->LeadDocket_Model->saveOrUpdate($data);
                if ($result === 'insert') $inserted++;
                if ($result === 'update') $updated++;
            }
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'inserted' => $inserted,
                'updated' => $updated,
                'message' => "Inserted $inserted and updated $updated records."
            ]));
    }
}
