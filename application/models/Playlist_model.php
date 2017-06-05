<?php

class Playlist_model extends CI_Model
{
    
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    
    function getPlaylist($cat_id)
    {
        $getPlaylist = $this->db->from('playlist')->where('cat_id',$cat_id)->order_by("time_to_start","ASC")->get();
        
        if ($getPlaylist->num_rows() == 0) {
            return NULL;
        } else {
            return $getPlaylist->result();
        }
    }
	
    function checkPlaylist($cat_id){
		
		$where = array('cat_id' => $cat_id);

		$this->db->where($where); 
		$this->db->order_by("id", "desc"); 
		$this->db->limit(1);
		$query = $this->db->get('playlist');
		$query = $query->row_array();
		
		if(time() > $query['time_to_finish']){
			$this->db->where('cat_id', $cat_id);
			$this->db->delete('playlist'); 
			return null;
		}else{
			return 1;
		}
	}
	
    function createNewPlaylist($cat_id, $SongsData)
    {
        $i        = 0;
        $playlist = array();
        foreach ($SongsData as $row) {
            $i++;
            $playlist[$i]["seconds"] = $row->seconds;
            $playlist[$i]["song"]    = 'song-' . $i;
            $playlist[$i]["song_id"] = $row->id;
            $playlist[$i]["cat_id"]  = $row->cat_id;
        }
        
        for ($i = 1; $i <= count($playlist); $i++) {
            
            if ($i == 1) {
                //inser first song
                $data = array(
                    'cat_id' => $playlist[$i]['cat_id'],
                    'song_id' => $playlist[$i]['song_id'],
                    'time_to_start' => time(),
                    'time_to_finish' => time() + $playlist[$i]['seconds']
                );
                $this->db->insert('playlist', $data);
                $playlist[$i]["time_to_start"]  = time();
                $playlist[$i]["time_to_finish"] = time() + $playlist[$i]['seconds'];
            } else {
                //inser next song
                $data = array(
                    'cat_id' => $playlist[$i]['cat_id'],
                    'song_id' => $playlist[$i]['song_id'],
                    'time_to_start' => $playlist[$i - 1]["time_to_finish"],
                    'time_to_finish' => $playlist[$i - 1]["time_to_finish"] + $playlist[$i]['seconds']
                );
                $this->db->insert('playlist', $data);
                $playlist[$i]["time_to_start"]  = $playlist[$i - 1]["time_to_finish"];
                $playlist[$i]["time_to_finish"] = $playlist[$i - 1]["time_to_finish"] + $playlist[$i]['seconds'];
            }
			
        }
    }
    
    function getSongNow($cat_id){
    	
        $query = $this->db->get_where('playlist', array(
            'cat_id' => $cat_id,
            'time_to_start <' => time(),
            'time_to_finish >' => time()
        ));
        
        return $query->row_array();
    }
    
}