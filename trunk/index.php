<?php get_header(); ?>
<!--#content:start-->
    	<div id="content" class="home">
    		<div class="about">
    			<img class="avatar" src="<?php bloginfo('template_directory'); ?>/images/site.jpg" alt="" />
				<?php echo get_option('ci_about'); ?>
			</div>
			
			<?php 
			include "extra/content-format.php";
			
			if(function_exists('wp_pagenavi')) : 
				wp_pagenavi();
			else :
			?>
				<div class="pagination">
	                <div class="prev-page"><?php next_posts_link('Older Entries') ?></div>
	                <div class="next-page"><?php previous_posts_link('Newer Entries') ?></div>
	            </div>
    
			<?php endif; ?>
		</div>
<!--#content:end-->
<?php get_sidebar(); ?>
<?php get_footer(); ?>