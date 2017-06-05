<?php
	   $i=1;
	   foreach ($playlist as $song){ 
		?>
		<tr <?php if($song_now['youtube_id']==$song['song_data']['youtube_id']):?> style="background:green;" <?php endif;?>>
		<td><?php echo $i++;?></td>
		<td><?php echo $song['song_data']['name'];?></td>
		<td><?php echo gmdate("H:i:s",$song['song_data']['seconds']);?></td>
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