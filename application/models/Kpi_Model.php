<?php

class Kpi_Model extends CI_Model
{
    
	var $table = 'kpi';
	var $id = 'id';

    function show() {
		$this->db->from("kpi a");

		$query = $this->db->select('*')
						->get();


		$result = $query->result_array();

		return $result;
	}
    
    function saveSeo($data) {

		$this->db->insert('seo_gbp_task_tracker', $data);

		return $data;
	}
	
	public function get_campaign_by_name($name)
    {
        return $this->db->get_where('campaign_reports', ['name' => $name])->row_array();
    }

    public function update_campaign_report($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('campaign_reports', $data);
    }
    
    function import($data) {

		$this->db->insert('asanakpi', $data);

		return $data;
	}
	
	function savekpi($data) {

		$this->db->insert('kpi', $data);

		return $data;
	}
	
	function find_existing($brand, $platform, $date, $link) {
        $this->db->from('kpi');
        $this->db->where('brand', $brand);
        $this->db->where('platform', $platform);
        $this->db->where('date', $date);

        // If link is null/empty, compare as NULL; else compare exact
        if ($link === null || $link === '') {
            $this->db->where('link IS NULL', null, false);
        } else {
            $this->db->where('link', $link);
        }

        $q = $this->db->get();
        return $q->row(); // object or null
    }

    function save($data) {
        $this->db->insert('kpi', $data);
        return $this->db->insert_id();
    }

    function update_by_id($id, $data) {
        $this->db->where('id', $id)->update('kpi', $data);
        return $this->db->affected_rows();
    }
	
    function saveseoperf($data) {

		$this->db->insert('flf_seo_performance', $data);

		return $data;
	}
	
	function update($data) {
        $this->db->where('title',$data['title']);
		$query = $this->db->update('kpi', $data);

		return TRUE;
	}

