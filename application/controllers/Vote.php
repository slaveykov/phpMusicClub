<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vote extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->load->helper(array('date','url'));
		$this->load->database();
	
	}
	public function index(){
		
	}
	
	public function Post(){
		
		$json = array();
		$json['reload'] = false;
		
		$type = $_POST['type'];
		
		if($type == "song"){
			
			$cat_id = (int) $_POST['cat_id'];
			$song_id = (int) $_POST['song_id'];
			$playlist_id = (int) $_POST['playlist_id'];
			$ip_address = $this->input->ip_address();
			$json_data = json_encode(array("cat_id"=>$cat_id,"song_id"=>$song_id,"playlist_id"=>$playlist_id));
			
			$check = $this->db->query("SELECT * FROM votes WHERE ip='{$ip_address}' AND data='{$json_data}'")->result_array();
			
			if(empty($check)){
				$data['ip'] = $ip_address;
				$data['time'] = date("Y-m-d H:i:s");
				$data['type'] = "song";
				$data['data'] = $json_data;
				$this->db->insert('votes', $data);
				$json['status'] = "success";
			} else{
				$json['status'] = "fail";
			}
			
			//let check for votes about this song, if we have a more votes then two we will to up to in playlist
			$check_all_votes = $this->db->query("SELECT * FROM votes WHERE data='{$json_data}'")->result_array();
			if(sizeof($check_all_votes)>1){
				
				$json['reload'] = true;
				$json['playlist'] = "test";
				
			}
		}
		
		
		
		echo json_encode($json);
	}
}