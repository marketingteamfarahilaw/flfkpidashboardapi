<?php
class GmbMetric_Model extends CI_Model
{

    protected $table            = 'gmb_metrics';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'date',
        'location',
        'discovery',
        'interactions',
        'calls',
        'messages',
        'directions',
        'website_clicks',
        'rating',
        'total_reviews',
        'movement_vs_prev_month',
        'leads',
        'signups',
        'rate',
    ];

    protected $useTimestamps = true; // created_at, updated_at
    protected $returnType    = 'array';
    
    function gmbloc(){
        $this->db->from("gmb_metrics a");

		$query = $this->db->select('*')
						->get();


		$result = $query->result_array();

		return $result;
    }
}