	public function showGraphicsTeam(array $filters = []): array
    {
        // Select ONLY the fields your UI renders
        $this->db->from('asanakpi a');
        $this->db->select([
            'a.id',
            'a.title',
            'a.due_on',          // DATE/DATETIME
            'a.completed_at',    // DATETIME NULL
            'a.performed_by',
            'a.parent_name',
            'a.permalink_url'
        ]);

        // ---- Optional filters (keep results "all" if none provided) ----
        if (!empty($filters['startDate']) || !empty($filters['endDate'])) {
            // Inclusive range over completed_at, falling back to due_on
            $start = !empty($filters['startDate']) ? $filters['startDate'].' 00:00:00' : null;
            $end   = !empty($filters['endDate'])   ? $filters['endDate'].' 23:59:59'   : null;

            if ($start && $end) {
                $this->db->where("COALESCE(a.completed_at, a.due_on) >=", $start);
                $this->db->where("COALESCE(a.completed_at, a.due_on) <=", $end);
            } elseif ($start) {
                $this->db->where("COALESCE(a.completed_at, a.due_on) >=", $start);
            } elseif ($end) {
                $this->db->where("COALESCE(a.completed_at, a.due_on) <=", $end);
            }
        }

        if (!empty($filters['performedBy'])) {
            $this->db->where('a.performed_by', $filters['performedBy']);
        }
        if (!empty($filters['dueMonth'])) {
            $this->db->where('MONTH(a.due_on) =', (int)$filters['dueMonth'], false);
        }
        if (!empty($filters['dueYear'])) {
            $this->db->where('YEAR(a.due_on) =', (int)$filters['dueYear'], false);
        }
        if (!empty($filters['completedMonth'])) {
            $this->db->where('MONTH(a.completed_at) =', (int)$filters['completedMonth'], false);
        }
        if (!empty($filters['completedYear'])) {
            $this->db->where('YEAR(a.completed_at) =', (int)$filters['completedYear'], false);
        }

        // Good default order for your UI
        $this->db->order_by('a.completed_at DESC, a.due_on DESC, a.id DESC');

        // NO LIMIT — returns everything
        $query = $this->db->get();
        return $query->result_array();
    }
	function showgbp() {
		$this->db->from("gpb_local_citation a");

		$query = $this->db->select('*')
						->get();


		$result = $query->result_array();

		return $result;
	}
	function showgbptask() {
		$this->db->from("seo_gbp_task_tracker a");

		$query = $this->db->select('*')
						->get();


		$result = $query->result_array();

		return $result;
	}
	function showseolinkbuilding() {
		$this->db->from("seo_link_building a");

		$query = $this->db->select('*')
						->get();


		$result = $query->result_array();

		return $result;
	}
	function showindexmonitoring() {
	    $this->db->from("index_monitoring a");

		$query = $this->db->select('*')
						->get();


		$result = $query->result_array();

		return $result;
	}
	function insertsummary($data) {
        if (!isset($data['topSites'], $data['napStatus'])) return false;

        $topSites = $data['topSites'];
        $napStatus = $data['napStatus'];
    
        $record = [
            'total_sites'        => $topSites['total'] ?? 0,
            'location'             => $data['location'] ?? '',
            'listed_sites'       => $topSites['listed'] ?? 0,
            'not_listed_sites'   => $topSites['notListed'] ?? 0,
            'listed_percentage'  => $topSites['listedPercentage'] ?? 0,
            'citation_score'     => $topSites['citationScore'] ?? 0,
            'show_citation_score'=> $topSites['showCitationScore'] ?? false,
    
            'nap_correct_total'  => $napStatus['total']['correctNum'] ?? 0,
            'nap_error_total'    => $napStatus['total']['errorsNum'] ?? 0,
    
            'nap_correct_name'   => $napStatus['businessName']['correctNum'] ?? 0,
            'nap_error_name'     => $napStatus['businessName']['errorsNum'] ?? 0,
    
            'nap_correct_phone'  => $napStatus['phoneNumber']['correctNum'] ?? 0,
            'nap_error_phone'    => $napStatus['phoneNumber']['errorsNum'] ?? 0,
    
            'nap_correct_address'=> $napStatus['address']['correctNum'] ?? 0,
            'nap_error_address'  => $napStatus['address']['errorsNum'] ?? 0,
    
            'nap_correct_postcode'=> $napStatus['postcode']['correctNum'] ?? 0,
            'nap_error_postcode' => $napStatus['postcode']['errorsNum'] ?? 0,
        ];
    
        return $this->db->insert('citation_summaries', $record);
    }
    
    function insertcitation($data){
        $record = [
            'source'         => $data['domain'] ?? '',
            'status'         => $data['status'] ?? '',
            'directory'      => $data['directory'] ?? '',
            'phone'          => $data['phone'] ?? '',
            'address'        => $data['address'] ?? '',
            'url'            => $data['url'] ?? '',
            'site_type'      => $data['site_type'] ?? '',
            'location'       => $data['location'] ?? '',
        ];
        return $this->db->insert('citations', $record);
    }
    
    function fetchcitationsummary() {
        $this->db->from("citation_summaries a");

		$query = $this->db->select('*')
						->get();


		$result = $query->result_array();

		return $result;
    }
    
    function fetchac() {
        $this->db->from("campaign_reports a");

		$query = $this->db->select('*')
						->get();


		$result = $query->result_array();

		return $result;
    }
    
    
    // function fetchac() {
    //     $this->db->select('ac.name, ac.opens, ac.bounces, report.email, report.event_type, report.campaign_name, report.campaign_id')
    //              ->from('campaign_reports ac')
    //              ->join('activecampaign_email_reports report', 'ac.campaign_id = report.campaign_id', 'left');
    
    //     $result = $this->db->get();
    //     $data = $result->result_array();
    //     $result->free_result();
    
    //     return $data;
    // }
    
    
    function fetchemailreport($campaign_id) {
        $this->db->from("activecampaign_email_reports a");

		$query = $this->db->select('*')
		                ->where('campaign_id', $campaign_id)
						->get();


		$result = $query->result_array();

		return $result;
    }
    
