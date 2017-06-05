<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
	
	//HOMEPAGE12
	
	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('date','url'));
		$this->load->database();
	
	}
	public function index()
	{
		$data = array();
		
		$data['categories'] = $this->categories_model->getCategories();
		$this->load->view("home", $data);
		
	}
	
	public function addSongs(){
		
		$this->load->library("Youtube_handler");
		
		if(isset($_POST['urls'])){
			
			$cat_id = (int) $_POST['cat'];
			
			$allUrls = $_POST['urls'];
			$urls = explode(PHP_EOL,$allUrls);
			
			foreach($urls as $url){
				
				preg_match("/(?:https?:\/\/)?(?:(?:(?:www\.?)?youtube\.com(?:\/(?:(?:watch\?.*?(v=[^&\s]+).*)|(?:v(\/.*))|(channel\/.+)|(?:user\/(.+))|(?:results\?(search_query=.+))))?)|(?:youtu\.be(\/.*)?))/",$url,$catch);
				$youtube_id = str_replace("v=","",$catch[1]);
				
				$video_info = $this->youtube_handler->VideosListById($youtube_id);
				
				
				$data = array(
					'cat_id'=>$cat_id,
					'youtube_id' => $youtube_id,
					'name' => $video_info['title'],
					'duration' => $video_info['duration'],
					'seconds' => $video_info['seconds'],
				);
				
				$this->db->insert('songs', $data); 
				
			}
		}
		
		echo '
		<form method="post">
		<select name="cat">
		';
		foreach($this->db->from("categories")->get()->result_array() as $cat){
			echo '<option value="'.$cat['id'].'">'.$cat['name'].'</option>'; 
		}
		echo '
		</select>
		Links from youtube: <br />
		<textarea name="urls"></textarea>
		<br />
		<input type="submit" value="Add all songs" />
		</form>
		';
	}
	
	public function category($cat_id){
		
		$data = array();
		
		/*
		$checkPlaylist = $this->playlist_model->getPlaylist($cat_id);
		if ($checkPlaylist == null)
		{
			if($this->songs_model->getSongs($cat_id)==null){
				//no songs in this cat
				$this->session->set_flashdata('message', array("type"=>"danger","No added songs in this cat."));
				redirect(base_url('home'));
			}else{
				$this->playlist_model->createNewPlaylist($cat_id,$this->songs_model->getSongs($cat_id));
				sleep(2);
				redirect(base_url('home/category/'.$cat_id));
			}
		}else{
			
			if($this->playlist_model->checkPlaylist($cat_id) == null){
				redirect(base_url('home/category/'.$cat_id));
			}
			
			$songNowId = $this->playlist_model->getSongNow($cat_id);
			$data['song_now'] = $this->songs_model->getSong($songNowId['song_id']);
			$data['song_playlist'] = $songNowId;
			
			$first  = new DateTime(date("h:i:s",time()));
			$second = new DateTime(date("h:i:s",$songNowId['time_to_start']));
			$diff = $first->diff($second);
			$str_time = $diff->format('%H:%I:%S');
			sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);
			$data['time_to_start'] = isset($seconds) ? $hours * 3600 + $minutes * 60 + $seconds : $hours * 60 + $minutes;
			
		}
		
		foreach($this->playlist_model->getPlaylist($cat_id) as $key=>$playlist){
			$data['playlist'][$key]['song_data'] = $this->songs_model->getSong($playlist->song_id);
			$data['playlist'][$key]['song_data']['playlist_id'] = $playlist->id;
			$data['playlist'][$key]['start'] = mdate("%G:%i %A",$playlist->time_to_start);
			$data['playlist'][$key]['finish'] =  mdate("%G:%i %A",$playlist->time_to_finish);
		}
		*/
		
		$data['cat_info'] = $this->categories_model->getCategory($cat_id);
		$data['categories'] = $this->categories_model->getCategories();
		
		$this->load->view("category",$data);
	}
	
}