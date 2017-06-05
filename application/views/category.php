<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <base href="<?php echo base_url();?>">
    <title><?php echo base_url();?></title>
	<link href='http://fonts.googleapis.com/css?family=Orbitron:400,700|Devonshire|Open+Sans|Open+Sans+Condensed:700' rel='stylesheet' type='text/css'>
    <link href="<?php echo base_url();?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url();?>css/style.css" rel="stylesheet">
  </head>
  <body>
    <div class="container">
	  <div class="header">
      <div class="row">
	  <div class="col-md-4">
	  <img src="<?php echo base_url();?>/img/logo.png" class="logo" />
	  </div> 
	   <div class="col-md-8">
		<nav class="pull-right">
		<ul id="menu">
		<?php
		foreach ($categories as $cat){
		?>
        <li><a href="<?php echo base_url();?>home/category/<?php echo $cat->id;?>"><?php echo $cat->name;?></a></li>
		<?php
		}
		?>
		</ul>
		</nav>
	  </div>
	    <div class="col-md-12 stats">
	    Club: <?php echo $cat_info['name'];?><br />
		ON AIR: <?php //echo $song_now['name'];?><br />
	   </div>
	  </div>
	  </div>
	  <div class="opacity_cont">
	  <div class="row">
	  <div class="col-md-12">
	   <div id="songPlayNow" style="display:none">
	   <!--
	   <iframe src="http://www.youtube.com/embed/<?php echo $song_now['youtube_id'];?>?start=<?php echo $time_to_start;?>&autoplay=1" style="width:100%;height:500px;" frameborder="0" allowfullscreen></iframe>
	  -->
	  </div>
	   <h2>DJ Playlist</h2>
	   <table class="table">
	   <thead>
	   <tr>
	   <td>#</td>
	   <td>Песен</td>
	   <td>Продължителност</td>
	   <td>Започва в</td>
	   <td>Свършва в</td>
	   <td>Гласувай</td>
	   </tr>
	   </thead>
	   <tbody id="playList" style="display:none">
	   <!--
	   <?php
	   $i=1;
	   foreach ($playlist as $song){ 
		?>
		<tr <?php if($song_now['youtube_id']==$song['song_data']['youtube_id']):?> style="background:green;" <?php endif;?>>
		<td><?php echo $i++;?></td>
		<td><?php echo $song['song_data']['name'];?></td>
		<td><?php echo gmdate("H:i:s",$song['song_data']['duration']);?></td>
		<td><?php echo $song['start'];?></td>
		<td><?php echo $song['finish'];?></td>
		<td id="vote_td_<?php echo $song['song_data']['id'];?>_<?php echo $cat_info['id'];?>">
		<a class="btn btn-default btn-sm voteForSong" song_id="<?php echo $song['song_data']['id'];?>" cat_id="<?php echo $cat_info['id'];?>" playlist_id="<?php echo $song['song_data']['playlist_id'];?>">
		Гласувай
		</a>
		</td>
		</tr> 
		<?php
	   }
	   ?>
	   -->
	    </tbody>
	   </table>
         </div>
         </div>
	  </div>
	  <footer>
		All rights reserved. | phpMuiscClub v1.0
	  </footer>
	<script src="https://code.jquery.com/jquery-1.6.2.js"></script>
	<script>
	function Countdown(options) {
	  var timer,
	  instance = this,
	  seconds = options.seconds || 10,
	  updateStatus = options.onUpdateStatus || function () {},
	  counterEnd = options.onCounterEnd || function () {};

	  function decrementCounter() {
		updateStatus(seconds);
		if (seconds === 0) {
		  counterEnd();
		  instance.stop();
		}
		seconds--;
	  }

	  this.start = function () {
		clearInterval(timer);
		timer = 0;
		seconds = options.seconds;
		timer = setInterval(decrementCounter, 1000);
	  };

	  this.stop = function () {
		clearInterval(timer);
	  };
	}
	</script>
	<script>
	
	var vote_saved = "Your vote are saved.";
	var vote_fail = "Allready voted for this song.";
	
	var cat_id  = <?php echo $cat_info['id'];?>;
	//var song_time_left = <?php // echo $song_now['duration'] - $time_to_start; ?>;
	
	var GeckoSystemsJQ = $.noConflict();
	GeckoSystemsJQ(document).ready(function($) {
		
		function SongCounter(song_time_left){
			var SongNowCount = new Countdown({
				seconds: song_time_left,  // number of seconds to count down
				onUpdateStatus: function(sec){
					console.log(sec);
				},
				onCounterEnd: function(){
					LoadSongNow(cat_id);
					LoadPlaylist(cat_id);
				}
			});
			SongNowCount.start();
		}
		
		function LoadSongNow(cat_id){
			$.post("Playlist/SongNow", {cat_id: cat_id}).done(function(data) {
				var response = jQuery.parseJSON(data);
				$("#songPlayNow").html(response.html).fadeIn("slow");
				SongCounter(response.song_time_left);
			});
		}
		
		function LoadPlaylist(cat_id){
			$.post("Playlist/SongsONQueue", {cat_id: cat_id}).done(function(html) {
				$("#playList").html(html).fadeIn("slow");
			});
		}
		
		LoadSongNow(cat_id);
		LoadPlaylist(cat_id);
			
		$(".voteForSong").click(function(){
			
			var song_id = $(this).attr("song_id");
			var cat_id  = $(this).attr("cat_id");
			var playlist_id  = $(this).attr("playlist_id");
			
			$.post("Vote/Post", { song_id: song_id, cat_id: cat_id, playlist_id: playlist_id, type: "song"})
			.done(function(data) {
				var response = jQuery.parseJSON(data);
				var vote_td = "#vote_td_"+song_id+"_"+cat_id;
				if(response.status == "success"){
					$(vote_td).html("<span style='font-size:12px;background:green;color:#fff;padding:5px;'>" + vote_saved + "</span>");
				}else{
					$(vote_td).html("<span style='font-size:12px;background:red;color:#fff;padding:5px;'>" + vote_fail + "</span>");
				}
				if(response.reload == true){
					$("#playList").html(response.playlist);
				}
			});
			
		});
	});
	</script>
  </body>
</html>
