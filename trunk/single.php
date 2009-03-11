<?php get_header(); ?>
<!--#content:start-->
    	<div id="content" class="page">
        <?php 
		if (have_posts()) : 
			$current_date = date('Y-m-d', (time() * (60*60*24)));
			while (have_posts()) : the_post(); 
			
			// Date divider
			if($current_date != the_date('Y-m-d', '', '', false)) : 
				$current_date = the_date('Y-m-d', '', '', false); 
		?>
			<div class="date-divider">
				<span><?php the_time('F j Y'); ?></span>
			</div>
		<?php endif;?>
		
        	<div class="entry" id="post-<?php the_ID(); ?>">
            
			<?php if(!!get_option('ci_delicious_cat') and in_category(get_option('ci_delicious_cat'))) : ?>
				<div class="bookmark">
					<?php the_author() ?> saved <?php  echo strip_tags($post->post_content, '<a><em><strong><b><div>'); ?> on Delicious.
				</div>
				<span class="tiny-meta">
					<?php the_time('h:i a') ?> - 
					<a href="<?php the_permalink() ?>#comment"><?php comments_number(); ?></a>
				</span>
        	
			<?php elseif(!!get_option('ci_twitter_cat') and in_category(get_option('ci_twitter_cat'))) : ?>
				<p class="tweet">
					<?php echo $post->post_content; ?> 
					<span class="tiny-meta">
						<?php the_time('h:i a') ?> - 
						<a href="<?php the_permalink() ?>#comment"><?php comments_number(); ?></a>
					</span>
				</p>
				
			<?php elseif(!!get_option('ci_aside_cat') and in_category(get_option('ci_aside_cat'))) : ?>
				<h2 class="aside">
					<a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a> 
					<span class="tiny-meta"><?php the_time('h:i a') ?></span>
				</h2>
            	<?php the_content(__('')); ?>
				
                <div class="meta">
                	<a href="<?php the_permalink() ?>#comment"><?php comments_number(); ?></a> <?php meta_entries($post->ID); ?>
				</div>
			
			<?php elseif(!!get_option('ci_video_cat') and in_category(get_option('ci_video_cat'))) : ?>
				<h2 class="video">
					<a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a> 
					<span class="tiny-meta"><?php the_time('h:i a') ?></span>
				</h2>
            	<?php the_content(__('')); ?>
				
                <div class="meta">
                	<a href="<?php the_permalink() ?>#comment"><?php comments_number(); ?></a> <?php meta_entries($post->ID); ?>
				</div>
			
			<?php elseif(!!get_option('ci_event_cat') and in_category(get_option('ci_event_cat'))) : ?>
				<h2 class="event">
					<a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a> 
					<span class="tiny-meta"><?php the_time('h:i a') ?></span>
				</h2>
            	<?php the_content(__('')); ?>
				
                <div class="meta">
                	<a href="<?php the_permalink() ?>#comment"><?php comments_number(); ?></a> <?php meta_entries($post->ID); ?>
				</div>
        	
			<?php else : ?>
       			<div class="header">
            		<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
                	<div>
						<?php _e("Posted on the"); ?> 
						<span class="date">
							<?php the_time('h:i a') ?>
						</span> under 
						<span class="tags">
							<?php the_category(',') ?>
						</span> by <span class="author"><?php the_author() ?></span>
					</div>
				</div>
				
                <?php the_content(__('')); ?>
                
                <div class="meta">
                	<a href="<?php the_permalink() ?>#comment"><?php comments_number(); ?></a> <?php meta_entries($post->ID); ?>
				</div>
			<?php endif; ?>
                
            </div>
            
            <?php comments_template(); ?>
            
        <?php endwhile; ?>
        <?php else: ?>
		<p>Sorry, no posts matched your criteria.</p>
        <?php endif; ?>
        	
        </div>
<!--#content:end-->
<?php get_sidebar(); ?>
<?php get_footer(); ?>