    function fetchcitation() {
        $this->db->from("citations a");

		$query = $this->db->select('*')
						->get();


		$result = $query->result_array();

		return $result;
    }
    
    function fetchkeywordrank() {
        $this->db->from("keyword_rankings_detailed a");

		$query = $this->db->select('*')
						->get();


		$result = $query->result_array();

		return $result;
    }
    
    function insert_keyword_rankings_detailed($data) {
        return $this->db->insert('keyword_rankings_detailed', $data);
    }
    
    
    
    // Active Campaign
    function save_campaign($data) {
        return $this->db->replace('campaigns', $data);
    }

    function save_campaign_stats($data) {
        return $this->db->replace('campaign_stats', $data);
    }

    function update_bounces($campaign_id, $hard, $soft) {
        return $this->db->where('campaign_id', $campaign_id)
                        ->update('campaign_stats', [
                            'bounces_hard' => $hard,
                            'bounces_soft' => $soft
                        ]);
    }

    function save_unsubscribe($data) {
        return $this->db->replace('unsubscribes', $data);
    }

    function save_click($data) {
        return $this->db->replace('campaign_clicks', $data);
    }
    
    function saveac($data){
        return $this->db->replace('activecampaign_email_reports', $data);
    }
    
    function save_engagement($data) {
        $this->db->insert('ga4_daily_engagement', [
            'report_date' => $data['reportDate'],
            'page_path' => $data['pagePath'],
            'page_title' => $data['pageTitle'],
            'screen_pageviews' => $data['screenPageViews'],
            'engaged_sessions' => $data['engagedSessions'],
            'engagement_rate' => $data['engagementRate'],
            'user_engagement_duration' => $data['userEngagementDuration'],
            'avg_session_duration' => $data['avgSessionDuration'],
            'bounce_rate' => $data['bounceRate'],
            'total_users' => $data['totalUsers'],
            'engaged_sessions_per_user' => $data['engagedSessionsPerUser'],
            'fetched_at' => $data['fetchedAt']
        ]);
    }
    
    function fetchga() {
        $this->db->from("ga4_daily_engagement a");

		$query = $this->db->select('*')
						->get();


		$result = $query->result_array();

		return $result;
    }
    
    function insert_batch_events($data) {
        if (!empty($data)) {
            return $this->db->insert_batch('ga_acquisition_events', $data);
        }
        return false;
    }
    
    function fetchgaacquisition() {
        $this->db->from("ga_acquisition_events a");

		$query = $this->db->select('*')
						->get();


		$result = $query->result_array();

		return $result;
    }
    
    function fetchleaddocket() {
        $this->db->from("leads_tracker a");

		$query = $this->db->select('*');
		$keywords = [
            'GMB', 'Website', 'Intaker', 'AVVO', 'RND', 'Yelp', 'ayuda',
            'web search', 'social', 'JFJ', 'kapwa', 'LLA',
            'labor law', 'brain', 'kj', 'motorcyclist'
        ];
        
       $this->db->group_start();
        foreach ($keywords as $keyword) {
            $this->db->or_where("LOWER(marketing_source) LIKE", "%" . strtolower($keyword) . "%");
        }
        $this->db->group_end();
        
        $query = $this->db->get();


		$result = $query->result_array();

		return $result;
    }
    
