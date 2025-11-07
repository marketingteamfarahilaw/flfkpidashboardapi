<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

// require_once APPPATH . 'libraries/OpenAIWrapper.php';
class Asana extends REST_Controller {
    function __construct()
    {
        parent::__construct();
        // If you use CSRF, consider excluding this endpoint in config or use token in header.
        header('Content-Type: application/json; charset=utf-8');
        $this->load->model('Asana_Model', 'asana');
        $this->load->helper(['security','date']);
    }
    function asanaSync_post() {
        // Only allow POST
        if (strtoupper($_SERVER['REQUEST_METHOD']) !== 'POST') {
            http_response_code(405);
            echo json_encode(['ok' => false, 'error' => 'Method Not Allowed']);
            return;
        }

        $raw = file_get_contents('php://input');
        if (!$raw) {
            echo json_encode(['ok' => false, 'error' => 'Empty body']);
            return;
        }

        $payload = json_decode($raw, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode(['ok' => false, 'error' => 'Invalid JSON']);
            return;
        }

        // Normalize to array
        $items = isset($payload[0]) ? $payload : [$payload];

        // Optional: sanitize/normalize each item
        $items = array_map(function($x){
            // Trim strings
            foreach ($x as $k => $v) {
                if (is_string($v)) $x[$k] = trim($v);
            }
            // Normalize booleans in `completed` to 'true'/'false' for your SET column
            if (isset($x['completed'])) {
                $val = strtolower((string)$x['completed']);
                $x['completed'] = in_array($val, ['1','true','yes','y'], true) ? 'true' : 'false';
            }
            // Ensure ints where appropriate
            if (isset($x['parent_id']))    $x['parent_id']    = (int)$x['parent_id'];
            if (isset($x['workspace_id'])) $x['workspace_id'] = (int)$x['workspace_id'];
            return $x;
        }, $items);

        $useWorkspaceScope = (strtolower($this->input->get('scope')) === 'workspace');

        try {
            $summary = $this->asana->bulk_upsert($items, $useWorkspaceScope);
            echo json_encode(['ok' => $summary['ok'], 'summary' => $summary], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $e) {
            http_response_code(500);
            echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
        }
    }

}