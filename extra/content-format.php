<?php if (have_posts()) : $current_date = date('Y-m-d', (time() * (60*60*24))); ?>
<?php while (have_posts()) : the_post(); ?>

	<?php if($current_date != the_date('Y-m-d', '', '', false)) : 
		$current_date = the_date('Y-m-d', '', '', false); ?>
		<div class="date-divider">
			<span><?php the_time('F j Y'); ?></span>
		</div>
	<?php endif; ?>
	
	<?php if(!!get_option('ci_twitter_cat') and in_category(get_option('ci_twitter_cat'))) : ?>
		<div class="box-post" id="post-<?php the_ID(); ?>">
			<p class="tweet">
				<?php echo $post->post_content; ?> 
				<span class="tiny-meta"><?php the_time('h:i a') ?> - <a href="<?php the_permalink() ?>#comment"><?php comments_number(); ?></a></span>
			</p>
		</div>

	<?php elseif(!!get_option('ci_delicious_cat') and in_category(get_option('ci_delicious_cat'))) : ?>
		<div class="box-post" id="post-<?php the_ID(); ?>">
			<div class="bookmark">
				<?php the_author() ?> saved <?php  echo strip_tags($post->post_content, '<a><em><strong><b><div>'); ?> on Delicious.
			</div>
			<span class="tiny-meta"><?php the_time('h:i a') ?> - <a href="<?php the_permalink() ?>#comment"><?php comments_number(); ?></a></span>
		</div>
	
	<?php elseif(!!get_option('ci_aside_cat') and in_category(get_option('ci_aside_cat'))) : ?>
		<div class="box-post" id="post-<?php the_ID(); ?>">
			<h2 class="aside">
				<a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a> 
				<span class="tiny-meta"><?php the_time('h:i a') ?></span>
			</h2>
			<div class="stream"><?php the_content(); ?></div>
			<div class="meta">
				<a href="<?php the_permalink() ?>#comment"><?php comments_number(); ?></a>
			</div>
		</div>
	
	<?php elseif(!!get_option('ci_video_cat') and in_category(get_option('ci_video_cat'))) : ?>
		<div class="box-post" id="post-<?php the_ID(); ?>">
			<h2 class="video">
				<a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a> 
				<span class="tiny-meta"><?php the_time('h:i a') ?></span>
			</h2>
			<div class="stream"><?php the_content(); ?></div>
			<div class="meta">
				<a href="<?php the_permalink() ?>#comment"><?php comments_number(); ?></a>
			</div>
		</div>
	
	<?php elseif(!!get_option('ci_event_cat') and in_category(get_option('ci_event_cat'))) : ?>
		<div class="box-post" id="post-<?php the_ID(); ?>">
			<h2 class="event">
				<a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a> 
				<span class="tiny-meta"><?php the_time('h:i a') ?></span>
			</h2>
			<div class="stream"><?php the_content(); ?></div>
			<div class="meta">
				<a href="<?php the_permalink() ?>#comment"><?php comments_number(); ?></a>
			</div>
		</div>
	
	<?php else : ?>
		<div class="box-post" id="post-<?php the_ID(); ?>">
			<h2 class="note">
				<a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a> 
				<span class="tiny-meta"><?php the_time('h:i a') ?></span>
			</h2>
			<div class="stream"><?php the_content(); ?></div>
			<div class="meta">
				<a href="<?php the_permalink() ?>#comment"><?php comments_number(); ?></a>
			</div>
		</div>
	<?php endif; ?>

<?php endwhile; endif; ?>