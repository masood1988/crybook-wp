<?php if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME'])) : 
	die ('Please do not load this page directly. Thanks!'); 
endif; ?>

<?php if (post_password_required()) : ?>
	<p class="nocomments">
		<?php _e("This post is password protected. Enter the password to view comments."); ?>
	</p>
<?php return; endif;  ?>

<!-- You can start editing here. -->

<div id="comment">
	<?php if ( have_comments() ) : ?>
		<ul class="commentlist">
		<?php
			wp_list_comments('type=pings&callback=ci_list_pings');
			wp_list_comments('&type=comment&callback=ci_list_comments'); 
		?>
		</ul>
		<div class="paginate">
			<?php echo paginate_comments_links(); ?>
		</div>
	<?php endif; ?>
</div>

<div id="respond">
<?php if ('open' == $post-> comment_status) : ?>
	<h3><?php comment_form_title(); ?></h3>
	<div id="cancel-comment-reply">
		<small><?php cancel_comment_reply_link() ?></small>
	</div>
	
	<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
		<p>You must be <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php the_permalink(); ?>">logged in</a> to post a comment.</p>
	<?php else : ?>

	<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
		<fieldset class="compact">
	
		<?php if ( $user_ID ) : ?>
			<p class="logged">Logged in as <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="<?php _e('Log out of this account') ?>">Logout &raquo;</a></p>

		<?php else : ?>

			<div class="panel">
	            <label for="author">Name <strong><?php if ($req) _e('*'); ?></strong></label>
	            <input class="required" type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="35" tabindex="1" />
			</div>
	        <div class="panel">
	            <label for="email">E-mail (will not be published) <strong><?php if ($req) _e('*'); ?></strong></label>
	            <input class="required email" type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="35" tabindex="2" />
			</div>
	        <div class="panel">
	            <label for="url">Website</label>
	            <input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="35" tabindex="3" />
			</div>
		<?php endif; ?>

		<div class="panel">
            <label for="comment">Comment</label>
            <textarea class="required min-15" name="comment" id="comment" cols="60" rows="10" tabindex="4"></textarea>
            <em><strong>XHTML:</strong> You can use these tags: <?php echo allowed_tags(); ?></em>
		</div>
        <div class="button">
        	<input name="submit" type="submit" id="submit" tabindex="5" value="Submit Comment" title="Please review your comment before submitting" />
		</div>
		<?php comment_id_fields(); ?>
		<?php do_action('comment_form', $post->ID); ?>
		</fieldset>
	</form>
<script type="text/javascript">
<!--
$(function() {
	var form = new Js.ext.validate;
	$("#commentform").submit(function() {
		var form = new Js.ext.validate(this);
		
		if( !!form.cacheResult ) {
			return true;
		} 
		else {
			alert("Please fill in all required fields (marked by *)");
		}
		return false;
	});
	
	$("#response-comment").find("a.url").bind("click", function() {
		var img = $(this).parent().parent().find("img:first-child");
		var name = $(this).html();
		var href = $(this).attr("href");
		var text = "Check out " + name + "'s website at <a href='" + href + "'>" + href + "</a>";
		
		var node = new Js.widget.dialog({
			element: "commenter",
			overlay: true,
			title: name,
			width: 350
		});
		
		img.clone( true ).css({
			margin: "0 5px 0 0",
			cssFloat: "left"
		}).appendTo( node.content[0] );
		Js.use("<span/>").html(text).appendTo( node.content[0] );
		return false;
	});
});
-->
</script>

<?php endif; // If registration required and not logged in ?>

<?php endif; // if you delete this the sky will fall on your head ?>
</div>