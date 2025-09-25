<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class GmbMetrics extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('GmbMetric_Model'); // CI3 loader
        $this->load->database();
    }

    public function gmbloc_post()
    {
        // Parse JSON body (CI3 way)
        $raw  = $this->input->raw_input_stream;
        $data = json_decode($raw, true);

        if (!is_array($data)) {
            return $this->response([
                'ok'      => false,
                'message' => 'Invalid or empty JSON body.'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }

        // Optional source check
        if (isset($data['source']) && $data['source'] !== 'gmb_metrics') {
            return $this->response([
                'ok'      => false,
                'message' => 'Invalid source.'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }

        $records = isset($data['records']) && is_array($data['records']) ? $data['records'] : null;
        if (!$records) {
            return $this->response([
                'ok'      => false,
                'message' => 'The "records" array is required.'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }

        $okRows   = [];
        $errors   = [];
        $rowIndex = 0;

        foreach ($records as $row) {
            $rowIndex++;

            // Normalize
            $normalized = [
                'date'                   => isset($row['date']) ? trim((string)$row['date']) : null,
                'location'               => trim((string)($row['location'] ?? '')),
                'discovery'              => self::toNullableInt($row['discovery'] ?? null),
                'interactions'           => self::toNullableInt($row['interactions'] ?? null),
                'calls'                  => self::toNullableInt($row['calls'] ?? null),
                'messages'               => self::toNullableInt($row['messages'] ?? null),
                'directions'             => self::toNullableInt($row['directions'] ?? null),
                'website_clicks'         => self::toNullableInt($row['website_clicks'] ?? null),
                'rating'                 => self::toNullableFloat($row['rating'] ?? null),
                'total_reviews'          => self::toNullableInt($row['total_reviews'] ?? null),
                'movement_vs_prev_month' => self::toNullableIntOrDash($row['movement_vs_prev_month'] ?? null),
                'leads'                  => self::toNullableInt($row['leads'] ?? null),
                'signups'                => self::toNullableInt($row['signups'] ?? null),
                'rate'                   => self::toNullableFloat($row['rate'] ?? null), // 0–1 float
            ];

            // Basic validation (manual for CI3)
            $valid =
                !empty($normalized['date']) &&
                preg_match('/^\d{4}-\d{2}-\d{2}$/', $normalized['date']) &&
                !empty($normalized['location']) &&
                strlen($normalized['location']) <= 191 &&
                ($normalized['rating'] === null || is_float($normalized['rating'])) &&
                ($normalized['rate']   === null || is_float($normalized['rate']));

            if (!$valid) {
                $errors[] = [
                    'index'    => $rowIndex,
                    'location' => $normalized['location'] ?: null,
                    'date'     => $normalized['date'] ?: null,
                    'errors'   => ['validation' => 'Failed basic validation.']
                ];
                continue;
            }

            $okRows[] = $normalized;
        }

        if (empty($okRows)) {
            return $this->response([
                'ok'     => false,
                'saved'  => 0,
                'errors' => $errors ?: [['message' => 'No valid rows to insert.']]
            ], REST_Controller::HTTP_BAD_REQUEST);
        }

        // UPSERT on (date, location) using ON DUPLICATE KEY
        // Ensure a UNIQUE KEY (`date`,`location`) exists on your table.
        $this->db->trans_start();
        try {
            foreach ($okRows as $r) {
                $fields = array_keys($r);
                $place  = implode(',', array_fill(0, count($fields), '?'));
                $update = implode(',', array_map(function ($f) { return "`$f`=VALUES(`$f`)"; }, $fields));

                $sql = "INSERT INTO gmb_metrics (`" . implode('`,`', $fields) . "`) VALUES ($place)
                        ON DUPLICATE KEY UPDATE $update";
                $this->db->query($sql, array_values($r));
            }
            $this->db->trans_complete();
        } catch (Throwable $e) {
            $this->db->trans_rollback();
            return $this->response([
                'ok'            => false,
                'message'       => 'Insert failed.',
                'error'         => $e->getMessage(),
                'partial_saved' => 0,
                'errors'        => $errors,
            ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->response([
            'ok'      => true,
            'saved'   => count($okRows),
            'skipped' => count($errors),
            'errors'  => $errors
        ], REST_Controller::HTTP_OK);
    }

    private static function toNullableInt($v)      { if ($v === '' || $v === null) return null; return is_numeric($v) ? (int)$v : null; }
    private static function toNullableFloat($v)    { if ($v === '' || $v === null) return null; return is_numeric($v) ? (float)$v : null; }
    private static function toNullableIntOrDash($v){ if ($v === '' || $v === null || $v === '-') return null; return is_numeric($v) ? (int)$v : null; }
    
    
    function gmbloclist_get(){
        $list = $this->GmbMetric_Model->gmbloc();

        if ( $list ) {
            $this->response( array(
                'status'   => TRUE,
                'response' => $list,
            ), REST_Controller::HTTP_OK );
        }
    }
}
