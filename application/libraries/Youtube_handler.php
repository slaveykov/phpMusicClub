<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Youtube_handler {
	
	protected $ci;
	protected $apiKey;
	
	public function __construct() {
		
		if (! file_exists($file = APPPATH . 'third_party/vendor/autoload.php')) {
			throw new \Exception('please run "composer require google/apiclient:~2.0" in "' . __DIR__ . '"');
		}
		
		require_once APPPATH . 'third_party/vendor/autoload.php';
		
		$this->ci = & get_instance();
		$this->ci->config->load('google');
		$this->apiKey = $this->ci->config->item('GoogleApiKey');
	
	}
	
	private function ConvertYoutubeDuration($youtube_time) {

		try{
			$start = new DateTime('@0'); // Unix epoch
			$start->add(new DateInterval($youtube_time));
			if (strlen($youtube_time) > 8) {
				return $start->format('g:i:s');
			} else {
				return $start->format('i:s');
			}
		}catch (Exception $e){
			return 30;
		}
	}
	
	private function DurationToSeconds($duration) {
		
		if(substr_count($duration, ':')==1){
			$duration = "00:" . $duration;
		}
			
		$seconds = strtotime("1970-01-01 $duration UTC");

		return $seconds;
	}
	
	public function VideosListById($video_id) {
		
		$client = new Google_Client();
		$client->setDeveloperKey($this->apiKey);
		
		// Define an object that will be used to make all API requests.
		$youtube = new Google_Service_YouTube($client);
		
		try {
			
			$part = "snippet,contentDetails,statistics";
			$params = array(
					'id'=>$video_id 
			);
			
			$params = array_filter($params);
			
			$response = $youtube->videos->listVideos($part, $params);
			
			$back = array();
			
			foreach ($response['items'] as $video) {
				
				$back['duration'] = $this->ConvertYoutubeDuration($video['contentDetails']['duration']);
				$back['seconds'] = $this->DurationToSeconds($back['duration']);
				$back['description'] = $video['snippet']['description'];
				$back['category_id'] = $video['snippet']['categoryId'];
				$back['title'] = $video['snippet']['title'];
				$back['thumbnail'] = $video['snippet']['thumbnails']['standard']['url'];
				
				break;
			}
			
			return $back;
		
		} catch ( Google_Service_Exception $e ) {
			var_dump($e->getMessage());
		} catch ( Google_Exception $e ) {
			var_dump($e->getMessage());
		}
	}

}
