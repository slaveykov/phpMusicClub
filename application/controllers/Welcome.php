<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->helper('date');
		$this->load->database();
	}
	public function index()
	{
		
		$query = $this->db->query("SELECT * FROM playlist WHERE cat_id=1");

		if ($query->num_rows() == 0)
		{
			$getAllSongs = $this->db->query('SELECT * FROM songs WHERE cat_id=1');
			$i=0;
			$playlist = array();
			foreach ($getAllSongs->result() as $row)
			{
				$i++;
				$playlist[$i]["seconds"] = $row->seconds;
				$playlist[$i]["song"] = 'song-'.$i;
				$playlist[$i]["song_id"] = $row->id;
				$playlist[$i]["cat_id"] = $row->cat_id;
			}
			for ($i = 1; $i <= count($playlist); $i++) {
				
				if($i==1){
					//inser first song
					$data = array(
					   'cat_id' => $playlist[$i]['cat_id'],
					   'song_id' => $playlist[$i]['song_id'],
					   'time_to_start' => time(),
					   'time_to_finish' => time() + $playlist[$i]['seconds'],
					);
					$this->db->insert('playlist', $data);
					$playlist[$i]["time_to_start"] = time();
					$playlist[$i]["time_to_finish"] = time() + $playlist[$i]['seconds'];
				}else{
					//inser next song
					$data = array(
						   'cat_id' => $playlist[$i]['cat_id'],
						   'song_id' => $playlist[$i]['song_id'],
						   'time_to_start' => $playlist[$i-1]["time_to_finish"],
						   'time_to_finish' => $playlist[$i-1]["time_to_finish"] + $playlist[$i]['seconds'],
						);
					$this->db->insert('playlist', $data); 
					$playlist[$i]["time_to_start"] = $playlist[$i-1]["time_to_finish"];
					$playlist[$i]["time_to_finish"] = $playlist[$i-1]["time_to_finish"] + $playlist[$i]['seconds'];
				}
			}

			echo 'nqma playlist.. zarejdam now  <meta http-equiv="refresh" content="3;" />   ';
		}else{
			$lastSong = $this->db->query("SELECT * FROM playlist WHERE cat_id=1 ORDER BY id DESC LIMIT 1");
			$lastSong = $lastSong->row_array();
			if(time() > $lastSong['time_to_finish']){
				
				$this->db->from('playlist');
				$this->db->truncate(); 
				echo 'pesnite swurshiha.. zarejdam nowi  <meta http-equiv="refresh" content="3;" />';
			}else{
			foreach ($query->result() as $row)
			{
			
				
			$getSongInfo = $this->db->query('SELECT * FROM songs WHERE id='. $row->song_id.'');
			$getSongInfo = $getSongInfo->row_array();
			echo $getSongInfo['id'].'-'.$getSongInfo['name'].' | Sekundi '.$getSongInfo['seconds'].' | 
			Zapochva v: '.mdate("%h:%i %s",$row->time_to_start).' | 
			Svurshva v: '.mdate("%h:%i %s",$row->time_to_finish).'<br />';
			}
			echo '<br />pleilista e zareden <meta http-equiv="refresh" content="3;" />';
			}
		}
				
		/*
		$query = $this->db->query('SELECT * FROM server WHERE cat_id=1');
		
		
		
		$getSongNow = $this->db->query('SELECT * FROM songs WHERE id=1');
		$getSongNow = $getSongNow->row_array();
		echo 'No playing: '.$getSongNow['name'].'<br />';
		
		
		$datestring = "Year: %Y Month: %m Day: %d - %h:%i %a %s ";
		$time = time();

		echo mdate($datestring, $time);/
		*/
		
		
		//$this->load->view('welcome_message');
	}
	
	public function play()
	{
		$getSongNow = $this->db->query("SELECT * FROM playlist WHERE cat_id =1 AND time_to_start < UNIX_TIMESTAMP() AND time_to_finish > UNIX_TIMESTAMP()");
		$getSongNow = $getSongNow->row_array();
		
		$getSongINFO = $this->db->query("SELECT * FROM songs WHERE id=$getSongNow[song_id]");
		$getSongINFO = $getSongINFO->row_array();
		
		echo 'No playing: '.$getSongINFO['name'].'<br />';
		//echo date("s",$getSongNow['time_to_start']+time());
	echo '
	
	<iframe src="http://www.youtube.com/embed/'.$getSongINFO['youtube_id'].'?start='.date("s",$getSongNow['time_to_start']+time()).'&autoplay=1" width="0" height="0" frameborder="0" allowfullscreen></iframe>

	';
			
	}
}
