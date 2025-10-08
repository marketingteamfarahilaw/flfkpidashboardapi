<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

// require_once APPPATH . 'libraries/OpenAIWrapper.php';
class Kpi extends REST_Controller {
    function __construct() {
        parent::__construct();
        $this->load->helper(array('html', 'url', 'date', 'form'));
        date_default_timezone_set('Asia/Manila');
        
        $this->load->model( 'Kpi_Model', 'kpi' );
        
        
        // $this->config->load('openai', TRUE);
    }

    function index_get () {
        // do not remove this
        show_404();
    }

    function show_get(){
        $kpi = $this->kpi->show();

        // if ( $kpi ) {
        //     $this->response( array(
        //         'status'   => TRUE,
        //         'response' => $kpi,
        //     ), REST_Controller::HTTP_OK );
        // }
        
        if ($kpi) {
            
            // $apiKey = $this->config->item('openai_api_key', 'openai');
            // // Get start and end of current week (Monday to Sunday)
            // $startOfLastWeek = date('Y-m-d', strtotime('monday last week'));
            // $endOfLastWeek   = date('Y-m-d', strtotime('sunday last week'));
            
            // $startOfLastMonth = date('Y-m-01', strtotime('first day of last month'));
            // $endOfLastMonth = date('Y-m-t', strtotime('last day of last month'));
            
            // // $prompt = "Generate a summary for the EOW tasks for the month ($startOfWeek to $endOfWeek):\n";
            
            // $promptEOW = "Generate a summary for the last week ($startOfLastWeek to $endOfLastWeek):\n";
            
            
            // $promptEOM = "'Generate a summary for the month ($startOfLastMonth to $endOfLastMonth):\n";
            
            // foreach ($kpi as $item) {
            //     $date = $item['submission_date'];
            
            //     // Include only entries from the current week
            //     if ($date >= $startOfLastWeek && $date <= $endOfLastWeek) {
            //         $promptEOW .= sprintf(
            //             "- [%s] (%s) performed by %s: %s (Link: %s)\n",
            //             $item['submission_date'],
            //             $item['brand'],
            //             // $item['performed_by'],
            //             $item['report'],
            //             $item['link'],
            //             $item['status']
            //         );
            //     }
            // }
            
            // foreach ($kpi as $item) {
            //     $date = $item['submission_date'];
            //     if ($date >= $startOfLastMonth && $date <= $endOfLastMonth) {
            //         $promptEOM .= sprintf(
            //             "- [%s] (%s) performed by %s: %s (Link: %s)\n",
            //             $item['submission_date'],
            //             $item['brand'],
            //             // $item['performed_by'],
            //             $item['report'],
            //             $item['link'],
            //             $item['status']
            //         );
            //     }
            // }
    
            // // Initialize OpenAI
            // $openai = new OpenAIWrapper($apiKey);
            // $client = $openai->getClient();
        
            // try {
                
            //     $responseEOW = $client->chat()->create([
            //         'model' => 'gpt-4-turbo',
            //         'messages' => [
            //             ['role' => 'system', 'content' => 'You are an expert at summarizing KPI tasks concisely and insightfully. you want to have a brief and in paragraph format. make it longer and concisely. Giving percentage on each project can be more help. for the conclusion I want to make the response to be more general or it is focused on team collaborative and not each members achievements.'],
            //             ['role' => 'user', 'content' => $promptEOW],
            //         ],
            //         'max_tokens' => 600,
            //         'temperature' => 1
            //     ]);
                
            //     $responseEOM = $client->chat()->create([
            //         'model' => 'gpt-4-turbo',
            //         'messages' => [
            //             ['role' => 'system', 'content' => 'You are an expert at summarizing KPI tasks concisely and insightfully. you want to have a brief and in paragraph format. make it longer and concisely. Giving percentage on each project can be more help. for the conclusion I want to make the response to be more general or it is focused on team collaborative and not each members achievements.'],
            //             ['role' => 'user', 'content' => $promptEOM],
            //         ],
            //         'max_tokens' => 600,
            //         'temperature' => 1
            //     ]);
            //     $EOWconc = trim($responseEOW->choices[0]->message->content);
            //     $EOMconc = trim($responseEOM->choices[0]->message->content);
            // } 
            
            // catch (Exception $e) {
            //     log_message('error', 'OpenAI API Error: ' . $e->getMessage());
            //     $EOWconc = "Error generating conclusion.";
            //     $EOMconc = "Error generating conclusion.";
            // }
    
            // Final structured response
            $this->response([
                'status' => TRUE,
                'response' => $kpi,
                // 'conclusionEOW' => $EOWconc,
                // 'conclusionEOM' => $EOMconc
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => FALSE,
                'message' => 'No KPI data available'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

//     function add_post(){
//         $data = array(
// 			'submission_date'   => $this->input->post('submission_date'),
// 			'report'            => $this->input->post('report'),
// 			'brand'             => $this->input->post('brand'),
// 			'performed_by'      => $this->input->post('performed_by'),
// 			'date'              => $this->input->post('date'),
// 			'link'              => $this->input->post('link'),
// 			'title'             => $this->input->post('title'),
// 			'article_type'      => $this->input->post('article_type'),
// 			'note'              => $this->input->post('note'),
// 			'platform'          => $this->input->post('platform'),
// 			'language'          => $this->input->post('language'),
// 			'indexed'          => $this->input->post('indexed'),
// 		);

// 		$kpi = $this->kpi->save( $data );

//         $this->response( array(
//             'status'   => TRUE,
//             'response' => $kpi,
//         ), REST_Controller::HTTP_OK );
//     }

    function add_post(){
        $data = array(
			'submission_date'   => $this->input->post('submission_date'),
			'report'            => $this->input->post('report'),
			'brand'             => $this->input->post('brand'),
			'performed_by'      => $this->input->post('performed_by'),
			'date'              => $this->input->post('date'),
			'link'              => $this->input->post('link'),
			'title'             => $this->input->post('title'),
			'article_type'      => $this->input->post('article_type'),
			'note'              => $this->input->post('note'),
			'platform'          => $this->input->post('platform'),
			'language'          => $this->input->post('language'),
			'indexed'          => $this->input->post('indexed'),
		);

		$kpi = $this->kpi->savekpi( $data );

        $this->response( array(
            'status'   => TRUE,
            'response' => $kpi,
        ), REST_Controller::HTTP_OK );
    }
    
    
    function add_gbp_post() {
        // Grab payload
        $submission_date = $this->input->post('submission_date'); // e.g., '2025-08-19 10:00:00'
        $report          = $this->input->post('report');          // e.g., 'GBP Post'
        $brand           = $this->input->post('brand');           // e.g., from "Brand"
        $performed_by    = $this->input->post('performed_by');    // e.g., from "Writer"
        $date            = $this->input->post('date');            // e.g., Posting Date '2025-08-01'
        $link            = $this->input->post('link');            // e.g., Published URL
        $title           = $this->input->post('title');           // optional
        $article_type    = $this->input->post('article_type');    // e.g., 'GBP Post'
        $note            = $this->input->post('note');            // e.g., "Remarks" or “GBP Location: …”
        $platform        = $this->input->post('platform');        // e.g., 'GBP' or 'GBP - San Diego'
        $language        = $this->input->post('language');        // e.g., 'en'
        $indexed         = $this->input->post('indexed');         // 0/1

        // Basic validation
        if (!$brand || !$performed_by || !$date || !$platform) {
            http_response_code(422);
            echo json_encode([
                'status' => 'error',
                'message' => 'Missing required fields: brand, performed_by, date, platform.'
            ]);
            return;
        }

        // Normalize
        if (!$submission_date) $submission_date = date('Y-m-d H:i:s');
        if (!$report) $report = 'GBP Post';
        if (!$article_type) $article_type = 'GBP Post';
        if ($indexed === null || $indexed === '') $indexed = 0;

        // Build data
        $data = [
            'submission_date' => $submission_date,
            'report'          => $report,
            'brand'           => $brand,
            'performed_by'    => $performed_by,
            'date'            => $date,
            'link'            => $link,
            'title'           => $title,
            'article_type'    => $article_type,
            'note'            => $note,
            'platform'        => $platform,
            'language'        => $language ?: 'en',
            'indexed'         => (int)$indexed,
        ];

        // UPSERT by unique key (brand, platform, date, link)
        $existing = $this->kpi->find_existing($brand, $platform, $date, $link);

        if ($existing) {
            $this->kpi->update_by_id($existing->id, $data);
            echo json_encode([
                'status' => 'ok',
                'action' => 'updated',
                'id'     => (int)$existing->id
            ]);
        } else {
            $new_id = $this->kpi->save($data);
            echo json_encode([
                'status' => 'ok',
                'action' => 'inserted',
                'id'     => (int)$new_id
            ]);
        }
    }
    
    function update_post() {
        $data = array(
			'submission_date'   => $this->input->post('submission_date'),
			'report'            => $this->input->post('report'),
			'brand'             => $this->input->post('brand'),
			'performed_by'      => $this->input->post('performed_by'),
			'date'              => $this->input->post('date'),
			'link'              => $this->input->post('link'),
			'title'             => $this->input->post('title'),
			'article_type'      => $this->input->post('article_type'),
			'note'              => $this->input->post('note'),
			'platform'          => $this->input->post('platform'),
		);  

		$kpi = $this->kpi->update( $data );

        $this->response( array(
            'status'   => TRUE,
            'response' => $kpi,
        ), REST_Controller::HTTP_OK );
    }

    function addSeo_post(){
        $data = array(
			'date'          => $this->input->post('date'),
			'seoTeam'       => $this->input->post('seoTeam'),
			'mainFunction'  => $this->input->post('mainFunction'),
			'seoTask'       => $this->input->post('seoTask'),
			'quantity'      => $this->input->post('quantity'),
			'link'          => $this->input->post('link'),
			'location'      => $this->input->post('location'),
			'clientName'    => $this->input->post('clientName'),
			'brand'         => $this->input->post('brand'),
			'remarks'       => $this->input->post('remarks'),
			'reviewDate'    => $this->input->post('reviewDate'),
		);

		$kpi = $this->kpi->saveSeo( $data );

        $this->response( array(
            'status'   => TRUE,
            'response' => $kpi,
        ), REST_Controller::HTTP_OK );
    }
    
    function asanaUsers_get(){
        $accessToken = '2/1209806775551260/1210071412594101:d6248d09d92e0e0321e514a469162141';
        $url = 'https://app.asana.com/api/1.0/users';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json'
        ]);
        
        curl_setopt_array($ch, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($ch);

        $decoded = json_decode($response, true);
        curl_close($ch);

        $this->response( array( 
            'status'   => TRUE,
            'response' => $decoded,
        ), REST_Controller::HTTP_OK );
    }
    
    function asanaKCTasks_get(){
        $accessToken = '2/1209806775551260/1210071412594101:d6248d09d92e0e0321e514a469162141';
        // $url = 'https://app.asana.com/api/1.0/tasks?assignee=1209436214068863&workspace=1141478185895232';
        $baseUrl = 'https://app.asana.com/api/1.0/tasks?assignee=1209436214068863&workspace=1141478185895232&opt_fields=name,due_on,parent.name,completed_at,completed,notes,permalink_url&limit=100';
        $allTasks = [];
        $nextUrl = $baseUrl;
    
        do {
            $ch = curl_init($nextUrl);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $accessToken,
                'Content-Type: application/json'
            ]);
            curl_setopt_array($ch, array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ));
    
            $response = curl_exec($ch);
            curl_close($ch);
    
            $decoded = json_decode($response, true);
    
            if (isset($decoded['data']) && is_array($decoded['data'])) {
                $allTasks = array_merge($allTasks, $decoded['data']);
            }
    
            $nextUrl = isset($decoded['next_page']['uri']) ? $decoded['next_page']['uri'] : null;
    
        } while ($nextUrl);
    
        $this->response(array( 
            'status'   => TRUE,
            'response' => $allTasks,
        ), REST_Controller::HTTP_OK);
    }
    
    
    function importAsanaTask_get(){
      // Prefer env/config over hardcoding
      $accessToken = '2/1209806775551260/1210071412594101:d6248d09d92e0e0321e514a469162141';
      $workspace   = '1141478185895232';
  
      $users = [
          'Alex'       => '1208729529378377',
          // 'Jacob'    => '1210174404900868',
          'Nina'       => '1206216525404645',
          'KC'         => '1209436214068863',
          'Myla'       => '1206618672290000',
          'Cyber'      => '1207011627772525',
          'Faye'       => '1204274772896118',
          'Mary'       => '1208925354989902',
          'Ray'        => '1209806775551260',
          // 'Caesar'   => '1210199891341332',
          'Christina'  => '1210322171555193',
          'Rupert'     => '1208940612212107',
      ];
  
      $summary = [
          'insert' => 0,
          'update' => 0,
          'noop'   => 0,
          'errors' => []
      ];
  
      foreach ($users as $name => $assigneeID) {
          // 1) Get all workspaces for this assignee (or all accessible ones if not allowed)
          $workspaces = $this->getAssigneeWorkspaces($assigneeID, $accessToken, $summary);
  
          foreach ($workspaces as $ws) {
              $workspaceGid  = $ws['gid'];
              $workspaceName = $ws['name'] ?? $ws['gid'];
              $summary['workspaces_checked'][] = ['assignee' => $assigneeID, 'workspace' => $workspaceGid, 'name' => $workspaceName];
  
              // 2) Pull tasks for this assignee in this workspace
              $query = http_build_query([
                  'assignee'   => $assigneeID,
                  'workspace'  => $workspaceGid,
                  'opt_fields' => 'gid,name,due_on,parent.name,parent.gid,completed_at,completed,notes,permalink_url',
                  'limit'      => 100,
              ]);
  
              $nextUrl = 'https://app.asana.com/api/1.0/tasks?' . $query;
  
              do {
                  [$code, $body, $err] = $this->asanaGet($nextUrl, $accessToken);
  
                  if ($body === false || $code >= 400) {
                      if ($code == 429) { usleep(600000); continue; } // short backoff
                      $summary['errors'][] = [
                          'assignee'  => $assigneeID,
                          'workspace' => $workspaceGid,
                          'message'   => ($err ?: "HTTP $code") . ' BODY: ' . substr((string)$body, 0, 500),
                          'url'       => $nextUrl,
                      ];
                      break;
                  }
  
                  $decoded = json_decode($body, true);
                  if (!is_array($decoded) || !isset($decoded['data'])) {
                      $summary['errors'][] = [
                          'assignee'  => $assigneeID,
                          'workspace' => $workspaceGid,
                          'message'   => 'Invalid JSON from Asana. BODY: ' . substr((string)$body, 0, 500),
                          'url'       => $nextUrl,
                      ];
                      break;
                  }
  
                  foreach ($decoded['data'] as $task) {
                      $record = [
                          'task_id'       => $task['gid'] ?? null,
                          'title'         => $task['name'] ?? '',
                          'notes'         => $task['notes'] ?? '',
                          'parent_id'     => $task['parent']['gid']  ?? null,
                          'parent_name'   => $task['parent']['name'] ?? null,
                          'permalink_url' => $task['permalink_url'] ?? null,
                          'completed_at'  => $task['completed_at'] ?? null,
                          'due_on'        => $task['due_on'] ?? null,
                          'completed'     => !empty($task['completed']) ? 1 : 0,
                          'performed_by'  => $name,
                          // (optional) store which workspace we pulled from
                          'workspace_id'  => $workspaceGid,
                          'workspace_name'=> $workspaceName,
                      ];
  
                      $res = $this->kpi->import($record);
                      if (is_array($res) && isset($res['action']) && isset($summary[$res['action']])) {
                          $summary[$res['action']]++;
                      }
                  }
  
                  $nextUrl = $decoded['next_page']['uri'] ?? null;
              } while ($nextUrl);
          }
      }
  
      // JSON response with a quick summary
      $this->output
          ->set_content_type('application/json')
          ->set_output(json_encode($summary));
  }

    
    private function getAssigneeWorkspaces(string $assigneeID, string $accessToken, array &$summary): array
    {
        // Try /users/{assignee}/workspaces first
        $url = "https://app.asana.com/api/1.0/users/{$assigneeID}/workspaces?limit=100";
        [$code, $body, $err] = $this->asanaGet($url, $accessToken);
    
        if ($body !== false && $code < 400) {
            $decoded = json_decode($body, true);
            if (isset($decoded['data']) && is_array($decoded['data']) && count($decoded['data'])) {
                return array_map(function ($w) {
                    return ['gid' => $w['gid'], 'name' => $w['name'] ?? $w['gid']];
                }, $decoded['data']);
            }
        } else {
            // Log why we fell back
            $summary['errors'][] = [
                'assignee' => $assigneeID,
                'message'  => 'Could not list assignee workspaces; falling back to my workspaces. ' . ($err ?: "HTTP $code") . ' BODY: ' . substr((string)$body, 0, 300),
                'url'      => $url,
            ];
        }
    
        // Fallback: list the token user’s workspaces
        $url = "https://app.asana.com/api/1.0/workspaces?limit=100";
        [$code, $body, $err] = $this->asanaGet($url, $accessToken);
    
        if ($body !== false && $code < 400) {
            $decoded = json_decode($body, true);
            if (isset($decoded['data']) && is_array($decoded['data']) && count($decoded['data'])) {
                return array_map(function ($w) {
                    return ['gid' => $w['gid'], 'name' => $w['name'] ?? $w['gid']];
                }, $decoded['data']);
            }
        }
    
        // If all else fails, return an empty list so caller handles gracefully
        return [];
    }
    