    function signcount() {
        // if($timeline = 'eow'){
        //     $startDate = (new DateTime())->modify('friday last week')->format('Y-m-d');
        //     $endDate = (new DateTime())->modify('friday last week')->format('Y-m-d');
        //     $currentYear = (new DateTime())->format('Y');
        // } if($timeline = 'eom') {
        //     $start_date = date('Y-m-01'); // First day of the month
        //     $end_date = date('Y-m-d');    // Today's date
        //     $current_year = date('Y');
        // }
        
        $start_date = date('Y-m-01'); // First day of the month
        $end_date = date('Y-m-d');    // Today's date
        $current_year = date('Y');
    
        $this->db->select('COUNT(DISTINCT full_name) as total_signed');
        $this->db->from('leads_tracker');
    
        // Use only one status — adjust this depending on your goal
        $this->db->where_in('status', ['Referred', 'Signed Up']); // or 'Signed Up'
        
        $this->db->where('case_id_number !=', "");
        // Filter month-to-date range
        $this->db->where('timestamp >=', $start_date);
        $this->db->where('timestamp <=', $end_date);
        
        $this->db->where('case_id_number !=', "");
    
        // Apply LIKE filters on lead_source
        $keywords = [
            'GMB', 'Website', 'Intaker', 'AVVO', 'RND', 'Yelp', 'ayuda',
            'web search', 'social', 'social media', 'JFJ', 'kapwa', 'LLA',
            'labor law', 'brain', 'kj', 'motorcyclist', 'FB ads'
        ];
        
       $this->db->group_start();
        foreach ($keywords as $keyword) {
            $this->db->or_where("LOWER(marketing_source) LIKE", "%" . strtolower($keyword) . "%");
        }
        $this->db->group_end();
    
        $query = $this->db->get();
        $result = $query->row_array();
    
        return $result['total_signed'];
    }

    
    
    function mtdcount() {
        // $start_date = date('Y-m-01'); // First day of the month
        // $end_date = date('Y-m-d');    // Today's date
        // $current_year = date('Y');
    
        // $this->db->select('COUNT(DISTINCT full_name) as total_signed');
        // $this->db->from('leads_tracker');
        // $this->db->where_in('status', ['Signed Up', 'Referred Out', 'Referred', 'Chase']);
    
        // // Filter last_update_date is month-to-date
        // $this->db->where('last_update_date >=', $start_date);
        // $this->db->where('last_update_date <=', $end_date);
    
        // // AND created_date is also month-to-date
        // $this->db->where('created_date >=', $start_date);
        // $this->db->where('created_date <=', $end_date);
        
        // $this->db->where('YEAR(created_date) =', $current_year, false);
        // $this->db->where('YEAR(last_update_date) =', $current_year, false);
    
        // $query = $this->db->get();
        // $result = $query->row_array();
    
        // return $result['total_signed'];
        
        $start_date = date('Y-m-01'); // First day of the month
        $end_date = date('Y-m-d');    // Today's date
        $current_year = date('Y');
    
        $this->db->select('COUNT(DISTINCT full_name) as total_signed');
        $this->db->from('leads_tracker');
    
        // Use only one status — adjust this depending on your goal
        $this->db->where_in('status', ['Referred', 'Signed Up', 'Rejected', 'Chase', 'Under Review', 'Pending Referral', 'Lost']); // or 'Signed Up'
    
        // Filter month-to-date range
        $this->db->where('last_update_date >=', $start_date);
        $this->db->where('last_update_date <=', $end_date);
    
        // Apply LIKE filters on lead_source
        $keywords = [
            'GMB', 'Website', 'Intaker', 'AVVO', 'RND', 'Yelp', 'ayuda',
            'web search', 'social', 'social media', 'JFJ', 'kapwa', 'LLA',
            'labor law', 'brain', 'kj', 'motorcyclist', 'FB ads'
        ];
        
       $this->db->group_start();
        foreach ($keywords as $keyword) {
            $this->db->or_where("LOWER(marketing_source) LIKE", "%" . strtolower($keyword) . "%");
        }
        $this->db->group_end();
    
        $query = $this->db->get();
        $result = $query->row_array();
        
        // echo $this->db->last_query();die();
        return $result['total_signed'];
    }
    
    
    function referredcount() {
        $start_date = date('Y-m-01'); // First day of the month
        $end_date = date('Y-m-d');    // Today's date
        $current_year = date('Y');
        $this->db->select('COUNT(DISTINCT full_name) as total_signed');
        $this->db->from('leads_tracker');
    
        // WHERE signed_cases_referral_status = 'Lead Referred out - Successful'
        $this->db->where('signed_cases_referral_status', "Lead Referred out - Successful");
    
        $this->db->where('case_id_number !=', "");
        // Filter month-to-date range
        $this->db->where('timestamp >=', $start_date);
        $this->db->where('timestamp <=', $end_date);
    
        // Case-insensitive LIKE on marketing_source
        $keywords = [
            'GMB', 'Website', 'Intaker', 'AVVO', 'RND', 'Yelp', 'ayuda',
            'web search', 'social', 'social media', 'JFJ', 'kapwa', 'LLA',
            'labor law', 'brain', 'kj', 'motorcyclist', 'FB ads'
        ];
    
        $this->db->group_start(); // (
        foreach ($keywords as $keyword) {
            $this->db->or_where("LOWER(marketing_source) LIKE", "%" . strtolower($keyword) . "%");
        }
        $this->db->group_end(); // )
    
        $query = $this->db->get();
        $result = $query->row_array();
    
        return $result['total_signed'];
    }

    
    
