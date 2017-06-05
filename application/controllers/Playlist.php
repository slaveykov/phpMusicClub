<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Playlist extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->load->helper(array('date','url'));
		$this->load->database();
	
	}
	public function index(){
		
	}
	
	public function MoveSong(){ //$song_id, $cat_id
		
		/*
		 План за реализация на скрипта.
		1.Събираме списъка с всички песни от списъка на категорията.
		2.Номерираме подредбата на всички песни от списъка.
		3.Декларираме желаната позиция с цифра на посочената песен.
		4.Правим нова подредба на списъка с песни. От по-малко към по-голямо.
		5.Оправяме времената на песните с новата подредба.
			
		Ако желната песен е с позиция 3, старата песен с позиция 3 я местим на позиция 4.
		*/
		
		$song_position 	= 3;
		$song_id 		= 13;
		$cat_id			= 10;
		
		$songs = array();
		$i_songs = 1;
		foreach($this->playlist_model->getPlaylist($cat_id) as $key=>$playlist){
			$songDetails = $this->songs_model->getSong($playlist->song_id);
			echo $songDetails['name']."<br />";
			$songDetails['position'] = $i_songs;
			$songs[] = $songDetails;
			$i_songs++;
		}
		
		$new_songs = array();
		$i_songs = 1;
		foreach($songs as $song){
			if($song['position'] == $song_position){
				$new_songs[$song_position + 1] = $song;
			}
			if($song['position'] < $song_position){
				$new_songs[$i_songs] = $song;
			}
			if($song['id'] == $song_id){
				$new_songs[$song_position] = $song;
				//unset($new_songs[$song['position']]);
			}
			$i_songs++;
		}
		echo $i_songs;
		ksort($new_songs);
		var_dump($new_songs);
	}
	
	public function SongsONQueue(){
		
		if(isset($_POST['cat_id'])){
			
			$cat_id = (int) $_POST['cat_id'];
			
			foreach($this->playlist_model->getPlaylist($cat_id) as $key=>$playlist){
				$data['playlist'][$key]['song_data'] = $this->songs_model->getSong($playlist->song_id);
				$data['playlist'][$key]['song_data']['playlist_id'] = $playlist->id;
				$data['playlist'][$key]['start'] = mdate("%G:%i:%s %A",$playlist->time_to_start);
				$data['playlist'][$key]['finish'] =  mdate("%G:%i:%s %A",$playlist->time_to_finish);
			}
			
			$songNowId = $this->playlist_model->getSongNow($cat_id);
			
			$data['song_now'] = $this->songs_model->getSong($songNowId['song_id']);
			$data['cat_info'] = $this->categories_model->getCategory($cat_id);
			
			$this->load->view("songs_on_queue_ajax", $data);
		}
		
	}
	
	public function SongNow($cat_id = false){
	
		if(isset($_POST['cat_id']) || $cat_id == true){
			
			if($cat_id == false){
				$cat_id = (int) $_POST['cat_id'];
			}
			
			$json = array();
			
			if($this->CreateNewPlaylist($cat_id)==true){
				
				sleep(rand(1,4));
				
				$songNowId = $this->playlist_model->getSongNow($cat_id);
				
				if($songNowId !== NULL){
					
					$songDetails = $this->songs_model->getSong($songNowId['song_id']);
					
					$data['youtube_id'] = $songDetails['youtube_id'];
					
					$first  = new DateTime(date("h:i:s",time()));
					$second = new DateTime(date("h:i:s",$songNowId['time_to_start']));
					$diff = $first->diff($second);
					$str_time = $diff->format('%H:%I:%S');
					sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);
					
					$data['time_to_start'] = isset($seconds) ? $hours * 3600 + $minutes * 60 + $seconds : $hours * 60 + $minutes;
					
					$json['song_time_left'] = $songDetails['seconds'] - $data['time_to_start'];
					
					if($json['song_time_left'] < 0){
						return $this->songNow($cat_id);
					}
					
					$json['html'] = $this->load->view("song_now_ajax", $data, TRUE);
				}
				
			}
			
			echo json_encode($json);
		}
	}
	
	private function CreateNewPlaylist($cat_id){
		
		$checkPlaylist = $this->playlist_model->getPlaylist($cat_id);
		
		if ($checkPlaylist == NULL){
			
			if($this->songs_model->getSongs($cat_id) == NULL){
				return false;
			} else {
				$this->playlist_model->createNewPlaylist($cat_id,$this->songs_model->getSongs($cat_id));
				return true;
			}
		}else{
			
			if($this->playlist_model->checkPlaylist($cat_id) == NULL){
				return $this->CreateNewPlaylist($cat_id);
			}
			
			return true;
		}
	}
}