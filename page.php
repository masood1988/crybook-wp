<?php get_header(); ?>
<!--#content:start-->
    	<div id="content" class="page">
			<?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <div class="entry" id="post-<?php the_ID(); ?>">
                    <div class="header">
                    	<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
                    </div>
                    <?php 
                    the_content(__(''));
                    wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); 
                    edit_post_link('Edit this entry.', '<p>', '</p>'); 
                    ?>
                </div>
            <?php endwhile; ?>
            <?php endif; ?>

        </div>
<!--#content:end-->
<?php get_sidebar(); ?>
<?php get_footer(); ?>