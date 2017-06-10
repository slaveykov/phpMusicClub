<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Playlist extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->load->helper(array('date','url'));
		$this->load->database();
	}

	public function index(){
		echo date("Y-m-d H:i:s", strtotime('-10 hours', time()));
	}
	
	private function array_swap(&$array,$swap_a,$swap_b){
		
		if(isset($array[$swap_b]) && isset($array[$swap_a])){
			list($array[$swap_a],$array[$swap_b]) = array($array[$swap_b],$array[$swap_a]);
		}

	}

	public function MoveSong(){

		$song_position 	= 1;
		$song_id 		= 5;
		$cat_id			= 1;

		$MoveSong = $this->MoveSongToPosition($song_id, $cat_id, $song_position);

		print_r($MoveSong); exit;

	}

	public function MoveSongToPosition($song_id, $cat_id, $song_position){

		/*
			План за реализация на скрипта.
			1.Събираме списъка с всички песни от списъка на категорията.
			2.Номерираме подредбата на всички песни от списъка.
			3.Декларираме желаната позиция с цифра на посочената песен.
			4.Правим нова подредба на списъка с песни. От по-малко към по-голямо.
			5.Оправяме времената на песните с новата подредба.
			
			Ако желната песен е с позиция 3, старата песен с позиция 3 я местим на позиция 4.
		*/
		/*
			$song_position 	= 0;
			$song_id 		= 0;
			$cat_id			= 0;
		*/

		$oldList = array();
		$playlist = $this->playlist_model->getPlaylist($cat_id);
		
		if(empty($playlist)) return array("error"=>"Playlist is empty.");

		$songNow = $this->playlist_model->getSongNow($cat_id);

		foreach($this->playlist_model->getPlaylist($cat_id) as $key=>$playlist){
			
			$songDetails = $this->songs_model->getSong($playlist->song_id);
			
			if($songNow['song_id'] == $songDetails['id']){
				$songDetails['song_now'] = true;
			}else{
				$songDetails['song_now'] = false;
			}

			$oldList[] = $songDetails;
		}

		if($song_position > sizeof($oldList)){
			return array("error"=>"Song cant be moved.");
		}

		$song_position = $song_position - 1;

		foreach($oldList as $k=>$f){
			if($f['song_now']==true){
				if($k==$song_position){
					return array("error"=>"The song cant be moved on this position because this position is play now.");
					break;
				}
			}
			echo $k . '-'.$f['name'].'id-'.$f['id'].'<br />';
		}
		
		echo '<hr />';  
		
		$now_position = 0;
		foreach($oldList as $pos=>$song){
			if($song['id']==$song_id){
				$now_position = $pos;
				break;
			}
		}
		
		$newList = $oldList;
		
		if($song_position==$now_position){
			//Ако желаната позицията на песента е същата като сегашната и позиция
			return array("error"=>"The song position is ident then now.");
		}elseif($song_position < $now_position){
			//Ако желаната позицията на песента е по-ниска от сегашната и позиция
			$operation = "minus";

		}elseif($song_position > $now_position){
			//Ако желаната позицията на песента е по-висока от сегашната и позиция
			$operation = "plus";
		}

		$i = 0;
		while(true){

			if($i >= sizeof($newList)) break;

			if($i==0){
				if($operation=="plus"){
					$this->array_swap($newList, $now_position, $now_position + 1);
				}elseif($operation=="minus"){
					$this->array_swap($newList, $now_position, $now_position - 1);
				}
			}else{
				if($operation=="plus"){
					$this->array_swap($newList, $now_position+$i,$now_position + $i + 1);
				}elseif($operation=="minus"){
					$this->array_swap($newList, $now_position-$i,$now_position - $i - 1);
				}
			}
				
			if($newList[$song_position] == $oldList[$now_position]) break;
				
			$i++;
		}

		foreach($newList as $k=>$f){
			echo $k . '-'.$f['name'].'<br />';
		}

		return array("success"=>true,"playlist"=>$newList);
	
	}
	
	public function SongsONQueue(){
		
		if(isset($_POST['cat_id'])){
			
			$cat_id = (int) $_POST['cat_id'];
			
			$playlist = $this->playlist_model->getPlaylist($cat_id);
			
			if(!empty($playlist)){
				foreach($this->playlist_model->getPlaylist($cat_id) as $key=>$playlist){
					$data['playlist'][$key]['song_data'] = $this->songs_model->getSong($playlist->song_id);
					$data['playlist'][$key]['song_data']['playlist_id'] = $playlist->id;
					$data['playlist'][$key]['start'] = mdate("%G:%i:%s %A",$playlist->time_to_start);
					$data['playlist'][$key]['finish'] =  mdate("%G:%i:%s %A",$playlist->time_to_finish);
				}
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