<?php if($video_mp4 || $video_webm || $video_ogg){ ?>
  <div class="video-overlay no-click fill hide-for-small"></div>
	<video class="video-bg fill hide-for-small" preload playsinline autoplay="true"
	<?php if($video_sound == 'false') echo 'muted'?>
	<?php if($video_loop !== 'false') echo 'loop'; ?>>
	    <?php if($video_mp4) { ?><source src="<?php echo $video_mp4; ?>" type="video/mp4"><?php } ?>
	    <?php if($video_webm) { ?><source src="<?php echo $video_webm; ?>" type="video/webm"><?php } ?>
	   <?php if($video_ogg) { ?><source src="<?php echo $video_ogg; ?>" type="video/ogg"><?php } ?>
	</video>
<?php } ?>
<?php if($youtube) { ?>
  <div class="video-overlay no-click fill"></div>
  <div id="ytplayer-<?php echo mt_rand(1,1000) ?>" class="ux-youtube fill object-fit hide-for-small" data-videoid="<?php echo $youtube ?>" data-loop="<?php echo 'false' !== $video_loop ? '1' : '0' ?>" data-audio="<?php echo 'false' === $video_sound ? '0' : '1' ?>"></div>
<?php } ?>
