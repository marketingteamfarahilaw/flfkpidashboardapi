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
        // Method guard
        if (strtoupper($_SERVER['REQUEST_METHOD']) !== 'POST') {
            http_response_code(405);
            echo json_encode(['ok' => false, 'error' => 'Method Not Allowed']);
            return;
        }

        // Read body
        $raw = file_get_contents('php://input');
        if (!$raw) {
            echo json_encode(['ok' => false, 'error' => 'Empty body']);
            return;
        }

        // Decode JSON
        $payload = json_decode($raw, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode(['ok' => false, 'error' => 'Invalid JSON']);
            return;
        }

        // Normalize to array
        $items = isset($payload[0]) ? $payload : [$payload];

        // Sanitize / normalize each item
        $items = array_map(function($x){
            if (!is_array($x)) $x = [];

            // Trim strings
            foreach ($x as $k => $v) {
                if (is_string($v)) $x[$k] = trim($v);
            }

            // Normalize booleans in `completed` to 'true' / 'false'
            if (array_key_exists('completed', $x)) {
                $val = strtolower((string)($x['completed'] ?? ''));
                $x['completed'] = in_array($val, ['1','true','yes','y'], true) ? 'true' : 'false';
            }

            // Ensure ints where appropriate
            if (isset($x['parent_id']))    $x['parent_id']    = (int)$x['parent_id'];
            if (isset($x['workspace_id'])) $x['workspace_id'] = (int)$x['workspace_id'];

            // Optional: coerce numeric fields you added
            if (isset($x['output_count']) && $x['output_count'] !== '') {
                $x['output_count'] = (int)$x['output_count'];
            }
            if (isset($x['time_minutes']) && $x['time_minutes'] !== '') {
                $x['time_minutes'] = (int)$x['time_minutes'];
            }

            // Empty strings to NULL for nicer SQL updates
            foreach (['brand','task_type','notes','permalink_url','parent_name','workspace_name','due_on','completed_at','title'] as $k) {
                if (array_key_exists($k, $x) && $x[$k] === '') $x[$k] = NULL;
            }

            return $x;
        }, $items);

        // NULL-SAFE scope handling (fixes PHP 8.1 deprecation)
        $scope = $this->input->get('scope', true);
        if (!is_string($scope)) { $scope = ''; }
        $useWorkspaceScope = (strcasecmp($scope, 'workspace') === 0);

        try {
            $summary = $this->asana->bulk_upsert($items, $useWorkspaceScope);
            echo json_encode(['ok' => $summary['ok'], 'summary' => $summary], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $e) {
            http_response_code(500);
            echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
        }
    }
}