    function rejectedcount() {
        $start_date = date('Y-m-01'); // First day of the month
        $end_date = date('Y-m-d');    // Today's date
        $current_year = date('Y');
    
        $this->db->select('COUNT(DISTINCT full_name) as total_signed');
        $this->db->from('leads_tracker');
    
        // Use only one status — adjust this depending on your goal
        $this->db->where('status', 'Rejected'); // or 'Signed Up'
    
        // Filter month-to-date range
        $this->db->where('last_update_date >=', $start_date);
        $this->db->where('last_update_date <=', $end_date);
    
        // Apply LIKE filters on lead_source
        $keywords = [
            'GMB', 'Website', 'Intaker', 'AVVO', 'RND', 'Yelp', 'ayuda',
            'web search', 'social', 'social media', 'JFJ', 'kapwa', 'LLA',
            'labor law', 'brain', 'kj', 'motorcyclist', 'FB ads'
        ];
        
       $this->db->group_start();
        foreach ($keywords as $keyword) {
            $this->db->or_where("LOWER(marketing_source) LIKE", "%" . strtolower($keyword) . "%");
        }
        $this->db->group_end();
    
        $query = $this->db->get();
        $result = $query->row_array();
    
        return $result['total_signed'];
    }
    // save data coming from the spreadsheet
    function saveOrUpdateSeo($data) {
        $this->db->where('full_name', $data['full_name']);
        $query = $this->db->get('leads_tracker');
    
        if ($query->num_rows() > 0) {
            // Record exists – update
            $this->db->where('full_name', $data['full_name']);
            $this->db->update('leads_tracker', $data);
            return ['type' => 'update', 'full_name' => $data['full_name']];
        } else {
            // Record doesn't exist – insert
            $this->db->insert('leads_tracker', $data);
            return ['type' => 'insert', 'insert_id' => $this->db->insert_id()];
        }
    }
    
    function inhousesignupsummary(){
        $start_date = date('Y-m-01'); // First day of the month
        $end_date = date('Y-m-d');    // Today's date
    
        $this->db->select('marketing_source, COUNT(DISTINCT full_name) as total_signed');
        $this->db->from('leads_tracker');
    
        // Filter by status and case ID
        $this->db->where_in('status', ['Referred', 'Signed Up']);
        $this->db->where('case_id_number !=', "");
    
        // Filter month-to-date range
        $this->db->where('last_update_date >=', $start_date);
        $this->db->where('last_update_date <=', $end_date);
    
        // Apply LIKE filters on marketing_source
        $keywords = ['GMB', 'Website', 'RND', 'JFJ - FB Page'];
        
        $this->db->group_start();
        foreach ($keywords as $keyword) {
            $this->db->or_where("LOWER(marketing_source) LIKE", "%" . strtolower($keyword) . "%");
        }
        $this->db->group_end();
    
        // Group by marketing_source to get breakdown
        $this->db->group_by('marketing_source');
    
        $query = $this->db->get();
        return $query->result_array(); // Returns array of rows with marketing_source and total_signed
    }
    
