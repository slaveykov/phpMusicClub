<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
	
	//HOMEPAGE1
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('categories','playlist','songs'));
		$this->load->helper(array('date','url'));
		$this->load->database();
	}
	public function index()
	{
		$data = array();
		
		$data['categories'] = $this->categories->getCategories();
		$this->load->view("home", $data);
		
	}
	public function category($cat_id){
		$data = array();
		
		$checkPlaylist = $this->playlist->getPlaylist($cat_id);
		if ($checkPlaylist == null)
		{
			if($this->songs->getSongs($cat_id)==null){
				//no songs in this cat
				redirect('/home');
			}else{
			$this->playlist->createNewPlaylist($cat_id,$this->songs->getSongs($cat_id));
			sleep(2);
			redirect('home/category/'.$cat_id);
			}
		}else{
			
			if($this->playlist->checkPlaylist($cat_id)==null){
				redirect('home/category/'.$cat_id);
			}
			
			$songNowId = $this->playlist->getSongNow($cat_id);
			$data['song_now'] = $this->songs->getSong($songNowId['song_id']);
			$data['song_playlist'] = $songNowId;
			
			$first  = new DateTime(date("h:i:s",time()));
			$second = new DateTime(date("h:i:s",$songNowId['time_to_start']));
			$diff = $first->diff($second);
			$str_time = $diff->format('%H:%I:%S');
			sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);
			$data['time_to_start'] = isset($seconds) ? $hours * 3600 + $minutes * 60 + $seconds : $hours * 60 + $minutes;
			
		}
		foreach($this->playlist->getPlaylist($cat_id) as $key=>$playlist){
			$data['playlist'][$key]['song_data'] = $this->songs->getSong($playlist->song_id);
			$data['playlist'][$key]['start'] = mdate("%G:%i %A",$playlist->time_to_start);
			$data['playlist'][$key]['finish'] =  mdate("%G:%i %A",$playlist->time_to_finish);
		}
		
		$data['cat_info'] = $this->categories->getCategory($cat_id);
		$data['categories'] = $this->categories->getCategories();
		$this->load->view("category",$data);
	}
	
}