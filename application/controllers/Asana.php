<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Asana extends REST_Controller {
    public function __construct()
    {
        parent::__construct();
        header('Content-Type: application/json; charset=utf-8');
        $this->load->model('Asana_Model', 'asana');
        $this->load->helper(['security','date']);
    }

    public function asanaSync_post()
    {
        // ===== Guard HTTP method =====
        if (strtoupper($_SERVER['REQUEST_METHOD']) !== 'POST') {
            $this->output
                ->set_status_header(405)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode(['ok' => false, 'error' => 'Method Not Allowed']));
            return;
        }

        // ===== Kill buffers & compression (avoid incorrect header check) =====
        while (ob_get_level() > 0) { @ob_end_clean(); }
        if (function_exists('ini_set')) { @ini_set('zlib.output_compression', '0'); }
        if (function_exists('header_remove')) { @header_remove('Content-Encoding'); }

        // ===== Read raw JSON safely =====
        // Prefer CI's raw_input_stream to avoid double-reading php://input
        $raw = $this->input->raw_input_stream;
        if ($raw === null || $raw === '') {
            $this->output
                ->set_status_header(400)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode(['ok' => false, 'error' => 'Empty body']));
            return;
        }

        // ===== Decode JSON (keep big ints as strings to avoid truncation) =====
        $payload = json_decode($raw, true, 512, JSON_BIGINT_AS_STRING);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->output
                ->set_status_header(400)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode(['ok' => false, 'error' => 'Invalid JSON']));
            return;
        }

        // ===== Normalize to array of items =====
        // If payload is an object (assoc), wrap it; if it's already a list, keep it.
        $items = (is_array($payload) && array_keys($payload) === range(0, count($payload)-1))
            ? $payload
            : [$payload];

        // ===== Field normalization rules =====
        // NOTE: We preserve zero (0) for numeric fields. Empty strings -> NULL (optional).
        $normalize = function(array $x) {
            // Trim strings
            foreach ($x as $k => $v) {
                if (is_string($v)) { $x[$k] = trim($v); }
            }

            // completed → 1/0 (accept bool, "true"/"false", "1"/"0", yes/no)
            if (array_key_exists('completed', $x)) {
                $v = $x['completed'];
                if (is_bool($v)) {
                    $x['completed'] = $v ? 1 : 0;
                } else {
                    $vv = strtolower((string)$v);
                    $x['completed'] = in_array($vv, ['1','true','yes','y'], true) ? 1 : 0;
                }
            }

            // Integers: preserve 0 (only cast if not null and not '')
            foreach (['parent_id','workspace_id','output_count','time_minutes'] as $k) {
                if (array_key_exists($k, $x) && $x[$k] !== '' && $x[$k] !== null) {
                    // Allow numeric strings; cast safely
                    if (is_numeric($x[$k])) { $x[$k] = (int)$x[$k]; }
                }
            }

            // Empty strings -> NULL for selected text/date fields (OPTIONAL: comment out if you want to keep empty strings)
            foreach ([
                'brand','task_type','notes','permalink_url','parent_name','workspace_name',
                'due_on','completed_at','title','performed_by'
            ] as $k) {
                if (array_key_exists($k, $x) && $x[$k] === '') { $x[$k] = null; }
            }

            // Optional: date normalization (YYYY-MM-DD only); leave as-is if already good.
            // if (!empty($x['due_on'])) { $x['due_on'] = date('Y-m-d', strtotime($x['due_on'])); }
            // if (!empty($x['completed_at'])) { $x['completed_at'] = date('Y-m-d H:i:s', strtotime($x['completed_at'])); }

            return $x;
        };

        $items = array_map(function($it) use ($normalize) {
            return is_array($it) ? $normalize($it) : [];
        }, $items);

        // ===== Scope param (safe) =====
        $scope = $this->input->get('scope', true);
        if (!is_string($scope)) { $scope = ''; }
        $useWorkspaceScope = (strcasecmp($scope, 'workspace') === 0);

        // ===== Perform upsert in model =====
        try {
            // EXPECTATION: bulk_upsert($items, $useWorkspaceScope) must accept/propagate:
            //  - brand, task_type, output_count, time_minutes, performed_by, title, notes,
            //    parent_id, parent_name, permalink_url, completed_at, due_on, completed,
            //    workspace_id, workspace_name, task_id (if present).
            //
            // If these 4 fields were not updating before, ensure your model uses them in:
            //  - INSERT ... (brand, task_type, output_count, time_minutes, ...)
            //  - ON DUPLICATE KEY UPDATE brand=VALUES(brand), task_type=VALUES(task_type),
            //    output_count=VALUES(output_count), time_minutes=VALUES(time_minutes), ...
            //
            $summary = $this->asana->bulk_upsert($items, $useWorkspaceScope);

            // Build response
            $resp = [
                'ok'      => isset($summary['ok']) ? (bool)$summary['ok'] : true,
                'summary' => $summary
            ];

            // Output clean JSON
            $this->output
                ->set_status_header(200)
                ->set_content_type('application/json', 'utf-8')
                ->set_header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0')
                ->set_header('Pragma: no-cache')
                ->set_header('X-Accel-Buffering: no')
                ->set_output(json_encode($resp, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            return;

        } catch (\Throwable $e) {
            $this->output
                ->set_status_header(500)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode(['ok' => false, 'error' => $e->getMessage()]));
            return;
        }
    }

}
