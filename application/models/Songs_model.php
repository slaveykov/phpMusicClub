<?php
class Songs_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
	function getSongs($cat_id){
		
		$array = array('cat_id' => $cat_id);
		$this->db->where($array); 
		$this->db->order_by("id", "random"); 
		$query = $this->db->get('songs');

		return $query->result();
	}
	function getSong($id){
		$query = $this->db->get_where('songs', array('id' => $id));
		return $query->row_array();
	}

}