    function inhousesignupsummarylist(){
        $start_date = date('Y-m-01'); // First day of the month
        $end_date = date('Y-m-d');    // Today's date
    
        $this->db->select('marketing_source, case_type, value, full_name as client_name');
        $this->db->from('leads_tracker');
    
        // Filter by status and case ID
        $this->db->where('status', 'Signed Up');
        $this->db->where('case_id_number !=', "");
    
        // Filter month-to-date range
        $this->db->where('last_update_date >=', $start_date);
        $this->db->where('last_update_date <=', $end_date);
    
        // Apply LIKE filters on marketing_source
        $keywords = ['GMB', 'Website', 'RND', 'JFJ - FB Page'];
        
        $this->db->group_start();
        foreach ($keywords as $keyword) {
            $this->db->or_where("LOWER(marketing_source) LIKE", "%" . strtolower($keyword) . "%");
        }
        $this->db->group_end();
    
        // Group by marketing_source to get breakdown
        $this->db->group_by('marketing_source');
    
        $query = $this->db->get();
        return $query->result_array(); // Returns array of rows with marketing_source and total_signed
    }
    
    function categorysignupsummary(){
        $start_date = date('Y-m-01'); // First day of the month
        $end_date = date('Y-m-d');    // Today's date
    
        $this->db->select('value, COUNT(DISTINCT full_name) as total_signed');
        $this->db->from('leads_tracker');
    
        // Filter by status and case ID
        $this->db->where_in('status', ['Referred', 'Signed Up']);
        $this->db->where('case_id_number !=', "");
    
        // Filter month-to-date range
        $this->db->where('last_update_date >=', $start_date);
        $this->db->where('last_update_date <=', $end_date);
        
        $keywords = ['GMB', 'Website', 'Intaker', 'AVVO', 'RND', 'Yelp', 'ayuda',
            'web search', 'social', 'social media', 'JFJ', 'kapwa', 'LLA',
            'labor law', 'brain', 'kj', 'motorcyclist', 'FB ads'];
        
        $this->db->group_start();
        foreach ($keywords as $keyword) {
            $this->db->or_where("LOWER(marketing_source) LIKE", "%" . strtolower($keyword) . "%");
        }
        $this->db->group_end();
    
        // Group by 'value' to get a count per category
        $this->db->group_by('value');
    
        $query = $this->db->get();
        return $query->result_array(); // Returns array of rows with value and total_signed
    }

    
    function referredsummarycount(){
        $start_date = date('Y-m-01');
        $end_date = date('Y-m-d');
        // Marketing source keywords
        $keywords = ['GMB', 'Website', 'Intaker', 'AVVO', 'RND', 'Yelp', 'ayuda',
            'web search', 'social', 'social media', 'JFJ', 'kapwa', 'LLA',
            'labor law', 'brain', 'kj', 'motorcyclist', 'FB ads'];
    
        // Build dynamic LIKE clause
        $like_clauses = array_map(function($kw) {
            return "LOWER(marketing_source) LIKE '%" . strtolower($kw) . "%'";
        }, $keywords);
        $like_string = implode(" OR ", $like_clauses);
    
        $sql = "
            SELECT 
                marketing_source,
                IFNULL(signed_cases_referral_status, 'Not Set') AS signed_cases_referral_status,
                COUNT(DISTINCT full_name) AS total_referred,
                SUM(CASE 
                        WHEN signed_cases_referral_status = 'Lead Referred out - Successful' 
                        THEN 1 
                        ELSE 0 
                    END) AS successful_referred_count
            FROM leads_tracker
            WHERE status = 'Referred'
              AND case_id_number != ''
              AND last_update_date BETWEEN ? AND ?
              AND ($like_string)
            GROUP BY marketing_source, signed_cases_referral_status
            ORDER BY marketing_source
        ";
    
        $query = $this->db->query($sql, [$start_date, $end_date]);
        return $query->result_array();
    }

