<?php
class EmailPreference_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	function getUserPref($id) {
		$this->db->join('members member', 'member.id = mp.member_id')
				 ->from('member_preference mp');

		$result = $this->db->select('mp.member_id, member.email, mp.channel_id, mp.news_id')
						   ->where('mp.member_id', $id)
                           ->get();
        $data = $result->result_array();
        $result->free_result();

        return $data;
	}

	function getUserPrefEmail() {
		$this->db->join('members member', 'member.id = mp.member_id')
				 ->from('member_preference mp');

		$result = $this->db->select('mp.member_id, member.email, member.first_name, mp.channel_id, mp.news_id')
						   ->where('member_id', '2720')
                           ->get();
        $data = $result->result_array();
        $result->free_result();

        return $data;
	}
	function latestArticle ( $newsCatId ) {
		$this->db->join('newscategory newscat', 'newscat.id = news.category_id')
				 ->from('articles news');

		$result = $this->db->select('news.title, news.date_created, news.blurb, newscat.id newId, news.banner')
						   ->where('category_id', $newsCatId)
						   ->where('isActive', '1')
                           ->get();
        $data = $result->result_array();

        // echo $this->db->last_query();die();
        // print_r($data);die();
        $result->free_result();
        return $data;
	}
	function latestPostCategory ( $catId ) {
		$this->db->join('category cat', 'cat.id = prog.category_id')
				 ->join('related_channel_tag rct', 'rct.channel_id = prog.category_id')
				 ->from('programs prog');

		$result = $this->db->select('prog.title, prog.date_created, prog.blurb, prog.id postId, cat.id catId, rct.tag_id')
						   ->where('category_id', $catId)
						   ->where('isActive', '1')
						   ->where_in('rct.tag_id', ['1','2', '3'])
                           ->get();
        $data = $result->result_array();
        // echo $this->db->last_query();die();
        // print_r($data);die();
        $result->free_result();

        return $data;
	}
	function save( $data ) {

	}

	function dataNews( $member_id ) {
		$this->db->from('member_preference');

        $result = $this->db->select('news_id')
        				   ->where('member_id', $member_id)
                           ->get();

        $data = $result->row();
        // echo $this->db->last_query(); die();
        // print_r($data);die();
        $result->free_result();

        return $data;
	}
}