    private function asanaGet(string $url, string $accessToken): array
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_HTTPHEADER     => [
                'Authorization: Bearer ' . $accessToken,
                'Accept: application/json',
                'User-Agent: JFJ-KPI-Importer/1.0',
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 5,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'GET',
        ]);
        $body    = curl_exec($ch);
        $code    = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err     = curl_error($ch);
        curl_close($ch);
        return [$code, $body, $err];
    }
    
    function asanaCyberTasks_get(){
        $accessToken = '2/1209806775551260/1210071412594101:d6248d09d92e0e0321e514a469162141';
        $baseUrl = 'https://app.asana.com/api/1.0/tasks?assignee=1207011627772525&workspace=1141478185895232&opt_fields=name,due_on,parent.name,completed_at,completed,notes,permalink_url&limit=100';
        
        $allTasks = [];
        $nextUrl = $baseUrl;
    
        do {
            $ch = curl_init($nextUrl);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $accessToken,
                'Content-Type: application/json'
            ]);
            curl_setopt_array($ch, array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ));
    
            $response = curl_exec($ch);
            curl_close($ch);
    
            $decoded = json_decode($response, true);
    
            if (isset($decoded['data']) && is_array($decoded['data'])) {
                $allTasks = array_merge($allTasks, $decoded['data']);
            }
    
            $nextUrl = isset($decoded['next_page']['uri']) ? $decoded['next_page']['uri'] : null;
    
        } while ($nextUrl);
    
        $this->response(array( 
            'status'   => TRUE,
            'response' => $allTasks,
        ), REST_Controller::HTTP_OK);
    }

    function getGraphicsTeam_get(){
        $kpi = $this->kpi->showGraphicsTeam();

        if ($kpi) {
            
            // $apiKey = $this->config->item('openai_api_key', 'openai');
            // // Get start and end of current week (Monday to Sunday)
            // $startOfLastWeek = date('Y-m-d', strtotime('monday last week'));
            // $endOfLastWeek   = date('Y-m-d', strtotime('sunday last week'));
            
            // $startOfLastMonth = date('Y-m-01', strtotime('first day of last month'));
            // $endOfLastMonth = date('Y-m-t', strtotime('last day of last month'));
            
            // $thismonth = date('Y-m-t', strtotime('last day of this month'));
            
            // // $prompt = "Generate a summary for the EOW tasks for the month ($startOfWeek to $endOfWeek):\n";
            
            // $promptEOW = "I want to have a item in paragraph of report to Generate a summary for the last week ($startOfLastWeek to $endOfLastWeek):\n";
            
            
            // $promptEOM = "I want to have a item paragraph of report to Generate a summary for the month ($thismonth):\n";
            
            // foreach ($kpi as $item) {
            //     $date = $item['completed_at'];
            //     if($date != null){
            //         // Include only entries from the current week
            //         if ($date >= $thismonth) {
            //             $promptEOW .= sprintf(
            //                 "- [%s] (%s) performed by %s: %s (Link: %s)\n",
            //                 $item['completed_at'],
            //                 $item['title'],
            //                 // $item['performed_by'],
            //                 $item['performed_by'],
            //                 $item['permalink_url'],
            //                 $item['due_on']
            //             );
            //         }
            //     }
                
            // }
            
            // foreach ($kpi as $item) {
            //     $date = $item['completed_at'];
            //     if($date != null){
            //         if ($date >= $thismonth) {
            //             $promptEOM .= sprintf(
            //                 "- [%s] (%s) performed by %s: %s (Link: %s)\n",
            //                 $item['completed_at'],
            //                 $item['title'],
            //                 // $item['performed_by'],
            //                 $item['performed_by'],
            //                 $item['permalink_url'],
            //                 $item['due_on']
            //             );
            //         }
            //     }
            // }
            // // Initialize OpenAI
            // $openai = new OpenAIWrapper($apiKey);
            // $client = $openai->getClient();
        
            // try {
                
            //     $responseEOW = $client->chat()->create([
            //         'model' => 'gpt-4-turbo',
            //         'messages' => [
            //             ['role' => 'system', 'content' => 'You are an expert at summarizing KPI tasks concisely and insightfully. you want to have a brief and in paragraph. Giving percentage on each project can be more help. for the conclusion I want to make the response to be more general or it is focused on team collaborative and not each members achievements.'],
            //             ['role' => 'user', 'content' => $promptEOW],
            //         ],
            //         'max_tokens' => 600,
            //         'temperature' => 1
            //     ]);
                
            //     $responseEOM = $client->chat()->create([
            //         'model' => 'gpt-4-turbo',
            //         'messages' => [
            //             ['role' => 'system', 'content' => 'You are an expert at summarizing KPI tasks concisely and insightfully. you want to have a brief and in paragraph. Giving percentage on each project can be more help. for the conclusion I want to make the response to be more general or it is focused on team collaborative and not each members achievements.'],
            //             ['role' => 'user', 'content' => $promptEOM],
            //         ],
            //         'max_tokens' => 600,
            //         'temperature' => 1
            //     ]);
                
            //     $EOWconc = trim($responseEOW->choices[0]->message->content);
            //     $EOMconc = trim($responseEOM->choices[0]->message->content);
            // } 
            
            // catch (Exception $e) {
            //     log_message('error', 'OpenAI API Error: ' . $e->getMessage());
            //     $EOWconc = "Error generating conclusion.". $e->getMessage();
            //     $EOMconc = "Error generating conclusion.". $e->getMessage();
            // }
    
            // Final structured response
            $this->response([
                'status' => TRUE,
                'response' => $kpi,
                // 'conclusionEOW' => $EOWconc,
                // 'conclusionEOM' => $EOMconc
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => FALSE,
                'message' => 'No KPI data available'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }
    
    function checkLinkStatus_get(){
        header('Content-Type: application/json');
        header("Access-Control-Allow-Origin: *"); // adjust this for security in production
        
        if (!isset($_GET['url'])) {
            echo json_encode(['error' => 'Missing URL']);
            exit;
        }
        
        $url = filter_var($_GET['url'], FILTER_VALIDATE_URL);
        
        if (!$url) {
            echo json_encode(['error' => 'Invalid URL']);
            exit;
        }
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true); // only get headers
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5); // optional timeout
        curl_exec($ch);
        
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        $isLive = $httpCode >= 200 && $httpCode < 400;
        
        echo json_encode([
            'url' => $url,
            'isLive' => $isLive
        ]);
    }
    
    function fetchGBP_get(){
        $gbp = $this->kpi->showgbp();

        if ( $gbp ) {
            $this->response( array(
                'status'   => TRUE,
                'response' => $gbp,
            ), REST_Controller::HTTP_OK );
        }
        
        // require '../../vendor/autoload.php';

        // use BrightLocal\Api;
        
        // $api = new Api('<YOUR_API_KEY>', '<YOUR_API_SECRET>');
        // $response = $api->get('/v2/lsrc/get', [
        //     'campaign-id' => 50
        // ]);
        // print_r($response->getResult());
    }
    function updateGBP_put($id) {
        
    }
    function addGBP_post(){
        
    }
    
    
    function fetchGBPTask_get(){
        $gbp = $this->kpi->showgbptask();

        
        if ($gbp) {
            
            // $apiKey = $this->config->item('openai_api_key', 'openai');
            // // Get start and end of current week (Monday to Sunday)
            // $startOfLastWeek = date('Y-m-d', strtotime('monday last week'));
            // $endOfLastWeek   = date('Y-m-d', strtotime('sunday last week'));
            
            // $startOfLastMonth = date('Y-m-01', strtotime('first day of last month'));
            // $endOfLastMonth = date('Y-m-t', strtotime('last day of last month'));
            
            // // $prompt = "Generate a summary for the EOW tasks for the month ($startOfWeek to $endOfWeek):\n";
            
            // $promptEOW = "'I want to have a item paragraph of report to Generate a summary for the last week ($startOfLastWeek to $endOfLastWeek):\n";
            
            
            // $promptEOM = "'I want to have a item paragraph of report to Generate a summary for the month ($startOfLastMonth to $endOfLastMonth):\n";
            
            // foreach ($gbp as $item) {
            //     $date = $item['date'];
            
            //     // Include only entries from the current week
            //     if ($date >= $startOfLastWeek && $date <= $endOfLastWeek) {
            //         $promptEOW .= sprintf(
            //             "- [%s] (%s) performed by %s: %s (Link: %s)\n",
            //             $item['date'],
            //             $item['mainFunction'],
            //             // $item['performed_by'],
            //             $item['seoTask'],
            //             $item['link'],
            //             $item['quantity']
            //         );
            //     }
            // }
            
            // foreach ($gbp as $item) {
            //     $date = $item['date'];
            //     if ($date >= $startOfLastMonth && $date <= $endOfLastMonth) {
            //         $promptEOM .= sprintf(
            //             "- [%s] (%s) performed by %s: %s (Link: %s)\n",
            //             $item['date'],
            //             $item['mainFunction'],
            //             // $item['performed_by'],
            //             $item['seoTask'],
            //             $item['link'],
            //             $item['quantity']
            //         );
            //     }
            // }
    
            // // Initialize OpenAI
            // $openai = new OpenAIWrapper($apiKey);
            // $client = $openai->getClient();
        
            // try {
                
            //     $responseEOW = $client->chat()->create([
            //         'model' => 'gpt-4-turbo',
            //         'messages' => [
            //             ['role' => 'system', 'content' => 'You are an expert at summarizing KPI tasks concisely and insightfully. you want to have a brief and in paragraph. Giving percentage on each project can be more help. for the conclusion I want to make the response to be more general or it is focused on team collaborative and not each members achievements.'],
            //             ['role' => 'user', 'content' => $promptEOW],
            //         ],
            //         'max_tokens' => 600,
            //         'temperature' => 1
            //     ]);
                
            //     $responseEOM = $client->chat()->create([
            //         'model' => 'gpt-4-turbo',
            //         'messages' => [
            //             ['role' => 'system', 'content' => 'You are an expert at summarizing KPI tasks concisely and insightfully. you want to have a brief and in paragraph. Giving percentage on each project can be more help. for the conclusion I want to make the response to be more general or it is focused on team collaborative and not each members achievements.'],
            //             ['role' => 'user', 'content' => $promptEOM],
            //         ],
            //         'max_tokens' => 600,
            //         'temperature' => 1
            //     ]);
            //     $EOWconc = trim($responseEOW->choices[0]->message->content);
            //     $EOMconc = trim($responseEOM->choices[0]->message->content);
            // } 
            
            // catch (Exception $e) {
            //     log_message('error', 'OpenAI API Error: ' . $e->getMessage());
            //     $EOWconc = "Error generating conclusion.";
            //     $EOMconc = "Error generating conclusion.";
            // }
    
            // Final structured response
            $this->response([
                'status' => TRUE,
                'response' => $gbp,
                // 'conclusionEOW' => $EOWconc,
                // 'conclusionEOM' => $EOMconc
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => FALSE,
                'message' => 'No KPI data available'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }
    
    
    function fetchSEOLinkBuilding_get(){
        $seo = $this->kpi->showseolinkbuilding();

        if ( $seo ) {
            $this->response( array(
                'status'   => TRUE,
                'response' => $seo,
            ), REST_Controller::HTTP_OK );
        }
    }
    
    
    
    function fetchIndexMonitoring_get(){
        $seo = $this->kpi->showindexmonitoring();

        if ( $seo ) {
            $this->response( array(
                'status'   => TRUE,
                'response' => $seo,
            ), REST_Controller::HTTP_OK );
        }
    }
    
    
    function store_post(){
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        
        if (!is_array($data) || !isset($data['citations']) || !isset($data['summary'])) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid JSON structure']);
            return;
        }
    
        $inserted = 0;
        foreach ($data['citations'] as $item) {
            if ($this->kpi->insertcitation($item)) {
                $inserted++;
            }
        }
    
        $summarySaved = $this->kpi->insertsummary($data['summary']);
    
        echo json_encode([
            'status' => 'success',
            'message' => "$inserted citations inserted. Summary saved: " . ($summarySaved ? 'Yes' : 'No')
        ]);
    }
    
    function showcitation_get(){
        $brightlocal = $this->kpi->fetchcitation();

        if ( $brightlocal ) {
            $this->response( array(
                'status'   => TRUE,
                'response' => $brightlocal,
            ), REST_Controller::HTTP_OK );
        }
    }
    
    function showcitationsummary_get(){
        $brightlocal = $this->kpi->fetchcitationsummary();

        if ( $brightlocal ) {
            $this->response( array(
                'status'   => TRUE,
                'response' => $brightlocal,
            ), REST_Controller::HTTP_OK );
        }
    }
    
    function keywordrank_get() {
        $list = $this->kpi->fetchkeywordrank();

        if ( $list ) {
            $this->response( array(
                'status'   => TRUE,
                'response' => $list,
            ), REST_Controller::HTTP_OK );
        }
    }
    
    function savekeywordranking_post () {
        // Parse JSON as array
        $json = file_get_contents('php://input');
        $data = json_decode($json, true); // associative array
        $timestamp = date('Y-m-d H:i:s');
        $inserted  = 0;
        
        foreach ($data as $entries) {
            foreach ($entries as $entry) {
                $record = [
                    'keyword'           => $entry['keyword'],
                    'search_engine'     => $entry['engine'],
                    'rank'              => is_numeric($entry['rank']) ? $entry['rank'] : null,
                    'rank_raw'          => $entry['rank'] ?? '',
                    'previous_rank'     => is_numeric($entry['previous_rank']) ? $entry['previous_rank'] : null,
                    'previous_rank_raw' => $entry['previous_rank'] ?? '',
                    'change'            => $entry['change'] ?? 0,
                    'search_location'   => $entry['search_location'],
                    'serp_url'          => $entry['serp_url'] ?? '',
                    'screenshot_urls'   => isset($entry['screenshot']) ? json_encode($entry['screenshot']) : null,
                    'sparkline'         => isset($entry['sparkline']) ? json_encode($entry['sparkline']) : null,
                    'created_at'        => $timestamp,
                    'volume'            => $entry['search_volume']
                ];
                // pr($record);
                
                if ($this->kpi->insert_keyword_rankings_detailed($record)) {
                    $inserted++;
                }
            }
        }
    
        echo json_encode([
            'status' => 'success',
            'message' => "$inserted keyword ranking records inserted."
        ]);
    }
    
    
    function add_seo_perf_post(){
        $data = array(
          'date'                          => $this->input->post('date'),
          'brands'                        => $this->input->post('brands'),
          'sessions'                      => $this->input->post('sessions'),
          'total_users'                   => $this->input->post('total_users'),
          'avg_engage_time_session'       => $this->input->post('avg_engage_time_session'),
          'phone_leads'                   => $this->input->post('phone_leads'),
          'form_leads'                    => $this->input->post('form_leads'),
          'flf_leads'                     => $this->input->post('flf_leads'),
          'flf_signups'                   => $this->input->post('flf_signups'),
          'rank_performance'              => $this->input->post('rank_performance'),
          'notes'                         => $this->input->post('notes'),
        );

		$kpi = $this->kpi->saveseoperf( $data );

        $this->response( array(
            'status'   => TRUE,
            'response' => $kpi,
        ), REST_Controller::HTTP_OK );
    }
    
    function add_active_campaign_get(){
        $apiKey = 'fd204dde85cc59c96adf03da3a8d2f56309ad04095cd21a8da7cb51bd629afb63bbc6a89';
        $baseUrl = 'https://farahilaw54527.api-us1.com';
    
        // Step 1: Get all campaigns
        $campaignsUrl = $baseUrl . '/api/3/campaigns?limit=100&offset=0';
        $campaigns = $this->_call_activecampaign_api($campaignsUrl, $apiKey);
    
        if (!isset($campaigns['campaigns'])) {
            $this->response([
                'status' => FALSE,
                'message' => 'No campaigns found or API error.',
            ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
            return;
        }
    
        foreach ($campaigns['campaigns'] as $campaign) {
            $campaignId = $campaign['id'];
            $campaignName = $campaign['name'];
    
            // Step 2: Get campaign detail for message_stats
            $campaignDetailUrl = $baseUrl . '/api/3/campaigns/' . $campaignId;
            $detail = $this->_call_activecampaign_api($campaignDetailUrl, $apiKey);
    
            if (!isset($detail['campaign'])) continue;
    
            $reportData = [
                'campaign_id' => $campaignId,
                'name'        => $detail['campaign']['name'] ?? '',
                'subject'     => $detail['campaign']['subject'] ?? '',
                'status'      => $detail['campaign']['status'] ?? '',
                'opens'       => $detail['campaign']['opens'] ?? 0,
                'clicks'      => $detail['campaign']['clicks'] ?? 0,
                'bounces'     => $detail['campaign']['softbounces'] ?? 0,
                'unsubscribes'=> $detail['campaign']['unsubscribes'] ?? 0,
                'created_at'=> $detail['campaign']['created_timestamp'] ?? 0,
            ];
    
            // Save to database using your model
            $this->kpi->save_campaign_report($reportData);
        }
    
        $this->response([
            'status' => TRUE,
            'message' => 'Campaign reports fetched and saved successfully.'
        ], REST_Controller::HTTP_OK);
    }
    
    function _call_activecampaign_api($url, $apiKey) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Api-Token: ' . $apiKey,
                'Content-Type: application/json'
            ]
        ]);
    
        $response = curl_exec($ch);
        curl_close($ch);
    
        return json_decode($response, true);
    }
    
    function fetch_ac_get () {
        $list = $this->kpi->fetchac();

        if ( $list ) {
            $this->response( array(
                'status'   => TRUE,
                'response' => $list,
            ), REST_Controller::HTTP_OK );
        }
    }
    function fetchemailreports_get() {
        // Get the campaign_id from GET parameters
        $campaign_id = $this->get('campaign_id');
    
        // Validate campaign_id
        if (!$campaign_id || !is_numeric($campaign_id)) {
            return $this->response([
                'status'  => FALSE,
                'message' => 'Invalid or missing campaign.'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    
        // Fetch data from model
        $list = $this->kpi->fetchemailreport($campaign_id);
    
        if (!empty($list)) {
            return $this->response([
                'status'   => TRUE,
                'response' => $list
            ], REST_Controller::HTTP_OK);
        } else {
            return $this->response([
                'status'  => FALSE,
                'message' => 'No records found for this campaign_id.'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    function emailreports_post () {
        $data = array(
			'email'                => $this->input->post('email'),
			'campaign_name'        => $this->input->post('campaign_name'),
			'timestamp'            => $this->input->post('timestamp'),
			'event_type'            => $this->input->post('event_type'),
			'contact_id'           => $this->input->post('contact_id'),
			'campaign_id'          => $this->input->post('campaign_id'),
		);

		$kpi = $this->kpi->saveac( $data );

        $this->response( array(
            'status'   => TRUE,
            'response' => $kpi,
        ), REST_Controller::HTTP_OK );
    }
    
    function savega_post() {
        $json = json_decode(file_get_contents("php://input"), true);

        if (!$json) {
            show_error('Invalid JSON', 400);
        }

        foreach ($json as $row) {
            $this->kpi->save_engagement($row);
        }

        echo json_encode(['status' => 'success', 'message' => 'Data saved']);
    }
    
    function fetchga_get() {
        $list = $this->kpi->fetchga();

        if ( $list ) {
            $this->response( array(
                'status'   => TRUE,
                'response' => $list,
            ), REST_Controller::HTTP_OK );
        }
    }
    
    
    function savegaacquisition_post() {
        $json = file_get_contents("php://input");
        $data = json_decode($json, true);

        if (!$data || !is_array($data)) {
            return $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode(['message' => 'Invalid or empty JSON']));
        }

        $cleaned_data = [];
        foreach ($data as $item) {
            // Common field
            $date = $item['date'] ?? null;
            if (!$date) continue;

            $cleaned_data[] = [
                'date' => $date,
                'session_source' => $item['session_source'] ?? null,
                'session_medium' => $item['session_medium'] ?? null,
                'first_user_source' => $item['first_user_source'] ?? null,
                'first_user_medium' => $item['first_user_medium'] ?? null,
                'default_channel_group' => $item['default_channel_group'] ?? null,
                'sessions' => isset($item['sessions']) ? (int) $item['sessions'] : 0,
                'new_users' => isset($item['new_users']) ? (int) $item['new_users'] : 0,
                'event_name' => $item['event_name'] ?? null,
                'event_count' => isset($item['event_count']) ? (int) $item['event_count'] : 0
            ];
        }

        if (empty($cleaned_data)) {
            return $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode(['message' => 'No valid data found']));
        }

        $inserted = $this->kpi->insert_batch_events($cleaned_data);

        if ($inserted) {
            return $this->output
                ->set_status_header(200)
                ->set_content_type('application/json')
                ->set_output(json_encode(['message' => 'Data saved successfully']));
        } else {
            return $this->output
                ->set_status_header(500)
                ->set_content_type('application/json')
                ->set_output(json_encode(['message' => 'Failed to save data']));
        }
    }
    
    function fetchgaacqiosition_get() {
        $list = $this->kpi->fetchgaacquisition();

        if ( $list ) {
            $this->response( array(
                'status'   => TRUE,
                'response' => $list,
            ), REST_Controller::HTTP_OK );
        }
    }
    
    function fetchleaddocket_get() {
        $list = $this->kpi->fetchleaddocket();

        if ( $list ) {
            $this->response( array(
                'status'   => TRUE,
                'response' => $list,
            ), REST_Controller::HTTP_OK );
        }
    }
    
    function countsign_get(){
        $list = $this->kpi->signcount();

        if ( $list ) {
            $this->response( array(
                'status'   => TRUE,
                'response' => $list,
            ), REST_Controller::HTTP_OK );
        }
    }
    
    function countmtd_get(){
        $list = $this->kpi->mtdcount();

        if ( $list ) {
            $this->response( array(
                'status'   => TRUE,
                'response' => $list,
            ), REST_Controller::HTTP_OK );
        }
    }
    
    
    function referredcount_get(){
        $list = $this->kpi->referredcount();

        if ( $list ) {
            $this->response( array(
                'status'   => TRUE,
                'response' => $list,
            ), REST_Controller::HTTP_OK );
        }
    }
    
    
    function rejectedcount_get(){
        $list = $this->kpi->rejectedcount();

        if ( $list ) {
            $this->response( array(
                'status'   => TRUE,
                'response' => $list,
            ), REST_Controller::HTTP_OK );
        }
    }
    
    function addintakelead_post(){
        $data = array(
            'full_name'                 => $this->post('full_name'),
            'mobile_phone'              => $this->post('mobile_phone'),
            'status'                    => $this->post('status'),
            'substatus'                 => $this->post('substatus'),
            'case_type'                 => $this->post('case_type'),
            'marketing_source'          => $this->post('marketing_source'),
            'created_date'              => $this->post('created_date'),
            'incident_date'             => $this->post('incident_date'),
            'last_status_change_date'   => $this->post('last_status_change_date'),
            'last_update_date'          => $this->post('last_update_date'),
            'last_note_date'            => $this->post('last_note_date'),
            'last_note'                 => $this->post('last_note'),
            'call_outcome'              => $this->post('call_outcome'),
            'not_interested_disposition' => $this->post('not_interested_disposition'),
            'not_responsive_disposition' => $this->post('not_responsive_disposition'),
            'intake_completed_by'       => $this->post('intake_completed_by'),
            'initial_call_taken_by'     => $this->post('initial_call_taken_by'),
            'open_disposition'          => $this->post('open_disposition'),
            'referred_out_disposition'  => $this->post('referred_out_disposition'),
            'rejected_disposition'      => $this->post('rejected_disposition'),
            'signed_disposition'        => $this->post('signed_disposition')
        );
    
        $result = $this->kpi->saveOrUpdateSeo($data); // Use the new function
    
        $this->response(array(
            'status'   => TRUE,
            'response' => $result,
        ), REST_Controller::HTTP_OK);
    }
    
    
    
    function signupintakelead_post(){
        $data = array(
            'full_name'                 => $this->post('full_name'),
            'email_contact'             => $this->post('email_contact'),
            'tier'                      => $this->post('tier'),
            'value'                     => $this->post('value'),
            'language'                  => $this->post('language'),
            'referred_attorney'         => $this->post('referred_attorney'),
            'referral_fee'              => $this->post('referral_fee'),
            'case_id_number'            => $this->post('case_id_number'),
            'lead_outcome'              => $this->post('lead_outcome'),
            'rank'                      => $this->post('rank'),
            'pd_assigned'               => $this->post('pd_assigned'),
            'cm_assigned'               => $this->post('cm_assigned'),
            'lead_source'               => $this->post('lead_source'),
            'marketing_source'          => $this->post('marketing_source'),
            'created_date'              => $this->post('sign_up_date'),
            'timestamp'                 => $this->post('timestamp'),
            'person_conducting_intake'  => $this->post('person_conducting_intake'),
            'case_id_number'            => $this->post('case_id_number'),
            'callback_number'           => $this->post('callback_number'),
            'sign_up_date'              => $this->post('sign_up_date'),
            '90_day_drop_date'          => $this->post('90_day_drop_date'),
            'signed_cases_referral_status'  => $this->post('signed_cases_referral_status'),
            'intake_completed_2'         => $this->post('intake_completed_2'),
            'accepted_disputed'         => $this->post('accepted_disputed'),
            'notes'                     => $this->post('notes'),
            'drop_date'                 => $this->post('drop_date'),
            'case_type'                 => $this->post('case_type'),
            'signup_outside_workhours'  => $this->post('signup_outside_workhours'),
        );
        
    
        $result = $this->kpi->saveOrUpdateSeo($data); // Use the new function
    
        $this->response(array(
            'status'   => TRUE,
            'response' => $result,
        ), REST_Controller::HTTP_OK);
    }
    
    
    function inhousesignupsummary_get() {
        $list = $this->kpi->inhousesignupsummary();

        if ( $list ) {
            $this->response( array(
                'status'   => TRUE,
                'response' => $list,
            ), REST_Controller::HTTP_OK );
        }
    }
    
    function inhousesignupsummarylist_get() {
        $list = $this->kpi->inhousesignupsummarylist();

        if ( $list ) {
            $this->response( array(
                'status'   => TRUE,
                'response' => $list,
            ), REST_Controller::HTTP_OK );
        }
    }
    
    function categorysignupsummary_get() {
        $list = $this->kpi->categorysignupsummary();

        if ( $list ) {
            $this->response( array(
                'status'   => TRUE,
                'response' => $list,
            ), REST_Controller::HTTP_OK );
        }
    }
    
    function referredsummarycount_get() {
        $list = $this->kpi->referredsummarycount();

        if ( $list ) {
            $this->response( array(
                'status'   => TRUE,
                'response' => $list,
            ), REST_Controller::HTTP_OK );
        }
    }
    
    function seoperformancesummarycount_get() {
        $list = $this->kpi->seoperformancesummary();

        if ( $list ) {
            $this->response( array(
                'status'   => TRUE,
                'response' => $list,
            ), REST_Controller::HTTP_OK );
        }
    }
    
    
    
    function addleademail_post(){
        $data = array(
            'name'                  => $this->post('name'),
            'phone'                 => $this->post('phone'),
            'email'                 => $this->post('email'),
            'message'               => $this->post('message'),
            'type'                  => $this->post('type'),
            'date'                  => $this->post('date'),
            'navigation_history'    => $this->post('navigation_history'),
        );
    
        $result = $this->kpi->saveLeadEmail($data); // Use the new function
    
        $this->response(array(
            'status'   => TRUE,
            'response' => $result,
        ), REST_Controller::HTTP_OK);
    }
    
    function fetchleadForm_get() {
        $list = $this->kpi->leadForm();

        if ( $list ) {
            $this->response( array(
                'status'   => TRUE,
                'response' => $list,
            ), REST_Controller::HTTP_OK );
        }
    }
    
    /**
     * Compute KPI rollups + grouped tables for a date range.
     * Returns targets, raw counts, derived rates, % to goal,
     * and table datasets for email rendering.
     */
    // private function compute_kpis($start, $end, $targetLead = 250, $targetSignup = 45)
    // {
    //     $startDt = $start . ' 00:00:00';
    //     $endDt   = $end   . ' 23:59:59';
    
    //     $keywords = [
    //         'gmb', 'intaker', 'avvo', 'rnd', 'yelp', 'ayuda',
    //         'social', 'justin', 'kapwa', 'labor', 'brain', 'motorcyclist', 'jfj', 'website'
    //     ];
    //     $leadStatuses = [
    //         'Signed Up', 'Referred', 'Rejected', 'Chase',
    //         'Pending Agreement', 'Pending Referral', 'Under Review', 'Lost', 'Signed'
    //     ];
    //     $leadOutcomes = ['Signed','Referred out'];
    
    //     // Helper: apply marketing_source keyword LIKEs (case-insensitive)
    //     $applyKeywords = function() use ($keywords) {
    //         $this->db->group_start();
    //         foreach ($keywords as $i => $kw) {
    //             if ($i === 0) $this->db->like('marketing_source', $kw);
    //             else          $this->db->or_like('marketing_source', $kw);
    //         }
    //         $this->db->group_end();
    //     };
    
    //     /** ------------------- CORE KPIs ------------------- **/
    //     // 1) Leads universe
    //     $this->db->reset_query();
    //     $this->db->from('leads_tracker');
    //     $this->db->group_start()
    //                 ->group_start()
    //                     ->where('created_date >=', $startDt)
    //                     ->where('created_date <=', $endDt)
    //                 ->group_end()
    //                 ->or_group_start()
    //                     ->where('sign_up_date >=', $startDt)
    //                     ->where('sign_up_date <=', $endDt)
    //                 ->group_end()
    //              ->group_end();
    //     $applyKeywords();
    //     $this->db->group_start()
    //              ->where_in('status', $leadStatuses)
    //              ->or_where_in('lead_outcome', $leadOutcomes)
    //              ->group_end();
    //     $mtdLeadCount = (int)$this->db->count_all_results();
        
    //     // echo $this->db->last_query();die();
    //     // pr($mtdLeadCount);die();
    
    //     // 2) Signed
    //     $this->db->reset_query();
    //     $this->db->from('leads_tracker');
    //     $this->db->where('sign_up_date >=', $startDt)
    //              ->where('sign_up_date <=', $endDt);
    //     $applyKeywords();
    //     $this->db->group_start()
    //              ->where('lead_outcome', 'Signed')
    //             //  ->or_where('status', 'Signed Up')
    //              ->group_end();
    //     $mtdSignCount = (int)$this->db->count_all_results();
    
    //     // 3) Referred out
    //     $this->db->reset_query();
    //     $this->db->from('leads_tracker');
    //     $this->db->where('sign_up_date >=', $startDt)
    //              ->where('sign_up_date <=', $endDt);
    //     $applyKeywords();
    //     $this->db->where('lead_outcome', 'Referred out');
    //     $referredCount = (int)$this->db->count_all_results();
    
    //     $totalSigned   = $mtdSignCount + $referredCount;
    //     $mtdAcqRate    = $mtdLeadCount > 0 ? round(($totalSigned / $mtdLeadCount) * 100, 2) : 0.00;
    
    //     $pctToGoalLeads   = $targetLead   > 0 ? round(($mtdLeadCount / $targetLead) * 100, 2) : 0.00;
    //     $pctToGoalSignups = $targetSignup > 0 ? round(($totalSigned  / $targetSignup) * 100, 2) : 0.00;
    
    //     $targetAR = ($targetLead > 0) ? ($targetSignup / $targetLead) : 0;
    //     $actualAR = ($mtdLeadCount > 0) ? ($totalSigned / $mtdLeadCount) : 0;
    //     $pctToGoalAR = ($targetAR > 0) ? round(($actualAR / $targetAR) * 100, 2) : 0.00;
    
    //     /** ------------------- GROUPED TABLES ------------------- **/
    //     // A) In-House Sign Up Summary: count by marketing_source where (Signed or Referred out)
    //     $this->db->reset_query();
    //     $this->db->select('marketing_source, COUNT(*) AS total_signed', false);
    //     $this->db->from('leads_tracker');
    //     $this->db->where('sign_up_date >=', $startDt)->where('sign_up_date <=', $endDt);
    //     $applyKeywords();
    //     $this->db->where_in('lead_outcome', $leadOutcomes);
    //     $this->db->group_by('marketing_source');
    //     $this->db->order_by('total_signed', 'DESC');
    //     $inhouseSignupSummary = $this->db->get()->result_array();
    
    //     // B) Category (value) summary: count by value where (Signed or Referred out)
    //     $this->db->reset_query();
    //     $this->db->select("COALESCE(NULLIF(TRIM(value),''),'Uncategorized') AS value, COUNT(*) AS total_signed", false);
    //     $this->db->from('leads_tracker');
    //     $this->db->where('sign_up_date >=', $startDt)->where('sign_up_date <=', $endDt);
    //     $applyKeywords();
    //     $this->db->where_in('lead_outcome', $leadOutcomes);
    //     $this->db->group_by('value');
    //     $this->db->order_by('total_signed', 'DESC');
    //     $categorySignupSummary = $this->db->get()->result_array();
    
    //     // C) Referred summary: total_referred (status='Referred'), successful_referred_count (lead_outcome='Referred out'), per marketing_source
    //     $this->db->reset_query();
    //     $sql = "
    //       SELECT
    //         marketing_source,
    //         SUM(CASE WHEN status = 'Referred' THEN 1 ELSE 0 END) AS total_referred,
    //         SUM(CASE WHEN lead_outcome = 'Referred out' THEN 1 ELSE 0 END) AS successful_referred_count
    //       FROM leads_tracker
    //       WHERE (sign_up_date BETWEEN ? AND ?)
    //         AND (".implode(' OR ', array_fill(0, count($keywords), 'LOWER(marketing_source) LIKE ?')).")
    //       GROUP BY marketing_source
    //       ORDER BY successful_referred_count DESC, total_referred DESC
    //     ";
    //     $binds = [$startDt, $endDt];
    //     foreach ($keywords as $kw) { $binds[] = '%'.strtolower($kw).'%'; }
    //     $referredSummary = $this->db->query($sql, $binds)->result_array();
    
    //     // D) In-House Signup Details (Signed only) limited for email
    //     $this->db->reset_query();
    //     $this->db->select('marketing_source, value, case_type, full_name AS client_name, sign_up_date');
    //     $this->db->from('leads_tracker');
    //     $this->db->where('sign_up_date >=', $startDt)->where('sign_up_date <=', $endDt);
    //     $applyKeywords();
    //     $this->db->where('lead_outcome', 'Signed');
    //     $this->db->order_by('sign_up_date', 'DESC');
    //     $this->db->limit(100); // keep emails light
    //     $inhouseSignupDetails = $this->db->get()->result_array();
        
        
        
    //     // E) Signup Referred Details
    //     $this->db->reset_query();
    //     $this->db->select('marketing_source, value, case_type, full_name AS client_name, sign_up_date');
    //     $this->db->from('leads_tracker');
    //     $this->db->where('sign_up_date >=', $startDt)->where('sign_up_date <=', $endDt);
    //     $applyKeywords();
    //     $this->db->where('lead_outcome', 'Signed');
    //     $this->db->order_by('sign_up_date', 'DESC');
    //     $this->db->limit(100); // keep emails light
    //     $signupReferredDetails = $this->db->get()->result_array();
    
    //     return [
    //         'range' => ['start' => $start, 'end' => $end],
    //         'targets' => [
    //             'lead' => (int)$targetLead,
    //             'signup' => (int)$targetSignup,
    //             'target_acquisition_rate' => $targetAR > 0 ? round($targetAR * 100, 2) : 0.00
    //         ],
    //         'kpis' => [
    //             'mtd_lead_count' => $mtdLeadCount,
    //             'mtd_sign_count' => $mtdSignCount,
    //             'referred_count' => $referredCount,
    //             'total_signed' => $totalSigned,
    //             'mtd_acquisition_rate' => $mtdAcqRate,
    //             'pct_to_goal' => [
    //                 'leads' => $pctToGoalLeads,
    //                 'signups' => $pctToGoalSignups,
    //                 'acquisition_rate' => $pctToGoalAR
    //             ]
    //         ],
    //         'tables' => [
    //             'inhouse_signup_summary'    => $inhouseSignupSummary,
    //             'category_signup_summary'   => $categorySignupSummary,
    //             'referred_summary'          => $referredSummary,
    //             'inhouse_signup_details'    => $inhouseSignupDetails,
    //             'signup_referred_details'    => $signupReferredDetails,
    //         ],
    //     ];
    // }
    
    
    private function compute_kpis($start, $end, $targetLead = 250, $targetSignup = 45)
    {
        $startDt = $start . ' 00:00:00';
        $endDt   = $end   . ' 23:59:59';
    
        $keywords = [
            'gmb', 'intaker', 'avvo', 'rnd', 'yelp', 'ayuda',
            'social', 'justin', 'kapwa', 'labor', 'brain', 'motorcyclist', 'jfj', 'website'
        ];
        $leadStatuses = [
            'Signed Up', 'Referred', 'Rejected', 'Chase',
            'Pending Agreement', 'Pending Referral', 'Under Review', 'Lost', 'Signed'
        ];
        $leadOutcomes = ['Signed','Referred out'];
    
        // Helper: apply marketing_source keyword LIKEs (case-insensitive)
        $applyKeywords = function() use ($keywords) {
            $this->db->group_start();
            foreach ($keywords as $i => $kw) {
                if ($i === 0) $this->db->like('marketing_source', $kw);
                else          $this->db->or_like('marketing_source', $kw);
            }
            $this->db->group_end();
        };
    
        /** ------------------- CORE KPIs ------------------- **/
        // 1) Leads universe
        $this->db->reset_query();
        $this->db->from('leads_tracker');
        $this->db->group_start()
                    ->group_start()
                        ->where('created_date >=', $startDt)
                        ->where('created_date <=', $endDt)
                    ->group_end()
                    ->or_group_start()
                        ->where('sign_up_date >=', $startDt)
                        ->where('sign_up_date <=', $endDt)
                    ->group_end()
                 ->group_end();
        $applyKeywords();
        $this->db->group_start()
                 ->where_in('status', $leadStatuses)
                 ->or_where_in('lead_outcome', $leadOutcomes)
                 ->group_end();
        $mtdLeadCount = (int)$this->db->count_all_results();
    
        // 2) Signed
        $this->db->reset_query();
        $this->db->from('leads_tracker');
        $this->db->where('sign_up_date >=', $startDt)
                 ->where('sign_up_date <=', $endDt);
        $applyKeywords();
        $this->db->where('lead_outcome', 'Signed');
        $mtdSignCount = (int)$this->db->count_all_results();
    
        // 3) Referred out
        $this->db->reset_query();
        $this->db->from('leads_tracker');
        $this->db->where('sign_up_date >=', $startDt)
                 ->where('sign_up_date <=', $endDt);
        $applyKeywords();
        $this->db->where('lead_outcome', 'Referred out');
        $referredCount = (int)$this->db->count_all_results();
    
        $totalSigned   = $mtdSignCount + $referredCount;
        $mtdAcqRate    = $mtdLeadCount > 0 ? round(($totalSigned / $mtdLeadCount) * 100, 2) : 0.00;
    
        $pctToGoalLeads   = $targetLead   > 0 ? round(($mtdLeadCount / $targetLead) * 100, 2) : 0.00;
        $pctToGoalSignups = $targetSignup > 0 ? round(($totalSigned  / $targetSignup) * 100, 2) : 0.00;
    
        $targetAR = ($targetLead > 0) ? ($targetSignup / $targetLead) : 0;
        $actualAR = ($mtdLeadCount > 0) ? ($totalSigned / $mtdLeadCount) : 0;
        $pctToGoalAR = ($targetAR > 0) ? round(($actualAR / $targetAR) * 100, 2) : 0.00;
    
        /** ------------------- GROUPED TABLES ------------------- **/
        // A) In-House Sign Up Summary: count by marketing_source where (Signed or Referred out)
        $this->db->reset_query();
        $this->db->select('marketing_source, COUNT(*) AS total_signed', false);
        $this->db->from('leads_tracker');
        $this->db->where('sign_up_date >=', $startDt)->where('sign_up_date <=', $endDt);
        $applyKeywords();
        $this->db->where_in('lead_outcome', $leadOutcomes);
        $this->db->group_by('marketing_source');
        $this->db->order_by('total_signed', 'DESC');
        $inhouseSignupSummary = $this->db->get()->result_array();
    
        // B) Category (value) summary
        $this->db->reset_query();
        $this->db->select("COALESCE(NULLIF(TRIM(value),''),'Uncategorized') AS value, COUNT(*) AS total_signed", false);
        $this->db->from('leads_tracker');
        $this->db->where('sign_up_date >=', $startDt)->where('sign_up_date <=', $endDt);
        $applyKeywords();
        $this->db->where_in('lead_outcome', $leadOutcomes);
        $this->db->group_by('value');
        $this->db->order_by('total_signed', 'DESC');
        $categorySignupSummary = $this->db->get()->result_array();
    
        // C) Referred summary (status vs outcome) per marketing_source
        $this->db->reset_query();
        $sql = "
          SELECT
            marketing_source,
            SUM(CASE WHEN status = 'Referred' THEN 1 ELSE 0 END) AS total_referred,
            SUM(CASE WHEN lead_outcome = 'Referred out' THEN 1 ELSE 0 END) AS successful_referred_count
          FROM leads_tracker
          WHERE (sign_up_date BETWEEN ? AND ?)
            AND (".implode(' OR ', array_fill(0, count($keywords), 'LOWER(marketing_source) LIKE ?')).")
          GROUP BY marketing_source
          ORDER BY successful_referred_count DESC, total_referred DESC
        ";
        $binds = [$startDt, $endDt];
        foreach ($keywords as $kw) { $binds[] = '%'.strtolower($kw).'%'; }
        $referredSummary = $this->db->query($sql, $binds)->result_array();
    
        // D) In-House Signup Details (Signed only) – keep
        $this->db->reset_query();
        $this->db->select('marketing_source, value, case_type, full_name AS client_name, sign_up_date');
        $this->db->from('leads_tracker');
        $this->db->where('sign_up_date >=', $startDt)->where('sign_up_date <=', $endDt);
        $applyKeywords();
        $this->db->where('lead_outcome', 'Signed');
        $this->db->order_by('sign_up_date', 'DESC');
        $this->db->limit(100);
        $inhouseSignupDetails = $this->db->get()->result_array();
    
        // E1) Signup + Referred GROUPS (Signed or Referred out) grouped by marketing_source & lead_outcome
        $this->db->reset_query();
        $this->db->select('marketing_source, lead_outcome, COUNT(*) AS group_count', false);
        $this->db->from('leads_tracker');
        $this->db->where('sign_up_date >=', $startDt)->where('sign_up_date <=', $endDt);
        $applyKeywords();
        $this->db->where_in('lead_outcome', $leadOutcomes);
        $this->db->group_by(['marketing_source', 'lead_outcome']);
        $this->db->order_by('group_count', 'DESC');
        $signupReferredGroups = $this->db->get()->result_array();
    
        // E2) Example DETAILS we can show under each group (latest rows)
        $this->db->reset_query();
        $this->db->select('marketing_source, value, case_type, lead_outcome, sign_up_date');
        $this->db->from('leads_tracker');
        $this->db->where('sign_up_date >=', $startDt)->where('sign_up_date <=', $endDt);
        $applyKeywords();
        $this->db->where_in('lead_outcome', $leadOutcomes);
        $this->db->order_by('sign_up_date', 'DESC');
        $this->db->limit(300);
        $signupReferredExamples = $this->db->get()->result_array();
    
        return [
            'range' => ['start' => $start, 'end' => $end],
            'targets' => [
                'lead' => (int)$targetLead,
                'signup' => (int)$targetSignup,
                'target_acquisition_rate' => $targetAR > 0 ? round($targetAR * 100, 2) : 0.00
            ],
            'kpis' => [
                'mtd_lead_count' => $mtdLeadCount,
                'mtd_sign_count' => $mtdSignCount,
                'referred_count' => $referredCount,
                'total_signed' => $totalSigned,
                'mtd_acquisition_rate' => $mtdAcqRate,
                'pct_to_goal' => [
                    'leads' => $pctToGoalLeads,
                    'signups' => $pctToGoalSignups,
                    'acquisition_rate' => $pctToGoalAR
                ]
            ],
            'tables' => [
                'inhouse_signup_summary'     => $inhouseSignupSummary,
                'category_signup_summary'    => $categorySignupSummary,
                'referred_summary'           => $referredSummary,
                'inhouse_signup_details'     => $inhouseSignupDetails,
                // NEW: groups + example rows for rendering
                'signup_referred_details'    => $signupReferredGroups,
                'signup_referred_examples'   => $signupReferredExamples,
            ],
        ];
    }

    
    /** POST /api/report/send-summary
     *  Body/Query: start_date, end_date, target_lead? , target_signup? , to? (default ray@farahilaw.com)
     *  Sends KPI cards + grouped tables as an HTML email.
     */
    function summary_get()
    {
        try {
            $start = $this->input->post('start_date', true) ?: $this->input->get('start_date', true);
            $end   = $this->input->post('end_date', true)   ?: $this->input->get('end_date', true);
            if (!$start || !$end) {
                http_response_code(400);
                echo json_encode(['ok' => false, 'error' => 'start_date and end_date are required (YYYY-MM-DD).']);
                return;
            }
    
            $targetLead   = (int)($this->input->post('target_lead')   ?: $this->input->get('target_lead')   ?: 250);
            $targetSignup = (int)($this->input->post('target_signup') ?: $this->input->get('target_signup') ?: 45);
            // $toEmail      = trim($this->input->post('to') ?: $this->input->get('to') ?: 'ray@farahilaw.com');
            $toEmail      = trim($this->input->post('to') ?: $this->input->get('to') ?: 'ray@farahilaw.com, ninasioson@farahilaw.com');
    
            $data = $this->compute_kpis($start, $end, $targetLead, $targetSignup);
    
            // ---- Formatted values
            $titleRange = $data['range']['start'] . ' to ' . $data['range']['end'];
            $tLead   = number_format($data['targets']['lead']);
            $tSignup = number_format($data['targets']['signup']);
            $tAR     = number_format($data['targets']['target_acquisition_rate'], 2) . '%';
    
            $mtdLeads   = number_format($data['kpis']['mtd_lead_count']);
            $mtdSign    = number_format($data['kpis']['mtd_sign_count']);
            $mtdRef     = number_format($data['kpis']['referred_count']);
            $total      = number_format($data['kpis']['total_signed']);
            $mtdAR      = number_format($data['kpis']['mtd_acquisition_rate'], 2) . '%';
    
            $pctLeads   = number_format($data['kpis']['pct_to_goal']['leads'], 2) . '%';
            $pctSign    = number_format($data['kpis']['pct_to_goal']['signups'], 2) . '%';
            $pctAR      = number_format($data['kpis']['pct_to_goal']['acquisition_rate'], 2) . '%';
    
            // Subject + preheader
            $subject   = "MTD Marketing Performance Summary — {$titleRange}";
            $preheader = "Targets: Leads {$tLead}, Sign-ups {$tSignup}, AR {$tAR} • MTD: Leads {$mtdLeads}, Signed {$mtdSign} (+{$mtdRef} ref), AR {$mtdAR}";
    
            // Colors for thresholds
            $leadPctOfTarget   = ($data['targets']['lead']   > 0) ? ($data['kpis']['mtd_lead_count'] / $data['targets']['lead']) * 100 : 0;
            $signedPctOfTarget = ($data['targets']['signup'] > 0) ? ($data['kpis']['total_signed']   / $data['targets']['signup']) * 100 : 0;
            $arPctToGoal       = $data['kpis']['pct_to_goal']['acquisition_rate'];
    
            $meterColor = function($pct) {
                if ($pct < 50)  return '#E74C3C';
                if ($pct < 100) return '#F39C12';
                return '#2ECC71';
            };
            $cap  = function($n) { return max(0, min(100, (float)$n)); };
            $fmtP = function($n) { return number_format((float)$n, 2) . '%'; };
    
            $leadBar   = $meterColor($leadPctOfTarget);
            $signedBar = $meterColor($signedPctOfTarget);
            $arBar     = $meterColor($arPctToGoal);
    
            // ------- helper to safely escape cell text -------
            $e = function($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); };
    
            // ------- build table HTML fragments from data -------
            $tbl = $data['tables'];
    
            // In-House Sign Up Summary
            $htmlInhouseSummary = '';
            if (!empty($tbl['inhouse_signup_summary'])) {
                $rows = '';
                foreach ($tbl['inhouse_signup_summary'] as $r) {
                    $rows .= '<tr>
                        <td style="padding:8px;border-bottom:1px solid #e6edf7;">'.$e($r['marketing_source']).'</td>
                        <td style="padding:8px;border-bottom:1px solid #e6edf7;text-align:right;">'.$e($r['total_signed']).'</td>
                    </tr>';
                }
                $htmlInhouseSummary = '
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                  <tr><td style="padding:8px 0;font-weight:700;">In-House Sign Up Summary</td></tr>
                </table>
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #d7e6fb;border-radius:6px;overflow:hidden;">
                  <tr style="background:#eaf2fe;">
                    <th align="left"  style="padding:8px;font-size:12px;">Lead Source</th>
                    <th align="right" style="padding:8px;font-size:12px;">Sign Ups</th>
                  </tr>'.$rows.'
                </table>';
            }
    
            // Category summary
            $htmlCategory = '';
            if (!empty($tbl['category_signup_summary'])) {
                $rows = '';
                foreach ($tbl['category_signup_summary'] as $r) {
                    $rows .= '<tr>
                        <td style="padding:8px;border-bottom:1px solid #e6edf7;">'.$e($r['value']).'</td>
                        <td style="padding:8px;border-bottom:1px solid #e6edf7;text-align:right;">'.$e($r['total_signed']).'</td>
                    </tr>';
                }
                $htmlCategory = '
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;margin-top:16px;">
                  <tr><td style="padding:8px 0;font-weight:700;">Category</td></tr>
                </table>
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #d7e6fb;border-radius:6px;overflow:hidden;">
                  <tr style="background:#eaf2fe;">
                    <th align="left"  style="padding:8px;font-size:12px;">Lead Source</th>
                    <th align="right" style="padding:8px;font-size:12px;">Sign Ups</th>
                  </tr>'.$rows.'
                </table>';
            }
    
            // Referred summary
            $htmlReferred = '';
            if (!empty($tbl['referred_summary'])) {
                $rows = '';
                foreach ($tbl['referred_summary'] as $r) {
                    $rows .= '<tr>
                        <td style="padding:8px;border-bottom:1px solid #e6edf7;">'.$e($r['marketing_source']).'</td>
                        <td style="padding:8px;border-bottom:1px solid #e6edf7;text-align:right;">'.$e($r['total_referred']).'</td>
                        <td style="padding:8px;border-bottom:1px solid #e6edf7;text-align:right;">'.$e($r['successful_referred_count']).'</td>
                    </tr>';
                }
                $htmlReferred = '
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;margin-top:16px;">
                  <tr><td style="padding:8px 0;font-weight:700;">Successful Referred Out</td></tr>
                </table>
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #d7e6fb;border-radius:6px;overflow:hidden;">
                  <tr style="background:#eaf2fe;">
                    <th align="left"  style="padding:8px;font-size:12px;">Referred From</th>
                    <th align="right" style="padding:8px;font-size:12px;">Total Referred</th>
                    <th align="right" style="padding:8px;font-size:12px;">Successful Referred Out</th>
                  </tr>'.$rows.'
                </table>';
            }
    
            // In-House Signup Details (Signed)
            $htmlInhouseDetails = '';
            if (!empty($tbl['inhouse_signup_details'])) {
                $rows = '';
                foreach ($tbl['inhouse_signup_details'] as $r) {
                    $rows .= '<tr>
                        <td style="padding:8px;border-bottom:1px solid #e6edf7;">'.$e($r['marketing_source']).'</td>
                        <td style="padding:8px;border-bottom:1px solid #e6edf7;">'.$e($r['value']).'</td>
                        <td style="padding:8px;border-bottom:1px solid #e6edf7;">'.$e($r['case_type']).'</td>
                        <td style="padding:8px;border-bottom:1px solid #e6edf7;">'.$e($r['client_name']).'</td>
                        <td style="padding:8px;border-bottom:1px solid #e6edf7;">'.$e($r['sign_up_date']).'</td>
                    </tr>';
                }
                $htmlInhouseDetails = '
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;margin-top:16px;">
                  <tr><td style="padding:8px 0;font-weight:700;">In-House Signup Details (Signed)</td></tr>
                </table>
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #d7e6fb;border-radius:6px;overflow:hidden;">
                  <tr style="background:#eaf2fe;">
                    <th align="left"  style="padding:8px;font-size:12px;">Lead Source</th>
                    <th align="left"  style="padding:8px;font-size:12px;">Case Value</th>
                    <th align="left"  style="padding:8px;font-size:12px;">Case Type</th>
                    <th align="left"  style="padding:8px;font-size:12px;">Client Name</th>
                    <th align="left"  style="padding:8px;font-size:12px;">Signed Date</th>
                  </tr>'.$rows.'
                </table>
                <div style="font-size:11px;color:#666;margin-top:4px;">Showing up to 100 recent signed records.</div>';
            }
    
            // ----- Build main email (cards + appended tables) -----
            $messageTop = '
            <div style="margin:0 auto;max-width:640px;background:#ffffff;font-family:Arial,Helvetica,sans-serif;color:#111;">
              <div style="display:none;max-height:0;overflow:hidden;opacity:0;color:transparent;">'
                . htmlspecialchars($preheader, ENT_QUOTES, "UTF-8") .
              '</div>
            
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                <tr>
                  <td style="padding:24px 16px 8px 16px;text-align:center;">
                    <div style="font-size:13px;letter-spacing:.5px;color:#223;">DIGITAL MARKETING DEPARTMENT</div>
                    <div style="font-size:22px;font-weight:700;margin-top:6px;">MTD MARKETING PERFORMANCE SUMMARY</div>
                    <div style="font-size:14px;color:#334;margin-top:6px;">'
                      . htmlspecialchars($titleRange, ENT_QUOTES, "UTF-8") .
                    '</div>
                  </td>
                </tr>
              </table>
            
              <!-- Targets -->
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;padding:0 12px;">
                <tr>
                  <td style="width:33.33%;padding:8px;">
                    <table role="presentation" width="100%" style="background:#b4d4f5;border-radius:8px;border-collapse:separate;">
                      <tr><td style="padding:14px 16px;">
                        <div style="font-size:12px;font-weight:700;">Target Leads</div>
                        <div style="font-size:22px;margin-top:4px;">'.$tLead.'</div>
                      </td></tr>
                    </table>
                  </td>
                  <td style="width:33.33%;padding:8px;">
                    <table role="presentation" width="100%" style="background:#b4d4f5;border-radius:8px;border-collapse:separate;">
                      <tr><td style="padding:14px 16px;">
                        <div style="font-size:12px;font-weight:700;">Target Sign-ups</div>
                        <div style="font-size:22px;margin-top:4px;">'.$tSignup.'</div>
                      </td></tr>
                    </table>
                  </td>
                  <td style="width:33.33%;padding:8px;">
                    <table role="presentation" width="100%" style="background:#b4d4f5;border-radius:8px;border-collapse:separate;">
                      <tr><td style="padding:14px 16px;">
                        <div style="font-size:12px;font-weight:700;">Target Acquisition Rate</div>
                        <div style="font-size:22px;margin-top:4px;">'.$tAR.'</div>
                      </td></tr>
                    </table>
                  </td>
                </tr>
              </table>
            
              <!-- MTD row -->
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;padding:0 12px;">
                <tr>
                  <td style="width:33.33%;padding:8px;vertical-align:top;">
                    <table role="presentation" width="100%" style="background:#b4d4f5;border-radius:8px;border-collapse:separate;">
                      <tr><td style="padding:14px 16px;border-bottom:5px solid '.$leadBar.';">
                        <div style="font-size:12px;font-weight:700;">MTD Leads</div>
                        <div style="font-size:22px;margin-top:4px;">'.$mtdLeads.'</div>
                        <div style="margin-top:8px;background:#e9f2fc;height:6px;border-radius:4px;">
                          <div style="height:6px;border-radius:4px;background:'.$leadBar.';width:'.$cap($leadPctOfTarget).'%;"></div>
                        </div>
                        <div style="font-size:11px;color:#333;margin-top:6px;">'.$fmtP($leadPctOfTarget).' of target</div>
                      </td></tr>
                    </table>
                  </td>
                  <td style="width:33.33%;padding:8px;vertical-align:top;">
                    <table role="presentation" width="100%" style="background:#b4d4f5;border-radius:8px;border-collapse:separate;">
                      <tr><td style="padding:12px 12px;border-bottom:5px solid '.$signedBar.';">
                        <table role="presentation" width="100%" style="border-collapse:collapse;border-bottom:1px solid #00000033;">
                          <tr>
                            <td style="text-align:center;padding:6px 4px;">
                              <div style="font-size:11px;font-weight:700;">MTD Client Sign-ups</div>
                              <div style="font-size:18px;margin-top:2px;">'.$mtdSign.'</div>
                            </td>
                            <td style="text-align:center;padding:6px 4px;">
                              <div style="font-size:11px;font-weight:700;">Successful Referrals</div>
                              <div style="font-size:18px;margin-top:2px;">'.$mtdRef.'</div>
                            </td>
                          </tr>
                        </table>
                        <div style="text-align:center;font-size:22px;margin-top:8px;">'.$total.'</div>
                        <div style="margin-top:8px;background:#e9f2fc;height:6px;border-radius:4px;">
                          <div style="height:6px;border-radius:4px;background:'.$signedBar.';width:'.$cap($signedPctOfTarget).'%;"></div>
                        </div>
                        <div style="font-size:11px;color:#333;margin-top:6px;">'.$fmtP($signedPctOfTarget).' of target</div>
                      </td></tr>
                    </table>
                  </td>
                  <td style="width:33.33%;padding:8px;vertical-align:top;">
                    <table role="presentation" width="100%" style="background:#b4d4f5;border-radius:8px;border-collapse:separate;">
                      <tr><td style="padding:14px 16px;border-bottom:5px solid '.$arBar.';">
                        <div style="font-size:12px;font-weight:700;">MTD Acquisition Rate</div>
                        <div style="font-size:22px;margin-top:4px;">'.$mtdAR.'</div>
                        <div style="margin-top:8px;background:#e9f2fc;height:6px;border-radius:4px;">
                          <div style="height:6px;border-radius:4px;background:'.$arBar.';width:'.$cap($arPctToGoal).'%;"></div>
                        </div>
                        <div style="font-size:11px;color:#333;margin-top:6px;">'.$fmtP($arPctToGoal).' of target</div>
                      </td></tr>
                    </table>
                  </td>
                </tr>
              </table>
            
              <!-- % to Goal row -->
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;padding:0 12px;">
                <tr>
                  <td style="width:33.33%;padding:8px;">
                    <table role="presentation" width="100%" style="background:#b4d4f5;border-radius:8px;border-collapse:separate;">
                      <tr><td style="padding:14px 16px;">
                        <div style="font-size:12px;font-weight:700;">% to Goal (Leads)</div>
                        <div style="font-size:22px;margin-top:4px;">'.$pctLeads.'</div>
                      </td></tr>
                    </table>
                  </td>
                  <td style="width:33.33%;padding:8px;">
                    <table role="presentation" width="100%" style="background:#b4d4f5;border-radius:8px;border-collapse:separate;">
                      <tr><td style="padding:14px 16px;">
                        <div style="font-size:12px;font-weight:700;">% to Goal (Sign-ups)</div>
                        <div style="font-size:22px;margin-top:4px;">'.$pctSign.'</div>
                      </td></tr>
                    </table>
                  </td>
                  <td style="width:33.33%;padding:8px;">
                    <table role="presentation" width="100%" style="background:#b4d4f5;border-radius:8px;border-collapse:separate;">
                      <tr><td style="padding:14px 16px;">
                        <div style="font-size:12px;font-weight:700;">% to Goal (AR)</div>
                        <div style="font-size:22px;margin-top:4px;">'.$pctAR.'</div>
                      </td></tr>
                    </table>
                  </td>
                </tr>
              </table>
            
              <!-- TABLES -->
              <div style="padding:0 12px 8px 12px;">
                '.$htmlInhouseSummary.'
                '.$htmlCategory.'
                '.$htmlReferred.'
                '.$htmlInhouseDetails.'
              </div>
            
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                <tr>
                  <td style="padding:14px 16px 28px 16px;">
                    <div style="font-size:12px;color:#666;line-height:1.4;">
                      Sent automatically from the KPI dashboard. If anything looks off, please check the date range or targets on the page.
                    </div>
                  </td>
                </tr>
              </table>
            </div>';
    
            $message = $messageTop;
    
            // ---- Send email (CI Email library) ----
            $this->load->library('email');
    
            if (!$this->config->item('protocol')) {
                $this->email->initialize([
                    'protocol'   => 'smtp',
                    'smtp_host'  => 'smtp.gmail.com',
                    'smtp_user'  => 'ray@farahilaw.com', // TODO: replace
                    'smtp_pass'  => 'knmi zhok clwk wjyq',       // TODO: use app password / env
                    'smtp_port'  => 587,
                    'smtp_crypto'=> 'tls',
                    'mailtype'   => 'html',
                    'charset'    => 'utf-8',
                    'newline'    => "\r\n",
                    'crlf'       => "\r\n",
                ]);
            }
    
            $this->email->from('no-reply@farahilaw.com', 'KPI Dashboard');
            $this->email->to($toEmail);
            $this->email->subject($subject);
            $this->email->message($message);
    
            if (!$this->email->send()) {
                $debug = method_exists($this->email, 'print_debugger') ? $this->email->print_debugger(['headers']) : 'send failed';
                http_response_code(500);
                echo json_encode(['ok' => false, 'error' => 'Email send failed', 'debug' => $debug]);
                return;
            }
    
            echo json_encode([
                'ok' => true,
                'sent_to' => $toEmail,
                'range' => $data['range'],
                'kpis' => $data['kpis'],
                'tables_counts' => [
                    'inhouse_signup_summary'  => count($data['tables']['inhouse_signup_summary']),
                    'category_signup_summary' => count($data['tables']['category_signup_summary']),
                    'referred_summary'        => count($data['tables']['referred_summary']),
                    'inhouse_signup_details'  => count($data['tables']['inhouse_signup_details']),
                ]
            ]);
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
        }
    }
    
    function successful_signup_get()
    {
        try {
            $start = date('Y-m-01'); // First day of current month
            $end   = date('Y-m-d');  // Today's date
            if (!$start || !$end) {
                http_response_code(400);
                echo json_encode(['ok' => false, 'error' => 'start_date and end_date are required (YYYY-MM-DD).']);
                return;
            }
    
            $targetLead   = (int)($this->input->post('target_lead')   ?: $this->input->get('target_lead')   ?: 250);
            $targetSignup = (int)($this->input->post('target_signup') ?: $this->input->get('target_signup') ?: 45);
            // $toEmail      = trim($this->input->post('to') ?: $this->input->get('to') ?: 'ray@farahilaw.com');
            $toEmail      = trim($this->input->post('to') ?: $this->input->get('to') ?: 'ray@farahilaw.com, ninasioson@farahilaw.com');
    
            $data = $this->compute_kpis($start, $end, $targetLead, $targetSignup);
    
            // ---- Formatted values
            $titleRange = $data['range']['start'] . ' to ' . $data['range']['end'];
            $tLead   = number_format($data['targets']['lead']);
            $tSignup = number_format($data['targets']['signup']);
            $tAR     = number_format($data['targets']['target_acquisition_rate'], 2) . '%';
    
            $mtdLeads   = number_format($data['kpis']['mtd_lead_count']);
            $mtdSign    = number_format($data['kpis']['mtd_sign_count']);
            $mtdRef     = number_format($data['kpis']['referred_count']);
            $total      = number_format($data['kpis']['total_signed']);
            $mtdAR      = number_format($data['kpis']['mtd_acquisition_rate'], 2) . '%';
    
            $pctLeads   = number_format($data['kpis']['pct_to_goal']['leads'], 2) . '%';
            $pctSign    = number_format($data['kpis']['pct_to_goal']['signups'], 2) . '%';
            $pctAR      = number_format($data['kpis']['pct_to_goal']['acquisition_rate'], 2) . '%';
    
            // Subject + preheader
            $subject   = "SIGNUP ANNOUNCEMENT — {$titleRange}";
            $preheader = "Targets: Leads {$tLead}, Sign-ups {$tSignup}, AR {$tAR} • MTD: Leads {$mtdLeads}, Signed {$mtdSign} (+{$mtdRef} ref), AR {$mtdAR}";
    
            // Colors for thresholds
            $leadPctOfTarget   = ($data['targets']['lead']   > 0) ? ($data['kpis']['mtd_lead_count'] / $data['targets']['lead']) * 100 : 0;
            $signedPctOfTarget = ($data['targets']['signup'] > 0) ? ($data['kpis']['total_signed']   / $data['targets']['signup']) * 100 : 0;
            $arPctToGoal       = $data['kpis']['pct_to_goal']['acquisition_rate'];
    
            $meterColor = function($pct) {
                if ($pct < 50)  return '#E74C3C';
                if ($pct < 100) return '#F39C12';
                return '#2ECC71';
            };
            $leadBar   = $meterColor($leadPctOfTarget);
            $signedBar = $meterColor($signedPctOfTarget);
            $arBar     = $meterColor($arPctToGoal);
    
            // ------- helper to safely escape cell text -------
            $e = function($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); };
    
            // ------- tables data -------
            $tbl = $data['tables'];
    
            /* ===================  A) GROUPED Signup/Referral block  =================== */
            $outcomeLabel = function($outcome) {
                return $outcome === 'Referred out' ? 'Successful Referral' : 'In-House Signup';
            };
    
            // Index: one latest example per (marketing_source|lead_outcome)
            $exampleIndex = [];
            if (!empty($tbl['signup_referred_examples'])) {
                foreach ($tbl['signup_referred_examples'] as $row) {
                    $k = strtolower(trim($row['marketing_source'])) . '|' . $row['lead_outcome'];
                    if (!isset($exampleIndex[$k])) $exampleIndex[$k] = $row; // already DESC by date
                }
            }
    
            $htmlSignupReferred = '';
            if (!empty($tbl['signup_referred_details'])) {
                $i = 0; $blocks = '';
                foreach ($tbl['signup_referred_details'] as $g) {
                    $i++;
                    $source   = (string)$g['marketing_source'];
                    $outcome  = (string)$g['lead_outcome'];
                    $count    = (int)$g['group_count'];
                    $label    = $outcomeLabel($outcome);
    
                    $k = strtolower(trim($source)) . '|' . $outcome;
                    $ex = isset($exampleIndex[$k]) ? $exampleIndex[$k] : null;
    
                    $value     = $ex ? $ex['value']      : '';
                    $caseType  = $ex ? $ex['case_type']  : '';
                    // format example date — change to 'Y-m-d' if you prefer
                    $contactDt = $ex && !empty($ex['sign_up_date']) ? date('n/j/Y', strtotime($ex['sign_up_date'])) : '';
    
                    // Three-line block per group
                    $blocks .= '
                      <tr>
                        <td style="padding:10px 12px;border-bottom:1px solid #e6edf7;">
                          <div style="font-weight:700;">'.$e($i.' '.$source.' '.$label).'</div>
                          <div style="margin-top:3px;">'.$e($source.' - '.($value ?: 'Uncategorized').' ('.($caseType ?: 'N/A').')').'</div>
                          <div style="margin-top:3px;color:#444;">Date of Contact - '.$e($contactDt ?: 'N/A').'</div>
                          <div style="margin-top:3px;font-size:11px;color:#666;">Group Count: '.$e($count).'</div>
                        </td>
                      </tr>';
                }
    
                $htmlSignupReferred = '
                  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;margin-top:16px;">
                    <tr><td style="padding:8px 0;font-weight:700;">Signup & Successful Referral Details</td></tr>
                  </table>
                  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #d7e6fb;border-radius:6px;overflow:hidden;">
                    '.$blocks.'
                  </table>
                  <div style="font-size:11px;color:#666;margin-top:4px;">Grouped by Lead Source & Outcome. Showing latest example per group.</div>';
            }
    
            /* ===================  B) In-House Signup Details (Signed only)  =================== */
            $htmlInhouseDetails = '';
            if (!empty($tbl['inhouse_signup_details'])) {
                $rows = '';
                foreach ($tbl['inhouse_signup_details'] as $r) {
                    $rows .= '<tr>
                        <td style="padding:8px;border-bottom:1px solid #e6edf7;">'.$e($r['marketing_source']).'</td>
                        <td style="padding:8px;border-bottom:1px solid #e6edf7;">'.$e($r['value']).'</td>
                        <td style="padding:8px;border-bottom:1px solid #e6edf7;">'.$e($r['case_type']).'</td>
                        <td style="padding:8px;border-bottom:1px solid #e6edf7;">'.$e($r['sign_up_date']).'</td>
                    </tr>';
                }
                $htmlInhouseDetails = '
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;margin-top:16px;">
                  <tr><td style="padding:8px 0;font-weight:700;">In-House Signup Details (Signed)</td></tr>
                </table>
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #d7e6fb;border-radius:6px;overflow:hidden;">
                  <tr style="background:#eaf2fe;">
                    <th align="left"  style="padding:8px;font-size:12px;">Lead Source</th>
                    <th align="left"  style="padding:8px;font-size:12px;">Case Value</th>
                    <th align="left"  style="padding:8px;font-size:12px;">Case Type</th>
                    <th align="left"  style="padding:8px;font-size:12px;">Signed Date</th>
                  </tr>'.$rows.'
                </table>
                <div style="font-size:11px;color:#666;margin-top:4px;">Showing up to 100 recent signed records.</div>';
            }
    
            /* ===================  C) FULL header + KPI cards block (RESTORED)  =================== */
            $messageTop = '
            <div style="margin:0 auto;max-width:640px;background:#ffffff;font-family:Arial,Helvetica,sans-serif;color:#111;">
              <div style="display:none;max-height:0;overflow:hidden;opacity:0;color:transparent;">'
                . htmlspecialchars($preheader, ENT_QUOTES, "UTF-8") .
              '</div>
    
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                <tr>
                  <td style="padding:24px 16px 8px 16px;text-align:center;">
                    <div style="font-size:13px;letter-spacing:.5px;color:#223;">DIGITAL MARKETING DEPARTMENT</div>
                    <div style="font-size:22px;font-weight:700;margin-top:6px;">SUCCESSFUL SIGNUP ANNOUNCEMENT</div>
                    <div style="font-size:14px;color:#334;margin-top:6px;">'
                      . htmlspecialchars($titleRange, ENT_QUOTES, "UTF-8") .
                    '</div>
                  </td>
                </tr>
              </table>
    
              <!-- Targets -->
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;padding:0 12px;">
                <tr>
                  <td style="width:33.33%;padding:8px;">
                    <table role="presentation" width="100%" style="background:#b4d4f5;border-radius:8px;border-collapse:separate;">
                      <tr><td style="padding:14px 16px;">
                        <div style="font-size:12px;font-weight:700;">Target Leads</div>
                        <div style="font-size:22px;margin-top:4px;">'.$tLead.'</div>
                      </td></tr>
                    </table>
                  </td>
                  <td style="width:33.33%;padding:8px;">
                    <table role="presentation" width="100%" style="background:#b4d4f5;border-radius:8px;border-collapse:separate;">
                      <tr><td style="padding:14px 16px;">
                        <div style="font-size:12px;font-weight:700;">Target Sign-ups</div>
                        <div style="font-size:22px;margin-top:4px;">'.$tSignup.'</div>
                      </td></tr>
                    </table>
                  </td>
                  <td style="width:33.33%;padding:8px;">
                    <table role="presentation" width="100%" style="background:#b4d4f5;border-radius:8px;border-collapse:separate;">
                      <tr><td style="padding:14px 16px;">
                        <div style="font-size:12px;font-weight:700;">Target Acquisition Rate</div>
                        <div style="font-size:22px;margin-top:4px;">'.$tAR.'</div>
                      </td></tr>
                    </table>
                  </td>
                </tr>
              </table>
    
              <!-- MTD row -->
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;padding:0 12px;">
                <tr>
                  <td style="width:33.33%;padding:8px;vertical-align:top;">
                    <table role="presentation" width="100%" style="background:#b4d4f5;border-radius:8px;border-collapse:separate;">
                      <tr><td style="padding:14px 16px;border-bottom:5px solid '.$leadBar.';">
                        <div style="font-size:12px;font-weight:700;">MTD Leads</div>
                        <div style="font-size:22px;margin-top:4px;">'.$mtdLeads.'</div>
                        <div style="margin-top:8px;background:#e9f2fc;height:6px;border-radius:4px;">
                          <div style="height:6px;border-radius:4px;background:'.$leadBar.';width:'.max(0,min(100,(float)$leadPctOfTarget)).'%;"></div>
                        </div>
                        <div style="font-size:11px;color:#333;margin-top:6px;">'.number_format((float)$leadPctOfTarget,2).'% of target</div>
                      </td></tr>
                    </table>
                  </td>
                  <td style="width:33.33%;padding:8px;vertical-align:top;">
                    <table role="presentation" width="100%" style="background:#b4d4f5;border-radius:8px;border-collapse:separate;">
                      <tr><td style="padding:12px 12px;border-bottom:5px solid '.$signedBar.';">
                        <table role="presentation" width="100%" style="border-collapse:collapse;border-bottom:1px solid #00000033;">
                          <tr>
                            <td style="text-align:center;padding:6px 4px;">
                              <div style="font-size:11px;font-weight:700;">MTD Client Sign-ups</div>
                              <div style="font-size:18px;margin-top:2px;">'.$mtdSign.'</div>
                            </td>
                            <td style="text-align:center;padding:6px 4px;">
                              <div style="font-size:11px;font-weight:700;">Successful Referrals</div>
                              <div style="font-size:18px;margin-top:2px;">'.$mtdRef.'</div>
                            </td>
                          </tr>
                        </table>
                        <div style="text-align:center;font-size:22px;margin-top:8px;">'.$total.'</div>
                        <div style="margin-top:8px;background:#e9f2fc;height:6px;border-radius:4px;">
                          <div style="height:6px;border-radius:4px;background:'.$signedBar.';width:'.max(0,min(100,(float)$signedPctOfTarget)).'%;"></div>
                        </div>
                        <div style="font-size:11px;color:#333;margin-top:6px;">'.number_format((float)$signedPctOfTarget,2).'% of target</div>
                      </td></tr>
                    </table>
                  </td>
                  <td style="width:33.33%;padding:8px;vertical-align:top;">
                    <table role="presentation" width="100%" style="background:#b4d4f5;border-radius:8px;border-collapse:separate;">
                      <tr><td style="padding:14px 16px;border-bottom:5px solid '.$arBar.';">
                        <div style="font-size:12px;font-weight:700;">MTD Acquisition Rate</div>
                        <div style="font-size:22px;margin-top:4px;">'.$mtdAR.'</div>
                        <div style="margin-top:8px;background:#e9f2fc;height:6px;border-radius:4px;">
                          <div style="height:6px;border-radius:4px;background:'.$arBar.';width:'.max(0,min(100,(float)$arPctToGoal)).'%;"></div>
                        </div>
                        <div style="font-size:11px;color:#333;margin-top:6px;">'.number_format((float)$arPctToGoal,2).'% of goal</div>
                      </td></tr>
                    </table>
                  </td>
                </tr>
              </table>
    
              <!-- % to Goal row -->
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;padding:0 12px;">
                <tr>
                  <td style="width:33.33%;padding:8px;">
                    <table role="presentation" width="100%" style="background:#b4d4f5;border-radius:8px;border-collapse:separate;">
                      <tr><td style="padding:14px 16px;">
                        <div style="font-size:12px;font-weight:700;">% to Goal (Leads)</div>
                        <div style="font-size:22px;margin-top:4px;">'.$pctLeads.'</div>
                      </td></tr>
                    </table>
                  </td>
                  <td style="width:33.33%;padding:8px;">
                    <table role="presentation" width="100%" style="background:#b4d4f5;border-radius:8px;border-collapse:separate;">
                      <tr><td style="padding:14px 16px;">
                        <div style="font-size:12px;font-weight:700;">% to Goal (Sign-ups)</div>
                        <div style="font-size:22px;margin-top:4px;">'.$pctSign.'</div>
                      </td></tr>
                    </table>
                  </td>
                  <td style="width:33.33%;padding:8px;">
                    <table role="presentation" width="100%" style="background:#b4d4f5;border-radius:8px;border-collapse:separate;">
                      <tr><td style="padding:14px 16px;">
                        <div style="font-size:12px;font-weight:700;">% to Goal (AR)</div>
                        <div style="font-size:22px;margin-top:4px;">'.$pctAR.'</div>
                      </td></tr>
                    </table>
                  </td>
                </tr>
              </table>
    
              <!-- TABLES -->
              <div style="padding:0 12px 8px 12px;">
                <!-- tables will be appended here -->
                __TABLES_SLOT__
              </div>
    
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                <tr>
                  <td style="padding:14px 16px 28px 16px;">
                    <div style="font-size:12px;color:#666;line-height:1.4;">
                      Sent automatically from the KPI dashboard. If anything looks off, please check the date range or targets on the page.
                    </div>
                  </td>
                </tr>
              </table>
            </div>';
    
            /* ===================  D) Assemble final message with tables injected  =================== */
            // Change order if you want in-house above grouped:
            $tablesBlock = $htmlSignupReferred . $htmlInhouseDetails;
            $message     = str_replace('__TABLES_SLOT__', $tablesBlock, $messageTop);
    
            // ---- Send email (CI Email library) ----
            $this->load->library('email');
    
            if (!$this->config->item('protocol')) {
                $this->email->initialize([
                    'protocol'   => 'smtp',
                    'smtp_host'  => 'smtp.gmail.com',
                    'smtp_user'  => 'ray@farahilaw.com',   // use env/app password in production
                    'smtp_pass'  => 'knmi zhok clwk wjyq', // use env/app password in production
                    'smtp_port'  => 587,
                    'smtp_crypto'=> 'tls',
                    'mailtype'   => 'html',
                    'charset'    => 'utf-8',
                    'newline'    => "\r\n",
                    'crlf'       => "\r\n",
                ]);
            }
    
            $this->email->from('no-reply@farahilaw.com', 'SINGUP ANNOUNCEMENT');
            $this->email->to($toEmail);
            $this->email->subject($subject);
            $this->email->message($message);
    
            if (!$this->email->send()) {
                $debug = method_exists($this->email, 'print_debugger') ? $this->email->print_debugger(['headers']) : 'send failed';
                http_response_code(500);
                echo json_encode(['ok' => false, 'error' => 'Email send failed', 'debug' => $debug]);
                return;
            }
    
            echo json_encode([
                'ok' => true,
                'sent_to' => $toEmail,
                'range' => $data['range'],
                'kpis' => $data['kpis'],
                'tables_counts' => [
                    'inhouse_signup_summary'   => count($tbl['inhouse_signup_summary']),
                    'category_signup_summary'  => count($tbl['category_signup_summary']),
                    'referred_summary'         => count($tbl['referred_summary']),
                    'signup_referred_details'  => count($tbl['signup_referred_details']),
                    'inhouse_signup_details'   => count($tbl['inhouse_signup_details']),
                ]
            ]);
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
        }
    }
    // content
    function add_content_post()
    {
        // Grab payload (works for form-data or JSON)
        $raw = $this->input->raw_input_stream;
        if (!empty($raw) && empty($_POST)) {
            $json = json_decode($raw, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($json)) {
                foreach ($json as $k => $v) {
                    $_POST[$k] = $v; // normalize so we can use $this->input->post()
                }
            }
        }

        // Fields
        $brand             = $this->input->post('brand');               // e.g., "Justin for Justice"
        $publication_month = $this->input->post('publication_month');   // e.g., "2025-10" or "October 2025"
        $task              = $this->input->post('task');                // e.g., "Blog – Pedestrian Accident"
        $type              = $this->input->post('type');                // e.g., "Blog", "Social", "Video"
        $language          = $this->input->post('language');            // e.g., "English"
        $task_date         = $this->input->post('task_date');           // e.g., "2025-10-07"
        $link_proof        = $this->input->post('link_proof');          // e.g., URL
        // Allow alternate key names common from sheets
        if (!$link_proof) $link_proof = $this->input->post('link') ?: $this->input->post('Link (Proof of Work)');

        // Basic validation
        // if (!$brand || !$task || !$type || !$language || !$task_date) {
        //     http_response_code(422);
        //     echo json_encode([
        //         'status'  => 'error',
        //         'message' => 'Missing required fields: brand, task, type, language, task_date.'
        //     ]);
        //     return;
        // }

        // Normalize date to YYYY-MM-DD if possible
        $ts = strtotime($task_date);
        if ($ts !== false) $task_date = date('Y-m-d', $ts);

        // Build data
        $data = [
            'brand'             => trim($brand),
            'publication_month' => $publication_month ? trim($publication_month) : null,
            'task'              => trim($task),
            'type'              => trim($type),
            'language'          => trim($language),
            'task_date'         => $task_date,
            'link_proof'        => $link_proof ? trim($link_proof) : null,
        ];

        pr($data);die();  
        // UPSERT by unique key (brand, task_date, task, type)
        $existing = $this->content->find_content_existing($data['brand'], $data['task_date'], $data['task'], $data['type']);

        if ($existing) {
            $this->content->update_content_by_id($existing->id, $data);
            echo json_encode([
                'status' => 'ok',
                'action' => 'updated',
                'id'     => (int)$existing->id
            ]);
        } else {
            $new_id = $this->content->save_content($data);
            echo json_encode([
                'status' => 'ok',
                'action' => 'inserted',
                'id'     => (int)$new_id
            ]);
        }
    }

}
