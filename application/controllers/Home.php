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
		
		$data['categories'] = $this->categories->getCategories();
		$this->load->view("home", $data);
		
	}
	
	public function addSongs(){
		
		if(isset($_POST['urls'])){
			
			$cat_id = (int) $_POST['cat'];
			
			$allUrls = $_POST['urls'];
			$urls = explode(PHP_EOL,$allUrls);
			
			foreach($urls as $url){
				
				preg_match("/(?:https?:\/\/)?(?:(?:(?:www\.?)?youtube\.com(?:\/(?:(?:watch\?.*?(v=[^&\s]+).*)|(?:v(\/.*))|(channel\/.+)|(?:user\/(.+))|(?:results\?(search_query=.+))))?)|(?:youtu\.be(\/.*)?))/",$url,$catch);
				$youtube_id = str_replace("v=","",$catch[1]);
				
				
				$youtube_title = 'Song id:'.$youtube_id;
				
				/*
				$content = file_get_contents($url);
				
				$doc = new DOMDocument('1.0', 'UTF-8');
				$doc->encoding = 'UTF-8';
				$doc->loadHTML($content);
				
				$content = mb_convert_encoding($content, 'HTML-ENTITIES', "UTF-8");
				
				$xpath = new DOMXpath($doc);
				$elements = $xpath->query(".//*[@id='eow-title']");
				
				if (!is_null($elements)) {
					foreach ($elements as $element) {
						$youtube_title = 'Song id:'.$youtube_id;//$element->nodeValue;
					}
				}
				*/
				
				$data = array(
					'cat_id'=>$cat_id,
					'youtube_id' => $youtube_id,
					'name' => $youtube_title,
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
		
		$checkPlaylist = $this->playlist->getPlaylist($cat_id);
		if ($checkPlaylist == null)
		{
			if($this->songs->getSongs($cat_id)==null){
				//no songs in this cat
				$this->session->set_flashdata('message', array("type"=>"danger","No added songs in this cat."));
				redirect(base_url('home'));
			}else{
				$this->playlist->createNewPlaylist($cat_id,$this->songs->getSongs($cat_id));
				sleep(2);
				redirect(base_url('home/category/'.$cat_id));
			}
		}else{
			
			if($this->playlist->checkPlaylist($cat_id) == null){
				redirect(base_url('home/category/'.$cat_id));
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