    function seoperformancesummary() {
        $end_date = date('Y-m-d');
        $start_date = date('Y-m-01', strtotime('-2 months'));
    
        // CASE to categorize marketing_source
        $source_case = "(CASE
            WHEN LOWER(marketing_source) LIKE '%website%' THEN 'Website'
            WHEN LOWER(marketing_source) LIKE '%ayuda%' THEN 'Ayuda'
            WHEN LOWER(marketing_source) LIKE '%web search%' THEN 'Web Search'
            WHEN LOWER(marketing_source) LIKE '%jfj%' THEN 'JFJ'
            WHEN LOWER(marketing_source) LIKE '%kapwa%' THEN 'Kapwa'
            WHEN LOWER(marketing_source) LIKE '%lla%' THEN 'LLA'
            WHEN LOWER(marketing_source) LIKE '%labor law%' THEN 'Labor Law'
            WHEN LOWER(marketing_source) LIKE '%brain%' THEN 'Brain'
            WHEN LOWER(marketing_source) LIKE '%kj%' THEN 'KJ'
            WHEN LOWER(marketing_source) LIKE '%motorcyclist%' THEN 'Motorcyclist'
        END) as seo_source";
    
        $this->db->select("
            $source_case,
            COUNT(*) as total_signed,
            SUM(CASE WHEN status = 'Signed Up' THEN 1 ELSE 0 END) as total_signed_up,
            SUM(CASE WHEN status = 'Referred' THEN 1 ELSE 0 END) as total_referred,
            SUM(value) as total_value
        ", false);
    
        $this->db->from('leads_tracker');
        $this->db->where_in('status', ['Referred', 'Signed Up', 'Chase']);
        $this->db->where('last_update_date >=', $start_date);
        $this->db->where('last_update_date <=', $end_date);
    
        // Filter matching keywords only
        $this->db->group_start();
        $keywords = ['website', 'ayuda', 'web search', 'jfj', 'kapwa', 'lla', 'labor law', 'brain', 'kj', 'motorcyclist'];
        foreach ($keywords as $keyword) {
            $this->db->or_where("LOWER(marketing_source) LIKE", "%$keyword%");
        }
        $this->db->group_end();
    
        $this->db->group_by('seo_source');
    
        $query = $this->db->get();
        $results = $query->result_array();
    
        // Build a grouped associative array
        $grouped = [];
        foreach ($results as $row) {
            $grouped[$row['seo_source']] = [
                'total_signed' => (int) $row['total_signed'],
                'total_signed_up' => (int) $row['total_signed_up'],
                'total_referred' => (int) $row['total_referred'],
                'total_value' => (float) $row['total_value'],
            ];
        }
    
        return $grouped;
    }
    
    
    
    // save data coming from the spreadsheet
    function saveLeadEmail($data) {
        // Smart matching: match by email, firstname, and lastname if all are available
        $this->db->from('leads_email_tracker');
        $this->db->where('email', $data['email']);
    
        $query = $this->db->get();
    
        if ($query->num_rows() > 0) {
            // Record exists – update
            $this->db->where('email', $data['email']);
            if (!empty($data['name'])) {
                $this->db->where('name', $data['name']);
            }
            $this->db->update('leads_email_tracker', $data);
            return ['type' => 'update', 'email' => $data['email']];
        } 
        else {
            // Record doesn't exist – insert
            $this->db->insert('leads_email_tracker', $data);
            return ['type' => 'insert', 'insert_id' => $this->db->insert_id()];
        }
    }


    
    
    function leadForm() {
        $this->db->from("leads_email_tracker a");

		$query = $this->db->select('*')
						->get();


		$result = $query->result_array();

		return $result;
    }

    // content
    function save_content(array $data)
    {
        pr($data);die();
        $this->db->insert('content_activity', $data);
        return $this->db->insert_id();
    }

    function update_content_by_id($id, array $data)
    {
        $this->db->where('id', (int)$id)->update('content_activity', $data);
        return $this->db->affected_rows();
    }

    function find_content_existing($brand, $task, $type)
    {
        return $this->db->where('brand', $brand)
                        // ->where('task_date', $task_date)
                        ->where('task', $task)
                        ->where('type', $type)
                        ->get('content_activity')
                        ->row();
    }
}
