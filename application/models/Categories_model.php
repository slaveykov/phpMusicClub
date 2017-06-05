<?php

class Categories_model extends CI_Model {

    var $id   = '';
    var $name = '';
    var $image    = '';

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
	function getCategories(){
		
		$query = $this->db->get('categories');
		return $query->result();
	}
	function getCategory($id){
		
		$query = $this->db->get_where('categories', array('id' => $id));
		return $query->row_array();
		
	}

}