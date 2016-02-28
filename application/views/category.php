<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <title><?php echo base_url();?></title>
	<link href='http://fonts.googleapis.com/css?family=Orbitron:400,700|Devonshire|Open+Sans|Open+Sans+Condensed:700' rel='stylesheet' type='text/css'>
    <link href="<?php echo base_url();?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url();?>css/style.css" rel="stylesheet">
  </head>
  <body>
    <div class="container">
	  <div class="header">
      <div class="row">
	  <div class="col-md-6">
	  <img src="<?php echo base_url();?>/img/logo.png" class="logo" />
	  </div> 
	   <div class="col-md-6">
		<nav class="pull-right">
		<ul id="menu">
		<?php
		foreach ($categories as $cat){
				?>
           <li> <a href="<?php echo base_url();?>home/category/<?php echo $cat->id;?>"><?php echo $cat->name;?></a></li>
		<?php
		}
		?>
		</ul>
		</nav>
	  </div>
	    <div class="col-md-12 stats">
	    Club: <?php echo $cat_info['name'];?><br />
		ON AIR: <?php echo $song_now['name'];?><br />
	   </div>
	  </div>
	  </div>
	  <div class="opacity_cont">
	  <div class="row">
	  <div class="col-md-12">
	   <iframe src="http://www.youtube.com/embed/<?php echo $song_now['youtube_id'];?>?start=<?php echo $time_to_start;?>&autoplay=1" style="width:100%;height:300px;" frameborder="0" allowfullscreen></iframe>
	   <h2>DJ Playlist</h2>
	   <table class="table">
	   <tr>
	   <td>#</td>
	   <td>Песен</td>
	   <td>Продължителност</td>
	   <td>Започва в</td>
	   <td>Свършва в</td>
	   </tr>
	   <?php
	   $i=1;
	   foreach ($playlist as $song){
		?>
		<tr>
		<td><?php echo $i++;?></td>
		<td><?php echo $song['song_data']['name'];?></td>
		<td><?php echo gmdate("H:i:s",$song['song_data']['seconds']);?></td>
		<td><?php echo $song['start'];?></td>
		<td><?php echo $song['finish'];?></td>
		</tr>
		<?php
	   }
	   ?>
	   </table>
         </div>
         </div>
	  </div>
	  <footer>
		All rights reserved. | phpMuiscClub v1.0
	  </footer>
    </div>
  </body>
</html>
